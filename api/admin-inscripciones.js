const {
  KNOWN_EVENTS,
  cleanText,
  getCanonicalEventName,
  fetchEventStatusMap,
  resolveEventActive,
  upsertEventStatus
} = require("./_encuentros");

const PROFESION_LABELS = {
  plomero: "Plomero",
  gasista: "Gasista",
  oficial_plomero: "Oficial Plomero",
  ayudante_plomero: "Ayudante de Plomero",
  medio_oficial_plomero: "Medio Oficial Plomero",
  maestro_mayor_obra: "Maestro Mayor de Obra",
  arquitecto_ingeniero: "Arquitecto/Ingeniero",
  estudiante_centro_formacion: "Estudiante de centro de formacion",
  expositor: "Expositor",
  otros: "Otros"
};

const STATUS_TABLE_SQL = [
  "create table if not exists public.encuentros_estado (",
  "  encuentro text primary key,",
  "  activo boolean not null default true,",
  "  updated_at timestamptz not null default now()",
  ");"
].join("\n");

function normalizeBody(body) {
  if (!body) return {};

  if (typeof body === "string") {
    try {
      const parsed = JSON.parse(body);
      return parsed && typeof parsed === "object" ? parsed : {};
    } catch {
      return {};
    }
  }

  if (typeof body === "object") {
    return body;
  }

  return {};
}

function parseBooleanInput(value) {
  if (typeof value === "boolean") {
    return value;
  }

  const normalized = String(value ?? "")
    .trim()
    .toLowerCase();

  if (["1", "true", "si", "s", "on", "activo", "activar"].includes(normalized)) {
    return true;
  }

  if (["0", "false", "no", "n", "off", "inactivo", "desactivar"].includes(normalized)) {
    return false;
  }

  return null;
}

function formatProfesion(value) {
  return String(value ?? "")
    .split(",")
    .map((item) => item.trim())
    .filter(Boolean)
    .map((item) => PROFESION_LABELS[item] || item)
    .join(", ");
}

function getComparableTimestamp(value) {
  const timestamp = Date.parse(String(value ?? ""));
  return Number.isFinite(timestamp) ? timestamp : null;
}

function compareRegistrationOrder(a, b) {
  const timeA = getComparableTimestamp(a.created_at);
  const timeB = getComparableTimestamp(b.created_at);

  if (timeA !== null && timeB !== null && timeA !== timeB) {
    return timeA - timeB;
  }

  const idA = Number.parseInt(String(a.id ?? ""), 10);
  const idB = Number.parseInt(String(b.id ?? ""), 10);

  if (Number.isFinite(idA) && Number.isFinite(idB) && idA !== idB) {
    return idA - idB;
  }

  return String(a.id ?? "").localeCompare(String(b.id ?? ""), "es");
}

async function handleGet(req, res) {
  const supabaseUrl = process.env.SUPABASE_URL;
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables de entorno de Supabase."
    });
  }

  const limitInput = Number.parseInt(String(req.query?.limit ?? "5000"), 10);
  const limit = Number.isFinite(limitInput)
    ? Math.min(Math.max(limitInput, 1), 10000)
    : 5000;

  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`
  );
  endpoint.searchParams.set(
    "select",
    "id,encuentro,dni,nombre_apellido,mail,provincia,localidad,asociado,profesion,origen,acepto_terminos,created_at"
  );
  endpoint.searchParams.set("order", "id.asc");
  endpoint.searchParams.set("limit", String(limit));

  try {
    const [inscripcionesResponse, statusResult] = await Promise.all([
      fetch(endpoint.toString(), {
        method: "GET",
        headers: {
          apikey: serviceRoleKey,
          Authorization: `Bearer ${serviceRoleKey}`
        }
      }),
      fetchEventStatusMap({ supabaseUrl, serviceRoleKey })
    ]);

    if (!inscripcionesResponse.ok) {
      const detail = await inscripcionesResponse.text().catch(() => "");
      return res.status(500).json({
        ok: false,
        error: "No se pudo consultar la base de datos.",
        detail
      });
    }

    const rows = await inscripcionesResponse.json().catch(() => []);
    const grouped = new Map();

    for (const row of Array.isArray(rows) ? rows : []) {
      const eventName = getCanonicalEventName(row.encuentro);
      const normalizedRow = {
        id: row.id ?? null,
        encuentro: eventName,
        dni: cleanText(row.dni, 20),
        nombre_apellido: cleanText(row.nombre_apellido, 120),
        mail: cleanText(row.mail, 120),
        provincia: cleanText(row.provincia, 40),
        localidad: cleanText(row.localidad, 120),
        asociado: cleanText(row.asociado, 5),
        profesion: cleanText(row.profesion, 300),
        profesion_label: formatProfesion(row.profesion),
        origen: cleanText(row.origen, 40),
        created_at: row.created_at || null
      };

      if (!grouped.has(eventName)) {
        grouped.set(eventName, []);
      }

      grouped.get(eventName).push(normalizedRow);
    }

    const dynamicEvents = [...grouped.keys()]
      .filter((name) => !KNOWN_EVENTS.includes(name) && name !== "Sin evento")
      .sort((a, b) => a.localeCompare(b, "es"));

    const orderedEvents = [
      ...KNOWN_EVENTS,
      ...dynamicEvents,
      ...(grouped.has("Sin evento") ? ["Sin evento"] : [])
    ];

    const statusEvents = [...statusResult.map.keys()].filter(
      (name) => !orderedEvents.includes(name) && name !== "Sin evento"
    );

    statusEvents.sort((a, b) => a.localeCompare(b, "es"));
    const fullOrder = [...orderedEvents, ...statusEvents];

    const eventos = fullOrder.map((eventName) => {
      const inscripciones = (grouped.get(eventName) || [])
        .slice()
        .sort(compareRegistrationOrder)
        .map((item, index) => ({
          ...item,
          id_evento: index + 1
        }));

      return {
        evento: eventName,
        contador: inscripciones.length,
        activo: resolveEventActive({
          eventName,
          statusMap: statusResult.map
        }),
        inscripciones
      };
    });

    return res.status(200).json({
      ok: true,
      total: Array.isArray(rows) ? rows.length : 0,
      eventos,
      status_available: statusResult.available,
      status_warning: statusResult.warning || "",
      generated_at: new Date().toISOString()
    });
  } catch (error) {
    return res.status(500).json({
      ok: false,
      error: "Error interno al consultar inscripciones.",
      detail: error instanceof Error ? error.message : "Error desconocido"
    });
  }
}

async function handlePost(req, res) {
  const supabaseUrl = process.env.SUPABASE_URL;
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables de entorno de Supabase."
    });
  }

  const payload = normalizeBody(req.body);
  const action = cleanText(payload.accion || payload.action, 80).toLowerCase();

  if (action === "reset_inscripciones_activos") {
    const confirmacion = cleanText(payload.confirmacion || payload.confirm, 80);

    if (confirmacion !== "BORRAR_INSCRIPTOS_ACTIVOS") {
      return res.status(422).json({
        ok: false,
        error:
          "Confirmacion invalida. Usa confirmacion=BORRAR_INSCRIPTOS_ACTIVOS para continuar."
      });
    }

    const statusResult = await fetchEventStatusMap({ supabaseUrl, serviceRoleKey });

    if (!statusResult.available) {
      return res.status(500).json({
        ok: false,
        error:
          "No se pudo leer el estado de encuentros. Se cancela el borrado para evitar eliminar encuentros incorrectos.",
        warning: statusResult.warning || ""
      });
    }

    const activeEvents = [...statusResult.map.entries()]
      .filter(([, isActive]) => isActive !== false)
      .map(([eventName]) => getCanonicalEventName(eventName))
      .filter((eventName) => eventName && eventName !== "Sin evento");

    if (activeEvents.length === 0) {
      return res.status(200).json({
        ok: true,
        message: "No hay encuentros activos para borrar.",
        deleted_total: 0,
        eventos: []
      });
    }

    const results = [];
    let deletedTotal = 0;

    for (const activeEvent of activeEvents) {
      const deleteUrl = new URL(
        `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`
      );
      deleteUrl.searchParams.set("select", "id,encuentro,created_at");
      deleteUrl.searchParams.set("encuentro", `eq.${activeEvent}`);

      const deleteResponse = await fetch(deleteUrl.toString(), {
        method: "DELETE",
        headers: {
          apikey: serviceRoleKey,
          Authorization: `Bearer ${serviceRoleKey}`,
          Prefer: "return=representation"
        }
      });

      if (!deleteResponse.ok) {
        const detail = await deleteResponse.text().catch(() => "");
        return res.status(500).json({
          ok: false,
          error: `No se pudo borrar inscripciones del encuentro ${activeEvent}.`,
          detail
        });
      }

      const deletedRows = await deleteResponse.json().catch(() => []);
      const deletedCount = Array.isArray(deletedRows) ? deletedRows.length : 0;
      deletedTotal += deletedCount;

      results.push({
        evento: activeEvent,
        deleted: deletedCount
      });
    }

    return res.status(200).json({
      ok: true,
      message: "Inscripciones de encuentros activos borradas.",
      deleted_total: deletedTotal,
      eventos: results,
      executed_at: new Date().toISOString()
    });
  }

  const eventInput = cleanText(payload.evento, 80);
  const activeValue = parseBooleanInput(payload.activo);

  if (!eventInput) {
    return res.status(422).json({
      ok: false,
      error: "Tenes que indicar un encuentro."
    });
  }

  if (activeValue === null) {
    return res.status(422).json({
      ok: false,
      error: "Tenes que indicar activo true/false."
    });
  }

  const saved = await upsertEventStatus({
    supabaseUrl,
    serviceRoleKey,
    encuentro: eventInput,
    activo: activeValue
  });

  if (!saved.ok) {
    if (saved.tableMissing) {
      return res.status(500).json({
        ok: false,
        error:
          "No existe la tabla de estados de encuentros. Creala una sola vez en Supabase y volve a intentar.",
        sql: STATUS_TABLE_SQL
      });
    }

    return res.status(500).json({
      ok: false,
      error: saved.error || "No se pudo guardar el estado del encuentro."
    });
  }

  return res.status(200).json({
    ok: true,
    evento: saved.eventName,
    activo: saved.active,
    updated_at: new Date().toISOString()
  });
}

module.exports = async (req, res) => {
  if (req.method === "GET") {
    return handleGet(req, res);
  }

  if (req.method === "POST") {
    return handlePost(req, res);
  }

  return res.status(405).json({
    ok: false,
    error: "Metodo no permitido. Usa GET o POST."
  });
};
