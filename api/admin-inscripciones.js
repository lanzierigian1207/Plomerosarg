const KNOWN_EVENTS = [
  "Bahía Blanca 11/3",
  "Mar del Plata 14/3",
  "Mendoza 9/5",
  "Salta 6/6",
  "San Luis 8/8"
];

const PROFESION_LABELS = {
  plomero: "Plomero",
  gasista: "Gasista",
  oficial_plomero: "Oficial Plomero",
  ayudante_plomero: "Ayudante de Plomero",
  medio_oficial_plomero: "Medio Oficial Plomero",
  maestro_mayor_obra: "Maestro Mayor de Obra",
  arquitecto_ingeniero: "Arquitecto/Ingeniero",
  estudiante_centro_formacion: "Estudiante de centro de formación",
  expositor: "Expositor",
  otros: "Otros"
};

function cleanText(value, maxLength = 120) {
  const text = String(value ?? "").trim().replace(/<[^>]*>/g, "");
  return text.length > maxLength ? text.slice(0, maxLength) : text;
}

function normalizeEventKey(value) {
  return String(value ?? "")
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .replace(/[^\w\s/.-]/g, " ")
    .toLowerCase()
    .replace(/\s+/g, " ")
    .trim();
}

const EVENT_CANONICAL_BY_KEY = new Map(
  KNOWN_EVENTS.map((eventName) => [normalizeEventKey(eventName), eventName])
);

const EVENT_FALLBACK_MATCHERS = [
  {
    name: "Bahía Blanca 11/3",
    matches: (key) =>
      key.includes("bahia blanca 11/3") ||
      (key.includes("bah") && key.includes("blanca") && key.includes("11/3"))
  },
  {
    name: "Mar del Plata 14/3",
    matches: (key) =>
      key.includes("mar del plata 14/3") ||
      (key.includes("mar") && key.includes("plata") && key.includes("14/3"))
  },
  {
    name: "Mendoza 9/5",
    matches: (key) =>
      key.includes("mendoza 9/5") || (key.includes("mendoza") && key.includes("9/5"))
  },
  {
    name: "Salta 6/6",
    matches: (key) =>
      key.includes("salta 6/6") || (key.includes("salta") && key.includes("6/6"))
  },
  {
    name: "San Luis 8/8",
    matches: (key) =>
      key.includes("san luis 8/8") ||
      (key.includes("san") && key.includes("luis") && key.includes("8/8"))
  }
];

function getCanonicalEventName(value) {
  const raw = cleanText(value, 80);
  const key = normalizeEventKey(raw);

  if (!key) {
    return "Sin evento";
  }

  if (EVENT_CANONICAL_BY_KEY.has(key)) {
    return EVENT_CANONICAL_BY_KEY.get(key);
  }

  const fallback = EVENT_FALLBACK_MATCHERS.find((item) => item.matches(key));
  if (fallback) {
    return fallback.name;
  }

  return raw;
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

module.exports = async (req, res) => {
  if (req.method !== "GET") {
    return res.status(405).json({
      ok: false,
      error: "Método no permitido. Usá GET."
    });
  }

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
    const response = await fetch(endpoint.toString(), {
      method: "GET",
      headers: {
        apikey: serviceRoleKey,
        Authorization: `Bearer ${serviceRoleKey}`
      }
    });

    if (!response.ok) {
      const detail = await response.text().catch(() => "");
      return res.status(500).json({
        ok: false,
        error: "No se pudo consultar la base de datos.",
        detail
      });
    }

    const rows = await response.json().catch(() => []);
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

    const eventos = orderedEvents.map((eventName) => {
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
        inscripciones
      };
    });

    return res.status(200).json({
      ok: true,
      total: Array.isArray(rows) ? rows.length : 0,
      eventos,
      generated_at: new Date().toISOString()
    });
  } catch (error) {
    return res.status(500).json({
      ok: false,
      error: "Error interno al consultar inscripciones.",
      detail: error instanceof Error ? error.message : "Error desconocido"
    });
  }
};
