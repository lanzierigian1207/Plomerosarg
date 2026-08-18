const { fetchSupabaseRows } = require("./_supabase-pagination");

const KNOWN_EVENTS = [
  "Bah\u00eda Blanca 11/3",
  "Mar del Plata 14/3",
  "Mendoza 9/5",
  "Salta 6/6",
  "San Luis 8/8"
];

const EVENT_CATALOG_TABLE = "encuentros";
const EVENT_STATUS_TABLE = "encuentros_estado";
const GENERIC_EVENT_REGISTRATION_PATH = "/inscripcion-evento.html";
const ATTENDANCE_KEY_PREFIX = "__attendance__::";
const LUNCH_KEY_PREFIX = "__lunch__::";
const RECONFIRM_KEY_PREFIX = "__reconfirm__::";
const RAFFLE_KEY_PREFIX = "__raffle__::";
const RAFFLE_BRAND_KEY_PREFIX = "__rafflebrand__::";
const CERTIFICATE_KEY_PREFIX = "__certificate__::";

const EVENT_CATALOG_SELECT = [
  "id",
  "nombre",
  "slug",
  "numero",
  "ciudad",
  "provincia",
  "fecha",
  "horario",
  "lugar",
  "direccion",
  "maps_url",
  "imagen_url",
  "whatsapp_url",
  "descripcion",
  "ingreso_solidario",
  "cupo",
  "activo",
  "certificado_activo",
  "mail_opcional",
  "asociado_opcional",
  "celular_obligatorio",
  "created_at",
  "updated_at"
].join(",");

const EVENT_CATALOG_TABLE_SQL = [
  "create table if not exists public.encuentros (",
  "  id bigserial primary key,",
  "  nombre text not null unique,",
  "  slug text not null unique,",
  "  numero integer,",
  "  ciudad text,",
  "  provincia text,",
  "  fecha text,",
  "  horario text,",
  "  lugar text,",
  "  direccion text,",
  "  maps_url text,",
  "  imagen_url text,",
  "  whatsapp_url text,",
  "  descripcion text,",
  "  ingreso_solidario text,",
  "  cupo integer,",
  "  activo boolean not null default true,",
  "  certificado_activo boolean not null default true,",
  "  mail_opcional boolean not null default false,",
  "  asociado_opcional boolean not null default false,",
  "  celular_obligatorio boolean not null default false,",
  "  created_at timestamptz not null default now(),",
  "  updated_at timestamptz not null default now()",
  ");"
].join("\n");

const DEFAULT_EVENT_CATALOG = [
  {
    nombre: "Bah\u00eda Blanca 11/3",
    slug: "bahia-blanca-11-3",
    numero: 27,
    ciudad: "Bah\u00eda Blanca",
    provincia: "Buenos Aires",
    fecha: "11/03/2026",
    horario: "14:00 hs",
    lugar: "Cam. de la Carrindanga 3802",
    direccion: "Cam. de la Carrindanga 3802, B8000 Bah\u00eda Blanca",
    maps_url: "https://share.google/viNow9oZuZHkSdo9C",
    imagen_url: "/Prueba_2/assets/bahia-blanca.jpg",
    descripcion: "Inscripci\u00f3n abierta para el encuentro en Bah\u00eda Blanca.",
    ingreso_solidario: "Traer uno o dos alimentos no perecederos para donar a una instituci\u00f3n de la zona",
    inscripcion_url: "/inscripcion-bahia-blanca.html",
    mail_opcional: true,
    asociado_opcional: false,
    celular_obligatorio: false
  },
  {
    nombre: "Mar del Plata 14/3",
    slug: "mar-del-plata-14-3",
    numero: 28,
    ciudad: "Mar del Plata",
    provincia: "Buenos Aires",
    fecha: "14/03/2026",
    horario: "08:30 hs",
    lugar: "Av. Pedro Luro 8851",
    direccion: "Av. Pedro Luro 8851, B7606 Mar del Plata",
    maps_url: "https://share.google/KA62Zn0H6wtiLd0La",
    imagen_url: "/Prueba_2/assets/mar-del-plata.webp",
    descripcion: "Inscripci\u00f3n abierta para el encuentro en Mar del Plata.",
    ingreso_solidario: "Traer uno o dos alimentos no perecederos para donar a una instituci\u00f3n de la zona",
    inscripcion_url: "/inscripcion-mar-del-plata.html",
    mail_opcional: true,
    asociado_opcional: false,
    celular_obligatorio: false
  },
  {
    nombre: "Mendoza 9/5",
    slug: "mendoza-9-5",
    numero: 29,
    ciudad: "Mendoza",
    provincia: "Mendoza",
    fecha: "09/05/2026",
    horario: "08:00 a 16:30 hs",
    lugar: "Espacio Cultural Julio Le Parc",
    direccion: "Mitre y Godoy Cruz, Guaymall\u00e9n, Mendoza",
    maps_url: "https://maps.app.goo.gl/Y49wHh722F2bY6uJ8",
    imagen_url: "/Prueba_2/assets/mendoza.jpg",
    descripcion: "Encuentro t\u00e9cnico para plomeros y sanitaristas.",
    ingreso_solidario: "Ingreso solidario: para participar del evento, es necesario llevar 2 alimentos no perecederos.",
    inscripcion_url: "/inscripcion-mendoza.html",
    mail_opcional: true,
    asociado_opcional: true,
    celular_obligatorio: false
  },
  {
    nombre: "Salta 6/6",
    slug: "salta-6-6",
    numero: 30,
    ciudad: "Salta",
    provincia: "Salta",
    fecha: "06/06/2026",
    horario: "08:30 hs",
    lugar: "Club Social Ahorrarte",
    direccion: "Club Social Ahorrarte, Salta",
    maps_url: "https://maps.app.goo.gl/sHd5dqsa6RnW2hws6",
    imagen_url: "/Prueba_2/assets/salta.jpg",
    descripcion: "Encuentro t\u00e9cnico para plomeros y sanitaristas.",
    ingreso_solidario: "Ingreso solidario: para participar del evento, es necesario llevar 2 alimentos no perecederos.",
    inscripcion_url: "/inscripcion-salta.html",
    mail_opcional: true,
    asociado_opcional: false,
    celular_obligatorio: false
  },
  {
    nombre: "San Luis 8/8",
    slug: "san-luis-8-8",
    numero: 31,
    ciudad: "Villa Mercedes",
    provincia: "San Luis",
    fecha: "07 y 08/08/2026",
    horario: "Viernes: 16:00-21:00 | S\u00e1bado: 09:00-18:00",
    lugar: "UPrO de Villa Mercedes",
    direccion: "UPrO de Villa Mercedes",
    maps_url: "https://share.google/7T00qtcB1UeGZJfir",
    imagen_url: "/Prueba_2/assets/san-luis.png",
    descripcion: "Inscripci\u00f3n abierta para el encuentro en San Luis.",
    ingreso_solidario: "Ingreso solidario: para participar del evento, es necesario llevar 2 alimentos no perecederos.",
    inscripcion_url: "/inscripcion-san-luis.html",
    mail_opcional: false,
    asociado_opcional: false,
    celular_obligatorio: true
  }
];

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

function slugifyEvent(value) {
  const slug = String(value ?? "")
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "")
    .slice(0, 80);

  return slug || "encuentro";
}

function parseBooleanValue(value, fallbackValue = false) {
  if (value === true || value === false) {
    return value;
  }

  const normalized = String(value ?? "")
    .trim()
    .toLowerCase();

  if (["true", "1", "si", "s\u00ed", "on", "yes"].includes(normalized)) {
    return true;
  }

  if (["false", "0", "no", "off"].includes(normalized)) {
    return false;
  }

  return fallbackValue;
}

function parsePositiveInteger(value) {
  const parsed = Number.parseInt(String(value ?? ""), 10);
  return Number.isFinite(parsed) && parsed > 0 ? parsed : null;
}

function buildEventRegistrationUrl(slugOrEventName) {
  const slug = slugifyEvent(slugOrEventName);
  return `${GENERIC_EVENT_REGISTRATION_PATH}?evento=${encodeURIComponent(slug)}`;
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

function isMissingTable(statusCode, detail, tableName = "") {
  const normalized = String(detail ?? "").toLowerCase();
  return (
    statusCode === 404 ||
    normalized.includes("42p01") ||
    normalized.includes("does not exist") ||
    normalized.includes("could not find the table")
  );
}

function isMissingStatusTable(statusCode, detail) {
  return isMissingTable(statusCode, detail, EVENT_STATUS_TABLE);
}

function isMissingCatalogTable(statusCode, detail) {
  return isMissingTable(statusCode, detail, EVENT_CATALOG_TABLE);
}

function parseJsonSafely(value, fallbackValue) {
  try {
    const parsed = JSON.parse(String(value ?? ""));
    return parsed;
  } catch {
    return fallbackValue;
  }
}

function normalizeUrl(value, maxLength = 500) {
  const text = cleanText(value, maxLength);
  if (!text) {
    return "";
  }

  if (
    text.startsWith("/") ||
    /^https?:\/\//i.test(text)
  ) {
    return text;
  }

  return "";
}

function normalizeEventCatalogRecord(row = {}, fallback = {}) {
  const rawName = cleanText(row.nombre || row.evento || row.encuentro || fallback.nombre, 80);
  const eventName = getCanonicalEventName(rawName);
  if (!eventName || eventName === "Sin evento") {
    return null;
  }

  const slug = slugifyEvent(row.slug || fallback.slug || eventName);
  const legacyUrl = cleanText(row.inscripcion_url || fallback.inscripcion_url, 180);
  const registrationUrl = legacyUrl || buildEventRegistrationUrl(slug);
  const numero = parsePositiveInteger(row.numero ?? fallback.numero);
  const cupo = parsePositiveInteger(row.cupo ?? fallback.cupo);

  return {
    id: row.id ?? fallback.id ?? null,
    evento: eventName,
    nombre: eventName,
    slug,
    numero,
    ciudad: cleanText(row.ciudad ?? fallback.ciudad, 80),
    provincia: cleanText(row.provincia ?? fallback.provincia, 80),
    fecha: cleanText(row.fecha ?? fallback.fecha, 80),
    horario: cleanText(row.horario ?? fallback.horario, 120),
    lugar: cleanText(row.lugar ?? fallback.lugar, 140),
    direccion: cleanText(row.direccion ?? fallback.direccion, 180),
    maps_url: normalizeUrl(row.maps_url ?? fallback.maps_url, 500),
    imagen_url: normalizeUrl(row.imagen_url ?? fallback.imagen_url, 500),
    whatsapp_url: normalizeUrl(row.whatsapp_url ?? fallback.whatsapp_url, 500),
    descripcion: cleanText(row.descripcion ?? fallback.descripcion, 300),
    ingreso_solidario: cleanText(row.ingreso_solidario ?? fallback.ingreso_solidario, 260),
    cupo,
    activo: parseBooleanValue(row.activo, fallback.activo !== false),
    certificado_activo: parseBooleanValue(
      row.certificado_activo,
      fallback.certificado_activo !== false
    ),
    mail_opcional: parseBooleanValue(row.mail_opcional, fallback.mail_opcional === true),
    asociado_opcional: parseBooleanValue(
      row.asociado_opcional,
      fallback.asociado_opcional === true
    ),
    celular_obligatorio: parseBooleanValue(
      row.celular_obligatorio,
      fallback.celular_obligatorio === true
    ),
    inscripcion_url: registrationUrl,
    created_at: row.created_at || fallback.created_at || null,
    updated_at: row.updated_at || fallback.updated_at || null,
    source: row.source || fallback.source || "catalog"
  };
}

function getDefaultEventCatalog() {
  return DEFAULT_EVENT_CATALOG
    .map((item) => normalizeEventCatalogRecord({ ...item, source: "default" }))
    .filter(Boolean);
}

function mergeEventCatalog(databaseRows = []) {
  const byKey = new Map();

  for (const defaultItem of getDefaultEventCatalog()) {
    byKey.set(normalizeEventKey(defaultItem.evento), defaultItem);
  }

  for (const row of Array.isArray(databaseRows) ? databaseRows : []) {
    const fallback = byKey.get(normalizeEventKey(row?.nombre || row?.evento || row?.encuentro)) || {};
    const normalized = normalizeEventCatalogRecord(
      { ...row, source: "catalog" },
      fallback
    );
    if (!normalized) continue;
    byKey.set(normalizeEventKey(normalized.evento), normalized);
  }

  return [...byKey.values()].sort((a, b) => {
    const aNumber = Number.isFinite(a.numero) ? a.numero : Number.MAX_SAFE_INTEGER;
    const bNumber = Number.isFinite(b.numero) ? b.numero : Number.MAX_SAFE_INTEGER;
    if (aNumber !== bNumber) return aNumber - bNumber;
    return a.evento.localeCompare(b.evento, "es");
  });
}

function buildEventCatalogMap(eventos) {
  const map = new Map();
  for (const item of Array.isArray(eventos) ? eventos : []) {
    if (!item) continue;
    const nameKey = normalizeEventKey(item.evento || item.nombre);
    const slugKey = slugifyEvent(item.slug || item.evento || item.nombre);
    if (nameKey) map.set(nameKey, item);
    if (slugKey) map.set(slugKey, item);
  }
  return map;
}

function findEventCatalogItem(eventos, value) {
  const key = normalizeEventKey(value);
  const slug = slugifyEvent(value);
  const map = buildEventCatalogMap(eventos);
  return map.get(key) || map.get(slug) || null;
}

function resolveCatalogEventActive(eventItem, statusMap) {
  const canonical = getCanonicalEventName(eventItem?.evento || eventItem?.nombre);
  if (statusMap instanceof Map && statusMap.has(canonical)) {
    return statusMap.get(canonical) !== false;
  }

  return eventItem?.activo !== false;
}

function resolveCatalogCertificateActive(eventItem, certificateMap) {
  const canonical = getCanonicalEventName(eventItem?.evento || eventItem?.nombre);
  if (certificateMap instanceof Map && certificateMap.has(canonical)) {
    return certificateMap.get(canonical) !== false;
  }

  return eventItem?.certificado_activo !== false;
}

async function fetchEventCatalog({ supabaseUrl, serviceRoleKey }) {
  const result = {
    eventos: getDefaultEventCatalog(),
    map: buildEventCatalogMap(getDefaultEventCatalog()),
    available: false,
    warning: ""
  };

  if (!supabaseUrl || !serviceRoleKey) {
    result.warning = "Sin credenciales de Supabase para consultar el catalogo de encuentros.";
    return result;
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_CATALOG_TABLE));
  endpoint.searchParams.set("select", EVENT_CATALOG_SELECT);
  endpoint.searchParams.set("order", "numero.asc.nullslast,nombre.asc");

  const rowsResult = await fetchSupabaseRows({
    endpoint,
    headers: buildSupabaseHeaders(serviceRoleKey)
  });

  if (!rowsResult.ok) {
    const detail = rowsResult.detail || "";

    if (isMissingCatalogTable(rowsResult.status, detail)) {
      result.warning =
        "Tabla de catalogo de encuentros no disponible. Se usan los encuentros definidos en codigo.";
      return result;
    }

    result.warning =
      `No se pudo leer el catalogo de encuentros (${rowsResult.status}). ` +
      `${getErrorSummary(detail) || "Se usan los encuentros definidos en codigo."}`;
    return result;
  }

  result.eventos = mergeEventCatalog(rowsResult.rows);
  result.map = buildEventCatalogMap(result.eventos);
  result.available = true;
  return result;
}

async function upsertEventCatalog({ supabaseUrl, serviceRoleKey, payload }) {
  if (!supabaseUrl || !serviceRoleKey) {
    return {
      ok: false,
      error: "Faltan variables de entorno de Supabase.",
      tableMissing: false
    };
  }

  const normalized = normalizeEventCatalogRecord(payload || {});
  if (!normalized) {
    return {
      ok: false,
      error: "Tenes que indicar un nombre de encuentro valido.",
      tableMissing: false
    };
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_CATALOG_TABLE));
  endpoint.searchParams.set("on_conflict", "slug");

  const record = {
    nombre: normalized.evento,
    slug: normalized.slug,
    numero: normalized.numero,
    ciudad: normalized.ciudad || null,
    provincia: normalized.provincia || null,
    fecha: normalized.fecha || null,
    horario: normalized.horario || null,
    lugar: normalized.lugar || null,
    direccion: normalized.direccion || null,
    maps_url: normalized.maps_url || null,
    imagen_url: normalized.imagen_url || null,
    whatsapp_url: normalized.whatsapp_url || null,
    descripcion: normalized.descripcion || null,
    ingreso_solidario: normalized.ingreso_solidario || null,
    cupo: normalized.cupo,
    activo: normalized.activo !== false,
    certificado_activo: normalized.certificado_activo !== false,
    mail_opcional: normalized.mail_opcional === true,
    asociado_opcional: normalized.asociado_opcional === true,
    celular_obligatorio: normalized.celular_obligatorio === true,
    updated_at: new Date().toISOString()
  };

  const response = await fetch(endpoint.toString(), {
    method: "POST",
    headers: buildSupabaseHeaders(serviceRoleKey, {
      "Content-Type": "application/json",
      Prefer: "resolution=merge-duplicates,return=representation"
    }),
    body: JSON.stringify([record])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar el encuentro (${response.status}). ${getErrorSummary(detail)}`,
      tableMissing: isMissingCatalogTable(response.status, detail),
      detail: parseJsonSafely(detail, detail)
    };
  }

  const rows = await response.json().catch(() => []);
  const saved = normalizeEventCatalogRecord(
    Array.isArray(rows) && rows[0] ? rows[0] : record
  );

  return {
    ok: true,
    evento: saved
  };
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
  endpoint.searchParams.set("select", "encuentro,activo,updated_at");
  endpoint.searchParams.set("order", "encuentro.asc");

  const rowsResult = await fetchSupabaseRows({
    endpoint,
    headers: buildSupabaseHeaders(serviceRoleKey)
  });

  if (!rowsResult.ok) {
    const detail = rowsResult.detail || "";

    if (isMissingStatusTable(rowsResult.status, detail)) {
      result.warning =
        "Tabla de estado de encuentros no disponible. Todos los encuentros quedan activos por defecto.";
      return result;
    }

    result.warning =
      `No se pudo leer el estado de encuentros (${rowsResult.status}). ` +
      `${getErrorSummary(detail) || "Todo queda activo por defecto."}`;
    return result;
  }

  const rows = rowsResult.rows;
  const resolvedByEvent = new Map();

  for (const row of Array.isArray(rows) ? rows : []) {
    const rawKey = String(row.encuentro || "");
    if (
      rawKey.startsWith(ATTENDANCE_KEY_PREFIX) ||
      rawKey.startsWith(LUNCH_KEY_PREFIX) ||
      rawKey.startsWith(RECONFIRM_KEY_PREFIX) ||
      rawKey.startsWith(RAFFLE_KEY_PREFIX) ||
      rawKey.startsWith(RAFFLE_BRAND_KEY_PREFIX) ||
      rawKey.startsWith(CERTIFICATE_KEY_PREFIX)
    ) {
      continue;
    }

    const eventName = getCanonicalEventName(row.encuentro);
    const exactCanonicalMatch = cleanText(rawKey, 80) === eventName;
    const timestamp = Date.parse(String(row.updated_at || ""));
    const nextEntry = {
      active: row.activo !== false,
      priority: exactCanonicalMatch ? 2 : 1,
      timestamp: Number.isFinite(timestamp) ? timestamp : 0
    };
    const previousEntry = resolvedByEvent.get(eventName);

    if (
      !previousEntry ||
      nextEntry.priority > previousEntry.priority ||
      (
        nextEntry.priority === previousEntry.priority &&
        nextEntry.timestamp >= previousEntry.timestamp
      )
    ) {
      resolvedByEvent.set(eventName, nextEntry);
    }
  }

  for (const [eventName, state] of resolvedByEvent.entries()) {
    result.map.set(eventName, state.active);
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

function buildCertificateStorageKey(eventName) {
  const canonicalEvent = getCanonicalEventName(eventName);
  if (!canonicalEvent || canonicalEvent === "Sin evento") {
    return "";
  }

  return `${CERTIFICATE_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}`;
}

function parseCertificateStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(CERTIFICATE_KEY_PREFIX)) {
    return null;
  }

  const encodedEvent = raw.slice(CERTIFICATE_KEY_PREFIX.length);
  if (!encodedEvent) {
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

  return canonicalEvent;
}

async function fetchCertificateStatusMap({ supabaseUrl, serviceRoleKey }) {
  const result = {
    map: new Map(),
    available: false,
    warning: ""
  };

  if (!supabaseUrl || !serviceRoleKey) {
    result.warning = "Sin credenciales de Supabase para consultar certificados.";
    return result;
  }

  const endpoint = new URL(buildSupabaseEndpoint(supabaseUrl, EVENT_STATUS_TABLE));
  endpoint.searchParams.set("select", "encuentro,activo");
  endpoint.searchParams.set("order", "encuentro.asc");

  const rowsResult = await fetchSupabaseRows({
    endpoint,
    headers: buildSupabaseHeaders(serviceRoleKey)
  });

  if (!rowsResult.ok) {
    const detail = rowsResult.detail || "";

    if (isMissingStatusTable(rowsResult.status, detail)) {
      result.warning =
        "Tabla de estado de encuentros no disponible. Los certificados quedan visibles por defecto.";
      return result;
    }

    result.warning =
      `No se pudo leer el estado de certificados (${rowsResult.status}). ` +
      `${getErrorSummary(detail) || "Quedan visibles por defecto."}`;
    return result;
  }

  const rows = rowsResult.rows;
  for (const row of Array.isArray(rows) ? rows : []) {
    const eventName = parseCertificateStorageKey(row.encuentro);
    if (!eventName) continue;
    result.map.set(eventName, row.activo !== false);
  }

  result.available = true;
  return result;
}

function resolveCertificateActive({ eventName, certificateMap }) {
  if (!(certificateMap instanceof Map)) {
    return true;
  }

  const canonical = getCanonicalEventName(eventName);
  if (!certificateMap.has(canonical)) {
    return true;
  }

  return certificateMap.get(canonical) !== false;
}

async function upsertCertificateStatus({ supabaseUrl, serviceRoleKey, encuentro, activo }) {
  const storageKey = buildCertificateStorageKey(encuentro);

  if (!supabaseUrl || !serviceRoleKey) {
    return {
      ok: false,
      error: "Faltan variables de entorno de Supabase.",
      tableMissing: false
    };
  }

  if (!storageKey) {
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
        encuentro: storageKey,
        activo: activo !== false
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar el estado del certificado (${response.status}). ${getErrorSummary(detail)}`,
      tableMissing: isMissingStatusTable(response.status, detail),
      detail: parseJsonSafely(detail, detail)
    };
  }

  const rows = await response.json().catch(() => []);
  const saved = Array.isArray(rows) && rows[0] ? rows[0] : null;

  return {
    ok: true,
    eventName: parseCertificateStorageKey(saved?.encuentro) || getCanonicalEventName(encuentro),
    active: saved?.activo !== false
  };
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
  EVENT_CATALOG_TABLE_SQL,
  CERTIFICATE_KEY_PREFIX,
  cleanText,
  buildEventRegistrationUrl,
  fetchEventCatalog,
  findEventCatalogItem,
  getCanonicalEventName,
  getDefaultEventCatalog,
  normalizeEventKey,
  resolveCatalogEventActive,
  resolveCatalogCertificateActive,
  fetchEventStatusMap,
  fetchCertificateStatusMap,
  resolveEventActive,
  resolveCertificateActive,
  getEventStatus,
  upsertEventStatus,
  upsertEventCatalog,
  upsertCertificateStatus
};
