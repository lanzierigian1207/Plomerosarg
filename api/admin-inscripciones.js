const crypto = require("crypto");

const { fetchSupabaseRows } = require("./_supabase-pagination");
const {
  KNOWN_EVENTS,
  cleanText,
  getCanonicalEventName,
  fetchEventStatusMap,
  fetchCertificateStatusMap,
  resolveEventActive,
  resolveCertificateActive,
  upsertEventStatus,
  upsertCertificateStatus
} = require("./_encuentros");

const COOKIE_NAME = "admin_session";
const ROLE_ADMIN = "admin";
const ROLE_ASISTENCIA = "asistencia";
const STATUS_TABLE = "encuentros_estado";
const RESEND_ENDPOINT = "https://api.resend.com/emails";
const ATTENDANCE_KEY_PREFIX = "__attendance__::";
const LUNCH_KEY_PREFIX = "__lunch__::";
const RECONFIRM_KEY_PREFIX = "__reconfirm__::";
const RAFFLE_KEY_PREFIX = "__raffle__::";
const RAFFLE_BRAND_KEY_PREFIX = "__rafflebrand__::";
const BULK_MAIL_CONCURRENCY = 4;
const BULK_MAIL_MAX_MESSAGE_LENGTH = 6000;
const INSCRIPCIONES_SELECT_BASE =
  "id,encuentro,dni,nombre_apellido,mail,provincia,localidad,asociado,profesion,origen,acepto_terminos,created_at";
const INSCRIPCIONES_SELECT_WITH_EXPOSITOR_INFO =
  `${INSCRIPCIONES_SELECT_BASE},expositor_info`;

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

function normalizeBrandKey(value) {
  return String(value ?? "")
    .trim()
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "_")
    .replace(/^_+|_+$/g, "");
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

function buildLunchMapKey(eventName, dni) {
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

function buildLunchStorageKey(eventName, dni) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return "";
  }
  return `${LUNCH_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}`;
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

function parseLunchStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(LUNCH_KEY_PREFIX)) {
    return null;
  }

  const tail = raw.slice(LUNCH_KEY_PREFIX.length);
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

function buildRaffleStorageKey(eventName, dni) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return "";
  }
  return `${RAFFLE_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}`;
}

function parseRaffleStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(RAFFLE_KEY_PREFIX)) {
    return null;
  }

  const tail = raw.slice(RAFFLE_KEY_PREFIX.length);
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

function buildRaffleBrandStorageKey(eventName, dni, brandKey) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  const normalizedBrandKey = normalizeBrandKey(brandKey);
  if (
    !canonicalEvent ||
    canonicalEvent === "Sin evento" ||
    !normalizedDni ||
    !normalizedBrandKey
  ) {
    return "";
  }

  return `${RAFFLE_BRAND_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}::${normalizedBrandKey}`;
}

function parseRaffleBrandStorageKey(storageKey) {
  const raw = String(storageKey ?? "");
  if (!raw.startsWith(RAFFLE_BRAND_KEY_PREFIX)) {
    return null;
  }

  const tail = raw.slice(RAFFLE_BRAND_KEY_PREFIX.length);
  const parts = tail.split("::");
  if (parts.length !== 3) {
    return null;
  }

  const [encodedEvent, rawDni, rawBrandKey] = parts;
  const normalizedDni = normalizeDniValue(rawDni);
  const normalizedBrandKey = normalizeBrandKey(rawBrandKey);
  if (!normalizedDni || !normalizedBrandKey) {
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
    dni: normalizedDni,
    brandKey: normalizedBrandKey
  };
}

async function fetchAttendanceAndFlagStateMaps({ supabaseUrl, serviceRoleKey }) {
  const attendanceMap = new Map();
  const lunchMap = new Map();
  const reconfirmMap = new Map();
  const raffleMap = new Map();
  const raffleBrandMap = new Map();

  if (!supabaseUrl || !serviceRoleKey) {
    return { attendanceMap, lunchMap, reconfirmMap, raffleMap, raffleBrandMap };
  }

  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/${STATUS_TABLE}`
  );
  endpoint.searchParams.set("select", "encuentro,activo");
  endpoint.searchParams.set("order", "encuentro.asc");

  const rowsResult = await fetchSupabaseRows({
    endpoint,
    headers: {
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`
    }
  });

  if (!rowsResult.ok) {
    return { attendanceMap, lunchMap, reconfirmMap, raffleMap, raffleBrandMap };
  }

  const rows = rowsResult.rows;
  for (const row of Array.isArray(rows) ? rows : []) {
    const parsedAttendance = parseAttendanceStorageKey(row.encuentro);
    if (parsedAttendance) {
      const attendanceKey = buildAttendanceMapKey(parsedAttendance.eventName, parsedAttendance.dni);
      if (attendanceKey) {
        attendanceMap.set(attendanceKey, row.activo !== false);
      }
    }

    const parsedLunch = parseLunchStorageKey(row.encuentro);
    if (parsedLunch) {
      const lunchKey = buildLunchMapKey(parsedLunch.eventName, parsedLunch.dni);
      if (lunchKey) {
        lunchMap.set(lunchKey, row.activo !== false);
      }
    }

    const parsedReconfirm = parseReconfirmStorageKey(row.encuentro);
    if (parsedReconfirm) {
      const reconfirmKey = buildInscriptoMapKey(parsedReconfirm.eventName, parsedReconfirm.dni);
      if (reconfirmKey) {
        reconfirmMap.set(reconfirmKey, row.activo !== false);
      }
    }

    const parsedRaffle = parseRaffleStorageKey(row.encuentro);
    if (parsedRaffle) {
      const raffleKey = buildInscriptoMapKey(parsedRaffle.eventName, parsedRaffle.dni);
      if (raffleKey) {
        raffleMap.set(raffleKey, row.activo !== false);
      }
    }

    const parsedRaffleBrand = row.activo !== false
      ? parseRaffleBrandStorageKey(row.encuentro)
      : null;
    if (parsedRaffleBrand) {
      const raffleBrandKey = buildInscriptoMapKey(
        parsedRaffleBrand.eventName,
        parsedRaffleBrand.dni
      );
      if (raffleBrandKey) {
        raffleBrandMap.set(raffleBrandKey, parsedRaffleBrand.brandKey);
      }
    }
  }

  return { attendanceMap, lunchMap, reconfirmMap, raffleMap, raffleBrandMap };
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

async function upsertLunchState({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  dni,
  almuerzo
}) {
  const storageKey = buildLunchStorageKey(eventName, dni);
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
        activo: almuerzo !== false
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar el almuerzo (${response.status}).`,
      detail,
      tableMissing: detail.toLowerCase().includes("does not exist")
    };
  }

  return {
    ok: true
  };
}

async function upsertRaffleState({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  dni,
  sorteado
}) {
  const storageKey = buildRaffleStorageKey(eventName, dni);
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
        activo: sorteado !== false
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar el resultado del sorteo (${response.status}).`,
      detail,
      tableMissing: detail.toLowerCase().includes("does not exist")
    };
  }

  return {
    ok: true
  };
}

async function upsertRaffleBrandState({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  dni,
  brandKey
}) {
  const storageKey = buildRaffleBrandStorageKey(eventName, dni, brandKey);
  if (!storageKey) {
    return {
      ok: false,
      error: "Encuentro, DNI o marca invalida.",
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
        activo: true
      }
    ])
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar la marca del sorteo (${response.status}).`,
      detail,
      tableMissing: detail.toLowerCase().includes("does not exist")
    };
  }

  return {
    ok: true
  };
}

async function deactivateRaffleBrandStates({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  dni
}) {
  const canonicalEvent = getCanonicalEventName(eventName);
  const normalizedDni = normalizeDniValue(dni);
  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return {
      ok: false,
      error: "Encuentro o DNI invalido.",
      tableMissing: false
    };
  }

  const storagePrefix =
    `${RAFFLE_BRAND_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}::`;
  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/${STATUS_TABLE}`
  );
  endpoint.searchParams.set("encuentro", `like.${storagePrefix}*`);

  const response = await fetch(endpoint.toString(), {
    method: "PATCH",
    headers: {
      "Content-Type": "application/json",
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`,
      Prefer: "return=representation"
    },
    body: JSON.stringify({
      activo: false
    })
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo limpiar la marca del sorteo (${response.status}).`,
      detail,
      tableMissing: detail.toLowerCase().includes("does not exist")
    };
  }

  const rows = await response.json().catch(() => []);

  return {
    ok: true,
    count: Array.isArray(rows) ? rows.length : 0
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

function isMissingExpositorInfoColumnError(detail) {
  const normalized = String(detail ?? "").toLowerCase();
  return (
    normalized.includes("expositor_info") &&
    (
      normalized.includes("does not exist") ||
      normalized.includes("schema cache") ||
      normalized.includes("column")
    )
  );
}

async function fetchInscripcionesRows({ endpoint, serviceRoleKey }) {
  const headers = {
    apikey: serviceRoleKey,
    Authorization: `Bearer ${serviceRoleKey}`
  };

  const result = await fetchSupabaseRows({
    endpoint,
    headers,
    select: INSCRIPCIONES_SELECT_WITH_EXPOSITOR_INFO
  });

  if (!result.ok) {
    if (isMissingExpositorInfoColumnError(result.detail)) {
      const fallbackResult = await fetchSupabaseRows({
        endpoint,
        headers,
        select: INSCRIPCIONES_SELECT_BASE
      });

      return fallbackResult.ok
        ? { ok: true, rows: fallbackResult.rows }
        : { ok: false, detail: fallbackResult.detail || result.detail };
    }

    return {
      ok: false,
      detail: result.detail
    };
  }

  return {
    ok: true,
    rows: result.rows
  };
}

async function fetchEventInscripcionesRows({ supabaseUrl, serviceRoleKey, eventName }) {
  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`
  );
  endpoint.searchParams.set(
    "select",
    "id,encuentro,dni,nombre_apellido,mail,provincia,localidad,asociado,profesion,created_at"
  );
  endpoint.searchParams.set("encuentro", `eq.${eventName}`);
  endpoint.searchParams.set("order", "id.asc");

  const result = await fetchSupabaseRows({
    endpoint,
    headers: {
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`
    }
  });

  if (!result.ok) {
    return {
      ok: false,
      detail: result.detail
    };
  }

  return {
    ok: true,
    rows: result.rows
  };
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(email || ""));
}

function normalizeEmail(value) {
  return cleanText(value, 120).toLowerCase();
}

function normalizeRecipientSelection(value) {
  if (!Array.isArray(value)) {
    return null;
  }

  const selected = new Set();
  for (const item of value) {
    const rawMail =
      typeof item === "string"
        ? item
        : item && typeof item === "object"
          ? item.mail || item.email
          : "";
    const mail = normalizeEmail(rawMail);
    if (mail && isValidEmail(mail)) {
      selected.add(mail);
    }
  }

  return selected;
}

function escapeHtml(text) {
  return String(text ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function formatMailMessageHtml(message) {
  const blocks = String(message || "")
    .split(/\n{2,}/)
    .map((block) => block.trim())
    .filter(Boolean);

  if (blocks.length === 0) {
    return "";
  }

  return blocks
    .map((block) => {
      const html = escapeHtml(block).replace(/\n/g, "<br />");
      return `<p style="margin:0 0 12px;">${html}</p>`;
    })
    .join("");
}

function getUniqueMailRecipients(rows, selectedMailSet = null) {
  const recipients = [];
  const seen = new Set();
  let duplicates = 0;
  let withoutMail = 0;
  let invalidMail = 0;
  let notSelected = 0;

  for (const row of Array.isArray(rows) ? rows : []) {
    const mail = normalizeEmail(row?.mail);
    if (!mail) {
      withoutMail += 1;
      continue;
    }

    if (!isValidEmail(mail)) {
      invalidMail += 1;
      continue;
    }

    if (selectedMailSet instanceof Set && !selectedMailSet.has(mail)) {
      notSelected += 1;
      continue;
    }

    if (seen.has(mail)) {
      duplicates += 1;
      continue;
    }

    seen.add(mail);
    recipients.push({
      mail,
      nombre: cleanText(row?.nombre_apellido, 120),
      dni: cleanText(row?.dni, 20)
    });
  }

  return {
    recipients,
    duplicates,
    withoutMail,
    invalidMail,
    notSelected
  };
}

function buildBulkEventEmailPayload({
  recipient,
  eventName,
  subject,
  message,
  mailFrom,
  replyTo
}) {
  const safeNombre = escapeHtml(recipient?.nombre || "participante");
  const safeEventName = escapeHtml(eventName || "encuentro");
  const messageHtml = formatMailMessageHtml(message);
  const text = [
    `Hola ${recipient?.nombre || "participante"}`,
    "",
    message,
    "",
    "Saludos, Equipo de Plomeros y Sanitaristas"
  ].join("\n");

  const html = `
    <div style="font-family:Arial,Helvetica,sans-serif;line-height:1.55;color:#10263f">
      <h2 style="margin:0 0 12px;">${escapeHtml(subject)}</h2>
      <p style="margin:0 0 10px;">Hola ${safeNombre}</p>
      ${messageHtml}
      <p style="margin:0 0 12px;color:#5b6b80;font-size:13px;">
        Encuentro: <strong>${safeEventName}</strong>
      </p>
      <p style="margin:0;">Saludos, Equipo de Plomeros y Sanitaristas</p>
    </div>
  `;

  const payload = {
    from: mailFrom,
    to: [recipient.mail],
    subject,
    html,
    text
  };

  if (replyTo) {
    payload.reply_to = replyTo;
  }

  return payload;
}

async function sendResendEmail({ resendApiKey, payload }) {
  try {
    const response = await fetch(RESEND_ENDPOINT, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${resendApiKey}`
      },
      body: JSON.stringify(payload)
    });

    if (!response.ok) {
      const detail = await response.text().catch(() => "");
      return {
        ok: false,
        status: response.status,
        detail: detail.slice(0, 500)
      };
    }

    return { ok: true };
  } catch (error) {
    return {
      ok: false,
      status: 0,
      detail: error instanceof Error ? error.message : "Error desconocido"
    };
  }
}

async function sendBulkEventEmail({
  supabaseUrl,
  serviceRoleKey,
  eventName,
  subject,
  message,
  selectedMails
}) {
  const resendApiKey = String(process.env.RESEND_API_KEY || "").trim();
  const mailFrom = String(process.env.MAIL_FROM || "").trim();
  const replyTo = String(process.env.MAIL_REPLY_TO || "").trim();
  const selectedMailSet = normalizeRecipientSelection(selectedMails);
  const hasSelection = Array.isArray(selectedMails);

  if (!resendApiKey || !mailFrom) {
    return {
      ok: false,
      status: 500,
      error: "Faltan RESEND_API_KEY o MAIL_FROM para enviar mails."
    };
  }

  const inscripcionesResult = await fetchEventInscripcionesRows({
    supabaseUrl,
    serviceRoleKey,
    eventName
  });

  if (!inscripcionesResult.ok) {
    return {
      ok: false,
      status: 500,
      error: "No se pudieron consultar las inscripciones del encuentro.",
      detail: inscripcionesResult.detail || ""
    };
  }

  if (hasSelection && selectedMailSet.size === 0) {
    return {
      ok: false,
      status: 422,
      error: "Selecciona al menos un mail valido.",
      total_inscripciones: Array.isArray(inscripcionesResult.rows)
        ? inscripcionesResult.rows.length
        : 0,
      destinatarios: 0,
      duplicados: 0,
      sin_mail: 0,
      mails_invalidos: 0,
      no_seleccionados: 0
    };
  }

  const recipientState = getUniqueMailRecipients(inscripcionesResult.rows, selectedMailSet);
  const recipients = recipientState.recipients;

  if (recipients.length === 0) {
    return {
      ok: false,
      status: 409,
      error: hasSelection
        ? "No hay mails seleccionados que correspondan a este encuentro."
        : "No hay mails validos para este encuentro.",
      total_inscripciones: Array.isArray(inscripcionesResult.rows)
        ? inscripcionesResult.rows.length
        : 0,
      destinatarios: 0,
      duplicados: recipientState.duplicates,
      sin_mail: recipientState.withoutMail,
      mails_invalidos: recipientState.invalidMail,
      no_seleccionados: recipientState.notSelected
    };
  }

  let nextIndex = 0;
  let sent = 0;
  const failed = [];
  const workersCount = Math.min(BULK_MAIL_CONCURRENCY, recipients.length);

  async function worker() {
    while (nextIndex < recipients.length) {
      const recipientIndex = nextIndex;
      nextIndex += 1;
      const recipient = recipients[recipientIndex];
      const payload = buildBulkEventEmailPayload({
        recipient,
        eventName,
        subject,
        message,
        mailFrom,
        replyTo
      });
      const result = await sendResendEmail({ resendApiKey, payload });

      if (result.ok) {
        sent += 1;
      } else {
        failed.push({
          mail: recipient.mail,
          nombre: recipient.nombre,
          status: result.status,
          detail: result.detail
        });
      }
    }
  }

  await Promise.all(
    Array.from({ length: workersCount }, () => worker())
  );

  const allFailed = sent === 0 && failed.length > 0;

  return {
    ok: !allFailed,
    status: allFailed ? 502 : 200,
    error: allFailed ? "No se pudo enviar ningun mail." : "",
    total_inscripciones: Array.isArray(inscripcionesResult.rows)
      ? inscripcionesResult.rows.length
      : 0,
    destinatarios: recipients.length,
    enviados: sent,
    fallidos: failed.length,
    fallidos_detalle: failed.slice(0, 25),
    duplicados: recipientState.duplicates,
    sin_mail: recipientState.withoutMail,
    mails_invalidos: recipientState.invalidMail,
    no_seleccionados: recipientState.notSelected
  };
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

function dedupeRowsByDni(rows) {
  const list = Array.isArray(rows) ? rows : [];
  const uniqueRows = [];
  const seen = new Set();

  for (const row of list) {
    const normalizedDni = normalizeDniValue(row?.dni);
    if (!normalizedDni || seen.has(normalizedDni)) {
      continue;
    }

    seen.add(normalizedDni);
    uniqueRows.push(row);
  }

  return uniqueRows;
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

  const endpoint = new URL(
    `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`
  );
  endpoint.searchParams.set("order", "id.asc");

  try {
    const [statusResult, certificateStatusResult, flagsState] = await Promise.all([
      fetchEventStatusMap({ supabaseUrl, serviceRoleKey }),
      fetchCertificateStatusMap({ supabaseUrl, serviceRoleKey }),
      fetchAttendanceAndFlagStateMaps({ supabaseUrl, serviceRoleKey })
    ]);
    const inscripcionesResult = await fetchInscripcionesRows({
      endpoint,
      serviceRoleKey
    });
    const attendanceMap = flagsState?.attendanceMap instanceof Map
      ? flagsState.attendanceMap
      : new Map();
    const lunchMap = flagsState?.lunchMap instanceof Map
      ? flagsState.lunchMap
      : new Map();
    const reconfirmMap = flagsState?.reconfirmMap instanceof Map
      ? flagsState.reconfirmMap
      : new Map();
    const raffleMap = flagsState?.raffleMap instanceof Map
      ? flagsState.raffleMap
      : new Map();
    const raffleBrandMap = flagsState?.raffleBrandMap instanceof Map
      ? flagsState.raffleBrandMap
      : new Map();

    if (!inscripcionesResult.ok) {
      return res.status(500).json({
        ok: false,
        error: "No se pudo consultar la base de datos.",
        detail: inscripcionesResult.detail || ""
      });
    }

    const rows = inscripcionesResult.rows;
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
        expositor_info: cleanText(row.expositor_info, 200),
        origen: cleanText(row.origen, 40),
        created_at: row.created_at || null,
        asistio:
          attendanceMap.get(buildAttendanceMapKey(eventName, row.dni)) === true,
        almuerzo:
          lunchMap.get(buildLunchMapKey(eventName, row.dni)) === true,
        reconfirmado:
          reconfirmMap.get(buildInscriptoMapKey(eventName, row.dni)) === true,
        sorteado:
          raffleMap.get(buildInscriptoMapKey(eventName, row.dni)) === true,
        marca_sorteo:
          raffleBrandMap.get(buildInscriptoMapKey(eventName, row.dni)) || ""
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
        certificado_activo: resolveCertificateActive({
          eventName,
          certificateMap: certificateStatusResult.map
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
            almuerzo: row.almuerzo === true,
            reconfirmado: row.reconfirmado === true,
            sorteado: row.sorteado === true,
            marca_sorteo: row.marca_sorteo || ""
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
      certificate_status_available: certificateStatusResult.available,
      certificate_status_warning: certificateStatusResult.warning || "",
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

  if (adminRole !== ROLE_ADMIN && action !== "set_lunch") {
    return res.status(403).json({
      ok: false,
      error: "No autorizado para modificar el estado de encuentros."
    });
  }

  if (action === "send_event_email") {
    const eventInput = cleanText(payload.evento, 80);
    const canonicalEvent = getCanonicalEventName(eventInput);
    const subject = cleanText(payload.asunto || payload.subject, 160);
    const message = cleanText(payload.mensaje || payload.message, BULK_MAIL_MAX_MESSAGE_LENGTH);

    if (!canonicalEvent || canonicalEvent === "Sin evento") {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar un encuentro valido."
      });
    }

    if (subject.length < 4) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que escribir un asunto."
      });
    }

    if (message.length < 10) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que escribir un mensaje."
      });
    }

    const mailResult = await sendBulkEventEmail({
      supabaseUrl,
      serviceRoleKey,
      eventName: canonicalEvent,
      subject,
      message,
      selectedMails: payload.destinatarios || payload.mails || payload.emails
    });

    return res.status(mailResult.status || (mailResult.ok ? 200 : 500)).json({
      ok: mailResult.ok,
      action: "send_event_email",
      evento: canonicalEvent,
      error: mailResult.error || "",
      detail: mailResult.detail || "",
      total_inscripciones: mailResult.total_inscripciones || 0,
      destinatarios: mailResult.destinatarios || 0,
      enviados: mailResult.enviados || 0,
      fallidos: mailResult.fallidos || 0,
      fallidos_detalle: mailResult.fallidos_detalle || [],
      duplicados: mailResult.duplicados || 0,
      sin_mail: mailResult.sin_mail || 0,
      mails_invalidos: mailResult.mails_invalidos || 0,
      no_seleccionados: mailResult.no_seleccionados || 0,
      updated_at: new Date().toISOString()
    });
  }

  if (action === "set_lunch") {
    const eventInput = cleanText(payload.evento, 80);
    const canonicalEvent = getCanonicalEventName(eventInput);
    const dni = normalizeDniValue(payload.dni);
    const almuerzo = parseBooleanInput(payload.almuerzo);

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

    if (almuerzo === null) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar almuerzo true/false."
      });
    }

    const savedLunch = await upsertLunchState({
      supabaseUrl,
      serviceRoleKey,
      eventName: canonicalEvent,
      dni,
      almuerzo
    });

    if (!savedLunch.ok) {
      return res.status(500).json({
        ok: false,
        error: savedLunch.error || "No se pudo guardar el almuerzo.",
        detail: savedLunch.detail || ""
      });
    }

    return res.status(200).json({
      ok: true,
      action: "set_lunch",
      evento: canonicalEvent,
      dni,
      almuerzo: almuerzo === true,
      updated_at: new Date().toISOString()
    });
  }

  if (action === "set_certificado_visible") {
    const eventInput = cleanText(payload.evento, 80);
    const certificadoVisible = parseBooleanInput(payload.certificado_activo);

    if (!eventInput) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar un encuentro."
      });
    }

    if (certificadoVisible === null) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar certificado_activo true/false."
      });
    }

    const savedCertificate = await upsertCertificateStatus({
      supabaseUrl,
      serviceRoleKey,
      encuentro: eventInput,
      activo: certificadoVisible
    });

    if (!savedCertificate.ok) {
      if (savedCertificate.tableMissing) {
        return res.status(500).json({
          ok: false,
          error:
            "No existe la tabla de estados de encuentros. Creala una sola vez en Supabase y volve a intentar.",
          sql: STATUS_TABLE_SQL
        });
      }

      return res.status(500).json({
        ok: false,
        error:
          savedCertificate.error || "No se pudo guardar la visibilidad del certificado."
      });
    }

    return res.status(200).json({
      ok: true,
      action: "set_certificado_visible",
      evento: savedCertificate.eventName,
      certificado_activo: savedCertificate.active,
      updated_at: new Date().toISOString()
    });
  }

  if (action === "reset_raffle_winner") {
    const eventInput = cleanText(payload.evento, 80);
    const canonicalEvent = getCanonicalEventName(eventInput);
    const dni = normalizeDniValue(payload.dni);

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

    const savedRaffle = await upsertRaffleState({
      supabaseUrl,
      serviceRoleKey,
      eventName: canonicalEvent,
      dni,
      sorteado: false
    });

    if (!savedRaffle.ok) {
      return res.status(500).json({
        ok: false,
        error: savedRaffle.error || "No se pudo actualizar el estado del sorteo.",
        detail: savedRaffle.detail || ""
      });
    }

    const cleanedBrand = await deactivateRaffleBrandStates({
      supabaseUrl,
      serviceRoleKey,
      eventName: canonicalEvent,
      dni
    });

    if (!cleanedBrand.ok) {
      return res.status(500).json({
        ok: false,
        error: cleanedBrand.error || "No se pudo limpiar la marca del sorteo.",
        detail: cleanedBrand.detail || ""
      });
    }

    return res.status(200).json({
      ok: true,
      action: "reset_raffle_winner",
      evento: canonicalEvent,
      dni,
      sorteado: false,
      marca_sorteo: "",
      marcas_limpiadas: cleanedBrand.count || 0,
      updated_at: new Date().toISOString()
    });
  }

  if (action === "draw_raffle") {
    const eventInput = cleanText(payload.evento, 80);
    const canonicalEvent = getCanonicalEventName(eventInput);
    const requestedCount = Number.parseInt(String(payload.cantidad ?? "1"), 10);
    const raffleBrandKey = normalizeBrandKey(payload.marca);

    if (!canonicalEvent || canonicalEvent === "Sin evento") {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar un encuentro valido."
      });
    }

    if (!Number.isFinite(requestedCount) || requestedCount < 1) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que indicar una cantidad valida para el sorteo."
      });
    }

    if (!raffleBrandKey) {
      return res.status(422).json({
        ok: false,
        error: "Tenes que seleccionar una marca para el sorteo."
      });
    }

    const [inscripcionesResult, flagsState] = await Promise.all([
      fetchEventInscripcionesRows({
        supabaseUrl,
        serviceRoleKey,
        eventName: canonicalEvent
      }),
      fetchAttendanceAndFlagStateMaps({ supabaseUrl, serviceRoleKey })
    ]);

    if (!inscripcionesResult.ok) {
      return res.status(500).json({
        ok: false,
        error: "No se pudieron consultar las inscripciones del encuentro.",
        detail: inscripcionesResult.detail || ""
      });
    }

    const attendanceMap = flagsState?.attendanceMap instanceof Map
      ? flagsState.attendanceMap
      : new Map();
    const raffleMap = flagsState?.raffleMap instanceof Map
      ? flagsState.raffleMap
      : new Map();
    const raffleBrandMap = flagsState?.raffleBrandMap instanceof Map
      ? flagsState.raffleBrandMap
      : new Map();

    const normalizedRows = (Array.isArray(inscripcionesResult.rows) ? inscripcionesResult.rows : [])
      .map((row) => {
        const dni = cleanText(row.dni, 20);
        const winnerKey = buildInscriptoMapKey(canonicalEvent, dni);

        return {
          id: row.id ?? null,
          dni,
          nombre_apellido: cleanText(row.nombre_apellido, 120),
          mail: cleanText(row.mail, 120),
          provincia: cleanText(row.provincia, 40),
          localidad: cleanText(row.localidad, 120),
          profesion: formatProfesion(row.profesion),
          created_at: row.created_at || null,
          asistio: attendanceMap.get(buildAttendanceMapKey(canonicalEvent, dni)) === true,
          sorteado: raffleMap.get(winnerKey) === true,
          marca_sorteo: raffleBrandMap.get(winnerKey) || ""
        };
      })
      .sort(compareRegistrationOrder);

    const uniqueRows = dedupeRowsByDni(normalizedRows);
    const sorteados = uniqueRows.filter((row) => row.sorteado === true);
    const elegibles = uniqueRows.filter(
      (row) => row.asistio === true && row.sorteado !== true && normalizeDniValue(row.dni)
    );

    if (elegibles.length === 0) {
      return res.status(409).json({
        ok: false,
        error: "No hay participantes disponibles para sortear en este encuentro.",
        evento: canonicalEvent,
        elegibles: 0,
        sorteados: sorteados.length
      });
    }

    const cantidadSorteada = Math.min(requestedCount, elegibles.length);
    const disponibles = [...elegibles];
    const winners = [];

    for (let index = 0; index < cantidadSorteada; index += 1) {
      const winnerIndex = crypto.randomInt(disponibles.length);
      const winner = disponibles.splice(winnerIndex, 1)[0];
      if (!winner) continue;

      const savedRaffle = await upsertRaffleState({
        supabaseUrl,
        serviceRoleKey,
        eventName: canonicalEvent,
        dni: winner.dni,
        sorteado: true
      });

      if (!savedRaffle.ok) {
        return res.status(500).json({
          ok: false,
          error: savedRaffle.error || "No se pudo guardar el sorteo.",
          detail: savedRaffle.detail || ""
        });
      }

      const savedBrand = await upsertRaffleBrandState({
        supabaseUrl,
        serviceRoleKey,
        eventName: canonicalEvent,
        dni: winner.dni,
        brandKey: raffleBrandKey
      });

      if (!savedBrand.ok) {
        return res.status(500).json({
          ok: false,
          error: savedBrand.error || "No se pudo guardar la marca del sorteo.",
          detail: savedBrand.detail || ""
        });
      }

      winners.push({
        ...winner,
        marca_sorteo: raffleBrandKey
      });
    }

    if (winners.length === 0) {
      return res.status(500).json({
        ok: false,
        error: "No se pudieron generar ganadores para este sorteo."
      });
    }

    return res.status(200).json({
      ok: true,
      action: "draw_raffle",
      evento: canonicalEvent,
      ganador: {
        id: winners[0].id,
        dni: winners[0].dni,
        nombre_apellido: winners[0].nombre_apellido,
        mail: winners[0].mail,
        provincia: winners[0].provincia,
        localidad: winners[0].localidad,
        profesion: winners[0].profesion,
        created_at: winners[0].created_at,
        marca_sorteo: winners[0].marca_sorteo || raffleBrandKey
      },
      ganadores: winners.map((winner) => ({
        id: winner.id,
        dni: winner.dni,
        nombre_apellido: winner.nombre_apellido,
        mail: winner.mail,
        provincia: winner.provincia,
        localidad: winner.localidad,
        profesion: winner.profesion,
        created_at: winner.created_at,
        marca_sorteo: winner.marca_sorteo || raffleBrandKey
      })),
      cantidad_solicitada: requestedCount,
      cantidad_sorteada: winners.length,
      elegibles_restantes: Math.max(elegibles.length - winners.length, 0),
      sorteados_total: sorteados.length + winners.length,
      updated_at: new Date().toISOString()
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
