const crypto = require("crypto");

const {
  KNOWN_EVENTS,
  cleanText,
  getCanonicalEventName,
  fetchEventStatusMap,
  resolveEventActive,
  upsertEventStatus
} = require("./_encuentros");

const COOKIE_NAME = "admin_session";
const ROLE_ADMIN = "admin";
const ROLE_ASISTENCIA = "asistencia";
const STATUS_TABLE = "encuentros_estado";
const ATTENDANCE_KEY_PREFIX = "__attendance__::";
const RECONFIRM_KEY_PREFIX = "__reconfirm__::";

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

function normalizeDniValue(value) {
  return String(value ?? "")
    .replace(/\D+/g, "")
    .trim();
}

function buildInscriptoMapKey(eventName, dni) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return "";
  }
  return `${canonicalEvent}::${normalizedDni}`;
}

function buildAttendanceMapKey(eventName, dni) {
  return buildInscriptoMapKey(eventName, dni);
}

function buildAttendanceStorageKey(eventName, dni) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return "";
  }
  return `${ATTENDANCE_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}`;
}

function parseAttendanceStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(ATTENDANCE_KEY_PREFIX)) {
    return null;
  }

  const tail = raw.slice(ATTENDANCE_KEY_PREFIX.length);
  const separatorIndex = tail.lastIndexOf("::");
  if (separatorIndex <= 0) {
    return null;
  }

  const encodedEvent = tail.slice(0, separatorIndex);
  const normalizedDni = normalizeDniValue(tail.slice(separatorIndex + 2));
  if (!normalizedDni) {
    return null;
  }

  let decodedEvent = "";
  try {
    decodedEvent = decodeURIComponent(encodedEvent);
  } catch {
    return null;
  }

  const canonicalEvent = getCanonicalEventName(decodedEvent);
  if (!canonicalEvent || canonicalEvent === "Sin evento") {
    return null;
  }

  return {
    eventName: canonicalEvent,
    dni: normalizedDni
  };
}

function parseReconfirmStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(RECONFIRM_KEY_PREFIX)) {
    return null;
  }

  const tail = raw.slice(RECONFIRM_KEY_PREFIX.length);
  const separatorIndex = tail.lastIndexOf("::");
  if (separatorIndex <= 0) {
    return null;
  }

  const encodedEvent = tail.slice(0, separatorIndex);
  const normalizedDni = normalizeDniValue(tail.slice(separatorIndex + 2));
  if (!normalizedDni) {
    return null;
  }

  let decodedEvent = "";
  try {
    decodedEvent = decodeURIComponent(encodedEvent);
  } catch {
    return null;
  }

  const canonicalEvent = getCanonicalEventName(decodedEvent);
  if (!canonicalEvent || canonicalEvent === "Sin evento") {
    return null;
  }

  return {
    eventName: canonicalEvent,
    dni: normalizedDni
  };
}

async function fetchAttendanceAndReconfirmStateMaps({ supabaseUrl, serviceRoleKey }) {
  const attendanceMap = new Map();
  const reconfirmMap = new Map();

  if (!supabaseUrl || !serviceRoleKey) {
    return { attendanceMap, reconfirmMap };
  }

  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/${STATUS_TABLE}`
  );
  endpoint.searchParams.set("select", "encuentro,activo");
  endpoint.searchParams.set("order", "encuentro.asc");
  endpoint.searchParams.set("limit", "20000");

  const response = await fetch(endpoint.toString(), {
    method: "GET",
    headers: {
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`
    }
  });

  if (!response.ok) {
    return { attendanceMap, reconfirmMap };
  }

  const rows = await response.json().catch(() => []);
  for (const row of Array.isArray(rows) ? rows : []) {
    const parsedAttendance = parseAttendanceStorageKey(row.encuentro);
    if (parsedAttendance) {
      const attendanceKey = buildAttendanceMapKey(parsedAttendance.eventName, parsedAttendance.dni);
      if (attendanceKey) {
        attendanceMap.set(attendanceKey, row.activo !== false);
      }
    }

    const parsedReconfirm = parseReconfirmStorageKey(row.encuentro);
    if (parsedReconfirm) {
      const reconfirmKey = buildInscriptoMapKey(parsedReconfirm.eventName, parsedReconfirm.dni);
      if (reconfirmKey) {
        reconfirmMap.set(reconfirmKey, row.activo !== false);
      }
    }
  }

  return { attendanceMap, reconfirmMap };
}

async function upsertAttendanceState({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  dni,
  asistio
}) {
  const storageKey = buildAttendanceStorageKey(eventName, dni);
  if (!storageKey) {
    return {
      ok: false,
      error: "Encuentro o DNI invalido.",
      tableMissing: false
    };
  }

  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/${STATUS_TABLE}`
  );
  endpoint.searchParams.set("on_conflict", "encuentro");

  const response = await fetch(endpoint.toString(), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`,
      Prefer: "resolution=merge-duplicates,return=representation"
    },
    body: JSON.stringify([
      {
        encuentro: storageKey,
        activo: asistio !== false
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar la asistencia (${response.status}).`,
      detail,
      tableMissing: detail.toLowerCase().includes("does not exist")
    };
  }

  return {
    ok: true
  };
}

function parseCookies(req) {
  const raw = String(req?.headers?.cookie || "");
  const cookies = {};

  raw.split(";").forEach((part) => {
    const index = part.indexOf("=");
    if (index <= 0) return;
    const key = part.slice(0, index).trim();
    const value = part.slice(index + 1).trim();
    cookies[key] = decodeURIComponent(value);
  });

  return cookies;
}

function timingSafeEqual(a, b) {
  const buffA = Buffer.from(String(a || ""));
  const buffB = Buffer.from(String(b || ""));
  if (buffA.length !== buffB.length) return false;
  return crypto.timingSafeEqual(buffA, buffB);
}

function sign(value, secret) {
  return crypto.createHmac("sha256", secret).update(value).digest("base64url");
}

function decodeToken(token, secret) {
  if (!token || typeof token !== "string" || !token.includes(".")) {
    return null;
  }

  const [payload, signature] = token.split(".");
  if (!payload || !signature) return null;

  const expected = sign(payload, secret);
  if (!timingSafeEqual(signature, expected)) return null;

  try {
    const decoded = JSON.parse(Buffer.from(payload, "base64url").toString("utf8"));
    if (!decoded || typeof decoded !== "object") return null;
    if (typeof decoded.exp !== "number") return null;
    if (Date.now() > decoded.exp) return null;
    return decoded;
  } catch {
    return null;
  }
}

function normalizeAdminRole(value) {
  const normalized = String(value || "")
    .trim()
    .toLowerCase();

  if (normalized === ROLE_ASISTENCIA) {
    return ROLE_ASISTENCIA;
  }

  return ROLE_ADMIN;
}

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

async function handleGet(req, res, adminRole) {
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
    const [inscripcionesResponse, statusResult, flagsState] = await Promise.all([
      fetch(endpoint.toString(), {
        method: "GET",
        headers: {
          apikey: serviceRoleKey,
          Authorization: `Bearer ${serviceRoleKey}`
        }
      }),
      fetchEventStatusMap({ supabaseUrl, serviceRoleKey }),
      fetchAttendanceAndReconfirmStateMaps({ supabaseUrl, serviceRoleKey })
    ]);
    const attendanceMap = flagsState?.attendanceMap instanceof Map
      ? flagsState.attendanceMap
      : new Map();
    const reconfirmMap = flagsState?.reconfirmMap instanceof Map
      ? flagsState.reconfirmMap
      : new Map();

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
        created_at: row.created_at || null,
        asistio:
          attendanceMap.get(buildAttendanceMapKey(eventName, row.dni)) === true,
        reconfirmado:
          reconfirmMap.get(buildInscriptoMapKey(eventName, row.dni)) === true
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

    const limitedForAsistencia = adminRole === ROLE_ASISTENCIA;
    const finalEventos = limitedForAsistencia
      ? eventos.map((eventItem) => ({
          ...eventItem,
          inscripciones: (eventItem.inscripciones || []).map((row) => ({
            id_evento: row.id_evento,
            dni: row.dni,
            nombre_apellido: row.nombre_apellido,
            mail: row.mail,
            localidad: row.localidad,
            created_at: row.created_at,
            asistio: row.asistio === true,
            reconfirmado: row.reconfirmado === true
          }))
        }))
      : eventos;

    return res.status(200).json({
      ok: true,
      total: Array.isArray(rows) ? rows.length : 0,
      eventos: finalEventos,
      role: adminRole,
      permissions: {
        can_view_inscripciones: adminRole === ROLE_ADMIN,
        can_manage_events: adminRole === ROLE_ADMIN
      },
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

async function handlePost(req, res, adminRole) {
  const supabaseUrl = process.env.SUPABASE_URL;
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables de entorno de Supabase."
    });
  }

  const payload = normalizeBody(req.body);
  const action = cleanText(payload.action, 40).toLowerCase();

  if (action === "set_attendance") {
    const eventInput = cleanText(payload.evento, 80);
    const canonicalEvent = getCanonicalEventName(eventInput);
    const dni = normalizeDniValue(payload.dni);
    const asistio = parseBooleanInput(payload.asistio);

    if (!canonicalEvent || canonicalEvent === "Sin evento") {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar un encuentro valido."
      });
    }

    if (!dni) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar un DNI valido."
      });
    }

    if (asistio === null) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar asistio true/false."
      });
    }

    const savedAttendance = await upsertAttendanceState({
      supabaseUrl,
      serviceRoleKey,
      eventName: canonicalEvent,
      dni,
      asistio
    });

    if (!savedAttendance.ok) {
      return res.status(500).json({
        ok: false,
        error: savedAttendance.error || "No se pudo guardar la asistencia.",
        detail: savedAttendance.detail || ""
      });
    }

    return res.status(200).json({
      ok: true,
      action: "set_attendance",
      evento: canonicalEvent,
      dni,
      asistio: asistio === true,
      updated_at: new Date().toISOString()
    });
  }

  if (adminRole !== ROLE_ADMIN) {
    return res.status(403).json({
      ok: false,
      error: "No autorizado para modificar el estado de encuentros."
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
  const adminSessionSecret = String(process.env.ADMIN_SESSION_SECRET || "").trim();

  if (!adminSessionSecret) {
    return res.status(500).json({
      ok: false,
      error: "Falta ADMIN_SESSION_SECRET para validar la sesion admin."
    });
  }

  const cookies = parseCookies(req);
  const session = decodeToken(cookies[COOKIE_NAME], adminSessionSecret);
  if (!session) {
    return res.status(401).json({
      ok: false,
      error: "No autorizado. Inicia sesion en /api/admin."
    });
  }
  const adminRole = normalizeAdminRole(session.r);

  if (req.method === "GET") {
    return handleGet(req, res, adminRole);
  }

  if (req.method === "POST") {
    return handlePost(req, res, adminRole);
  }

  return res.status(405).json({
    ok: false,
    error: "Metodo no permitido. Usa GET o POST."
  });
};
