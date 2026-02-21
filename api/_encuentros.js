const KNOWN_EVENTS = [
  "Bah\u00eda Blanca 11/3",
  "Mar del Plata 14/3",
  "Mendoza 9/5",
  "Salta 6/6",
  "San Luis 8/8"
];

const EVENT_STATUS_TABLE = "encuentros_estado";

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
    name: "Bah\u00eda Blanca 11/3",
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

function buildSupabaseEndpoint(supabaseUrl, tableName) {
  return `${String(supabaseUrl || "").replace(/\/$/, "")}/rest/v1/${tableName}`;
}

function buildSupabaseHeaders(serviceRoleKey, extraHeaders = {}) {
  return {
    apikey: serviceRoleKey,
    Authorization: `Bearer ${serviceRoleKey}`,
    ...extraHeaders
  };
}

function getErrorSummary(detail) {
  const raw = String(detail ?? "").trim();
  if (!raw) return "";

  try {
    const parsed = JSON.parse(raw);
    const parts = [parsed.message, parsed.details, parsed.hint].filter(Boolean);
    if (parts.length > 0) {
      return parts.join(" ");
    }
  } catch {
    return raw.slice(0, 400);
  }

  return raw.slice(0, 400);
}

function isMissingStatusTable(statusCode, detail) {
  const normalized = String(detail ?? "").toLowerCase();
  return (
    statusCode === 404 ||
    normalized.includes("42p01") ||
    normalized.includes("does not exist") ||
    normalized.includes("could not find the table") ||
    normalized.includes(EVENT_STATUS_TABLE)
  );
}

function parseJsonSafely(value, fallbackValue) {
  try {
    const parsed = JSON.parse(String(value ?? ""));
    return parsed;
  } catch {
    return fallbackValue;
  }
}

async function fetchEventStatusMap({ supabaseUrl, serviceRoleKey }) {
  const result = {
    map: new Map(),
    available: false,
    warning: ""
  };

  if (!supabaseUrl || !serviceRoleKey) {
    result.warning = "Sin credenciales de Supabase para consultar estados.";
    return result;
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_STATUS_TABLE));
  endpoint.searchParams.set("select", "encuentro,activo");
  endpoint.searchParams.set("order", "encuentro.asc");
  endpoint.searchParams.set("limit", "500");

  const response = await fetch(endpoint.toString(), {
    method: "GET",
    headers: buildSupabaseHeaders(serviceRoleKey)
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");

    if (isMissingStatusTable(response.status, detail)) {
      result.warning =
        "Tabla de estado de encuentros no disponible. Todos los encuentros quedan activos por defecto.";
      return result;
    }

    result.warning =
      `No se pudo leer el estado de encuentros (${response.status}). ` +
      `${getErrorSummary(detail) || "Todo queda activo por defecto."}`;
    return result;
  }

  const rows = await response.json().catch(() => []);

  for (const row of Array.isArray(rows) ? rows : []) {
    const eventName = getCanonicalEventName(row.encuentro);
    result.map.set(eventName, row.activo !== false);
  }

  result.available = true;
  return result;
}

function resolveEventActive({ eventName, statusMap }) {
  if (!(statusMap instanceof Map)) {
    return true;
  }

  const canonical = getCanonicalEventName(eventName);
  if (!statusMap.has(canonical)) {
    return true;
  }

  return statusMap.get(canonical) !== false;
}

async function getEventStatus({ supabaseUrl, serviceRoleKey, encuentro }) {
  const canonicalEvent = getCanonicalEventName(encuentro);

  if (!supabaseUrl || !serviceRoleKey) {
    return {
      ok: false,
      error: "Faltan variables de entorno de Supabase."
    };
  }

  if (!canonicalEvent || canonicalEvent === "Sin evento") {
    return {
      ok: false,
      error: "Encuentro invalido."
    };
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_STATUS_TABLE));
  endpoint.searchParams.set("select", "encuentro,activo");
  endpoint.searchParams.set("encuentro", `eq.${canonicalEvent}`);
  endpoint.searchParams.set("limit", "1");

  const response = await fetch(endpoint.toString(), {
    method: "GET",
    headers: buildSupabaseHeaders(serviceRoleKey)
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");

    if (isMissingStatusTable(response.status, detail)) {
      return {
        ok: true,
        active: true,
        configured: false,
        available: false
      };
    }

    return {
      ok: false,
      error: `No se pudo consultar el estado del encuentro (${response.status}). ${getErrorSummary(detail)}`
    };
  }

  const rows = await response.json().catch(() => []);
  if (!Array.isArray(rows) || rows.length === 0) {
    return {
      ok: true,
      active: true,
      configured: false,
      available: true
    };
  }

  return {
    ok: true,
    active: rows[0].activo !== false,
    configured: true,
    available: true
  };
}

async function upsertEventStatus({ supabaseUrl, serviceRoleKey, encuentro, activo }) {
  const canonicalEvent = getCanonicalEventName(encuentro);

  if (!supabaseUrl || !serviceRoleKey) {
    return {
      ok: false,
      error: "Faltan variables de entorno de Supabase.",
      tableMissing: false
    };
  }

  if (!canonicalEvent || canonicalEvent === "Sin evento") {
    return {
      ok: false,
      error: "Encuentro invalido.",
      tableMissing: false
    };
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_STATUS_TABLE));
  endpoint.searchParams.set("on_conflict", "encuentro");

  const response = await fetch(endpoint.toString(), {
    method: "POST",
    headers: buildSupabaseHeaders(serviceRoleKey, {
      "Content-Type": "application/json",
      Prefer: "resolution=merge-duplicates,return=representation"
    }),
    body: JSON.stringify([
      {
        encuentro: canonicalEvent,
        activo: activo !== false
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar el estado del encuentro (${response.status}). ${getErrorSummary(detail)}`,
      tableMissing: isMissingStatusTable(response.status, detail),
      detail: parseJsonSafely(detail, detail)
    };
  }

  const rows = await response.json().catch(() => []);
  const saved = Array.isArray(rows) && rows[0] ? rows[0] : null;

  return {
    ok: true,
    eventName: getCanonicalEventName(saved?.encuentro || canonicalEvent),
    active: saved?.activo !== false
  };
}

module.exports = {
  KNOWN_EVENTS,
  cleanText,
  getCanonicalEventName,
  normalizeEventKey,
  fetchEventStatusMap,
  resolveEventActive,
  getEventStatus,
  upsertEventStatus
};
