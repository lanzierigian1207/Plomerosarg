const ALLOWED_PROVINCIAS = new Set([
  "buenos_aires", "caba", "catamarca", "chaco", "chubut", "cordoba", "corrientes",
  "entre_rios", "formosa", "jujuy", "la_pampa", "la_rioja", "mendoza", "misiones",
  "neuquen", "rio_negro", "salta", "san_juan", "san_luis", "santa_cruz", "santa_fe",
  "santiago_del_estero", "tierra_del_fuego", "tucuman"
]);

const ALLOWED_ASOCIADO = new Set(["si", "no"]);

const ALLOWED_PROFESION = new Set([
  "plomero",
  "gasista",
  "oficial_plomero",
  "ayudante_plomero",
  "medio_oficial_plomero",
  "maestro_mayor_obra",
  "arquitecto_ingeniero",
  "estudiante_centro_formacion",
  "expositor",
  "otros"
]);

const ALLOWED_ORIGEN = new Set([
  "facebook",
  "grupo_whatsapp",
  "instagram",
  "casa_sanitarios",
  "centro_formacion"
]);

const { getCanonicalEventName, getEventStatus } = require("./_encuentros");
const DEFAULT_WHATSAPP_GROUP_URL =
  "https://wa.me/5491100000000?text=Hola%2C%20quiero%20sumarme%20al%20grupo%20del%20encuentro";
const MAIL_EVENT_EXTRAS = [
  {
    eventKey: "bahia blanca 11/3",
    locationAddress: "Cam. de la Carrindanga 3802, B8000 Bah\u00eda Blanca",
    locationLinkLabel: "Ve el lugar en google",
    whatsappRequiredNotice:
      "Para terminar la Inscripci\u00f3n es Obligatorio entrar al grupo de whatsapp",
    ingresoHorarioLabel: "Horario de Ingreso",
    ingresoHorario: "14hrs",
    hideBuenDiaLine: true,
    locationUrl: "https://share.google/viNow9oZuZHkSdo9C",
    imageUrl:
      "https://plomerosarg.com/Prueba_2/assets/WhatsApp%20Image%202026-02-23%20at%2010.13.50%20PM.jpeg"
  },
  {
    eventKey: "mar del plata 14/3",
    locationAddress: "Av. Pedro Luro 8851, B7606 Mar del Plata",
    locationLinkLabel: "Ve el lugar en google",
    whatsappRequiredNotice:
      "Para terminar la Inscripci\u00f3n es Obligatorio entrar al grupo de whatsapp",
    ingresoHorarioLabel: "Horario de Entrada",
    ingresoHorario: "8:30hrs",
    hideBuenDiaLine: true,
    locationUrl: "https://share.google/KA62Zn0H6wtiLd0La",
    imageUrl:
      "https://plomerosarg.com/Prueba_2/assets/WhatsApp%20Image%202026-02-23%20at%2010.13.49%20PM.jpeg"
  }
];
const WHATSAPP_GROUP_MATCHERS = [
  {
    key: "bahia blanca",
    url: "https://chat.whatsapp.com/BS5tb0BVbZfAKYibMz2Yh3?mode=gi_t"
  },
  {
    key: "mar del plata",
    url: "https://chat.whatsapp.com/JbQnGOHFcsuBEc2kgKRuBy"
  }
];
const DEFAULT_REGISTRO_RESET_AT = "2026-02-21T20:33:01.000Z";

function cleanText(value, maxLength = 120) {
  const text = String(value ?? "").trim().replace(/<[^>]*>/g, "");
  return text.length > maxLength ? text.slice(0, maxLength) : text;
}

function normalizeProfesion(value) {
  const list = Array.isArray(value)
    ? value
    : cleanText(value, 300)
        .split(",")
        .map((item) => item.trim())
        .filter(Boolean);

  const normalized = list
    .map((item) => cleanText(item, 40))
    .filter(Boolean);

  return [...new Set(normalized)];
}

function normalizeDni(value) {
  return cleanText(value, 20).replace(/\D+/g, "");
}

function normalizeLookupText(value) {
  return String(value ?? "")
    .normalize("NFD")
    .replace(/[\u0300-\u036f]/g, "")
    .toLowerCase()
    .replace(/[^\w\s/.-]/g, " ")
    .replace(/\s+/g, " ")
    .trim();
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

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function isDuplicateConstraintError(detail) {
  const normalized = String(detail ?? "").toLowerCase();
  return (
    normalized.includes("duplicate key value") ||
    normalized.includes("inscripciones_dni_encuentro_key") ||
    (normalized.includes("unique") &&
      normalized.includes("dni") &&
      normalized.includes("encuentro"))
  );
}

async function existsDniInEncuentro({ endpoint, serviceRoleKey, dni, encuentro }) {
  const lookupUrl = new URL(endpoint);
  lookupUrl.searchParams.set("select", "id");
  lookupUrl.searchParams.set("dni", `eq.${dni}`);
  lookupUrl.searchParams.set("encuentro", `eq.${encuentro}`);
  lookupUrl.searchParams.set("limit", "1");

  const response = await fetch(lookupUrl.toString(), {
    method: "GET",
    headers: {
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`
    }
  });

  if (!response.ok) {
    const detail = await response.text();
    throw new Error(`No se pudo validar el DNI: ${detail}`);
  }

  const rows = await response.json().catch(() => []);
  return Array.isArray(rows) && rows.length > 0;
}

function parseContentRangeTotal(contentRange) {
  const match = String(contentRange ?? "").match(/\/(\d+)$/);
  if (!match) {
    return null;
  }

  const total = Number.parseInt(match[1], 10);
  return Number.isFinite(total) ? total : null;
}

async function getRegistroNumeroPorEncuentro({
  endpoint,
  serviceRoleKey,
  encuentro,
  id,
  resetAt
}) {
  const numericId = Number.parseInt(String(id ?? ""), 10);

  if (!encuentro || !Number.isFinite(numericId)) {
    return null;
  }

  const lookupUrl = new URL(endpoint);
  lookupUrl.searchParams.set("select", "id");
  lookupUrl.searchParams.set("encuentro", `eq.${encuentro}`);
  lookupUrl.searchParams.set("id", `lte.${numericId}`);
  if (resetAt) {
    lookupUrl.searchParams.set("created_at", `gte.${resetAt}`);
  }

  const response = await fetch(lookupUrl.toString(), {
    method: "GET",
    headers: {
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`,
      Prefer: "count=exact"
    }
  });

  if (!response.ok) {
    const detail = await response.text();
    throw new Error(`No se pudo calcular el número de registro: ${detail}`);
  }

  const total = parseContentRangeTotal(response.headers.get("content-range"));
  if (total !== null) {
    return Math.max(total, 0);
  }

  const rows = await response.json().catch(() => []);
  return Array.isArray(rows) ? Math.max(rows.length, 0) : null;
}

function getRegistroResetAt() {
  const configured = cleanText(process.env.REGISTRO_RESET_AT, 80);
  const rawValue = configured || DEFAULT_REGISTRO_RESET_AT;
  const parsed = new Date(rawValue);

  if (Number.isNaN(parsed.getTime())) {
    return DEFAULT_REGISTRO_RESET_AT;
  }

  return parsed.toISOString();
}

function getClientIp(req) {
  const forwarded = req.headers["x-forwarded-for"];

  if (Array.isArray(forwarded) && forwarded.length > 0) {
    return String(forwarded[0]).split(",")[0].trim();
  }

  if (typeof forwarded === "string" && forwarded.length > 0) {
    return forwarded.split(",")[0].trim();
  }

  return req.socket?.remoteAddress || "";
}

function formatEncuentroLabel(encuentro) {
  return String(encuentro || "")
    .replace(/[_-]+/g, " ")
    .replace(/\s+/g, " ")
    .trim();
}

function escapeHtml(text) {
  return String(text ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function resolveWhatsappGroupUrl(encuentro) {
  const normalizedEncuentro = normalizeLookupText(encuentro);
  const matched = WHATSAPP_GROUP_MATCHERS.find((item) =>
    normalizedEncuentro.includes(item.key)
  );

  if (matched) {
    return matched.url;
  }

  return process.env.MAIL_WHATSAPP_GRUPO_URL || DEFAULT_WHATSAPP_GROUP_URL;
}

function resolveMailEventExtras(encuentro) {
  const normalizedEventKey = normalizeLookupText(getCanonicalEventName(encuentro));
  return MAIL_EVENT_EXTRAS.find((item) => item.eventKey === normalizedEventKey) || null;
}

async function sendConfirmationEmail({ to, nombre, encuentro, numeroRegistro }) {
  const resendApiKey = process.env.RESEND_API_KEY;
  const mailFrom = process.env.MAIL_FROM;
  const replyTo = process.env.MAIL_REPLY_TO;
  const whatsappGroupUrl = resolveWhatsappGroupUrl(encuentro);
  const logoUrl = "https://plomerosarg.com/Prueba_2/assets/logo-plomeros-circular.png";
  const eventExtras = resolveMailEventExtras(encuentro);
  const normalizedTo = cleanText(to, 120).toLowerCase();

  if (!resendApiKey || !mailFrom || !normalizedTo) {
    return { sent: false, skipped: true, reason: "missing_config" };
  }

  const safeNombre = escapeHtml(nombre || "participante");
  const safeEncuentro = escapeHtml(formatEncuentroLabel(encuentro) || "encuentro");
  const safeWhatsappGroupUrl = escapeHtml(whatsappGroupUrl);
  const safeLogoUrl = escapeHtml(logoUrl);
  const safeEventLocationAddress = escapeHtml(eventExtras?.locationAddress || "");
  const safeEventLocationLinkLabel = escapeHtml(eventExtras?.locationLinkLabel || "");
  const safeEventWhatsappRequiredNotice = escapeHtml(
    eventExtras?.whatsappRequiredNotice || ""
  );
  const safeEventIngresoHorarioLabel = escapeHtml(
    eventExtras?.ingresoHorarioLabel || "Horario de Ingreso"
  );
  const safeEventIngresoHorario = escapeHtml(eventExtras?.ingresoHorario || "");
  const safeEventLocationUrl = escapeHtml(eventExtras?.locationUrl || "");
  const safeEventMailImageUrl = escapeHtml(eventExtras?.imageUrl || "");
  const eventLocationUrlLineHtml = eventExtras
    ? eventExtras.locationLinkLabel
      ? `
        <br />
        <strong>${safeEventLocationLinkLabel}:</strong>
        <a href="${safeEventLocationUrl}" target="_blank" rel="noopener noreferrer">
          ${safeEventLocationUrl}
        </a>
      `
      : `
        <br />
        <a href="${safeEventLocationUrl}" target="_blank" rel="noopener noreferrer">
          ${safeEventLocationUrl}
        </a>
      `
    : "";
  const eventIngresoHorarioHtml =
    eventExtras?.ingresoHorario
      ? `
        <br />
        <strong>${safeEventIngresoHorarioLabel}:</strong> ${safeEventIngresoHorario}
      `
      : "";
  const eventBuenDiaHtml =
    eventExtras?.hideBuenDiaLine === true
      ? ""
      : `<p style="margin:0 0 10px;">Que tengan buen d&iacute;a</p>`;
  const eventWhatsappRequiredNoticeHtml =
    eventExtras?.whatsappRequiredNotice
      ? `<p style="margin:0 0 14px;color:#6b7280;font-size:13px;">${safeEventWhatsappRequiredNotice}</p>`
      : "";
  const eventLocationHtml = eventExtras
    ? `
      <p style="margin:0 0 12px;">
        <strong>Lugar del Encuentro:</strong>${eventExtras.locationAddress ? ` ${safeEventLocationAddress}` : ""}
        ${eventLocationUrlLineHtml}
        ${eventIngresoHorarioHtml}
      </p>
    `
    : "";
  const eventFlyerHtml = eventExtras
    ? `
      <img
        src="${safeEventMailImageUrl}"
        alt="Flyer del encuentro"
        width="520"
        style="display:block;width:100%;max-width:520px;height:auto;margin:12px auto 16px;border:0;outline:none;text-decoration:none;border-radius:12px;"
      />
    `
    : "";
  const html = `
    <div style="font-family:Arial,Helvetica,sans-serif;line-height:1.55;color:#10263f">
      <h2 style="margin:0 0 12px">Inscripci&oacute;n confirmada</h2>
      <p>Hola ${safeNombre}</p>
      <p>Ya est&aacute; confirmada su vacante para el encuentro <strong>${safeEncuentro}</strong>.</p>
      ${eventLocationHtml}
      <p>
        Ingrese al grupo de WhatsApp exclusivo para empezar a vivir la experiencia del encuentro para
        consultas, sorteo, informaci&oacute;n y comunicados:
      </p>
      <p style="margin:10px 0 14px;">
        <a
          href="${safeWhatsappGroupUrl}"
          target="_blank"
          rel="noopener noreferrer"
          style="display:inline-block;padding:10px 14px;border-radius:8px;background:#0b6b35;color:#ffffff;text-decoration:none;font-weight:700;"
        >
          Ingresar al grupo de WhatsApp
        </a>
      </p>
      ${eventWhatsappRequiredNoticeHtml}
      <p style="margin:0 0 14px;color:#c62828;font-weight:800;">
        Unos d&iacute;as antes del encuentro recibir&aacute; un mail para que confirme su asistencia.
      </p>
      ${eventBuenDiaHtml}
      <p style="margin:0 0 16px;">Saludos Equipo de Plomeros y Sanitaristas</p>
      <p style="margin:0 0 14px;color:#5b6b80;font-size:13px;">
        N&uacute;mero de registro: <strong>${escapeHtml(numeroRegistro ?? "pendiente")}</strong>
      </p>
      ${eventFlyerHtml}
      <img
        src="${safeLogoUrl}"
        alt="Logo Plomeros ARG"
        width="140"
        height="140"
        style="display:block;margin:8px auto 0;border:0;outline:none;text-decoration:none;"
      />
    </div>
  `;

  const payload = {
    from: mailFrom,
    to: [normalizedTo],
    subject: "Inscripci\u00f3n confirmada - Plomeros ARG",
    html
  };

  if (replyTo) {
    payload.reply_to = replyTo;
  }

  const response = await fetch("https://api.resend.com/emails", {
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
      sent: false,
      skipped: false,
      reason: `resend_error_${response.status}`,
      detail
    };
  }

  return { sent: true, skipped: false };
}

module.exports = async (req, res) => {
  if (req.method !== "POST") {
    return res.status(405).json({ ok: false, error: "Método no permitido. Usá POST." });
  }

  const payload = normalizeBody(req.body);

  const dni = normalizeDni(payload.dni);
  const encuentroInput = cleanText(payload.encuentro, 80);
  const encuentro = getCanonicalEventName(encuentroInput);
  const nombre_apellido = cleanText(payload.nombre_apellido, 120);
  const mail = cleanText(payload.mail, 120).toLowerCase();
  const provincia = cleanText(payload.provincia, 40);
  const localidad = cleanText(payload.localidad, 120);
  const asociado = cleanText(payload.asociado, 5);
  const profesiones = normalizeProfesion(payload.profesion);
  const profesion = profesiones.join(",");
  const origen = cleanText(payload.origen, 40);
  const acepto_terminos = cleanText(payload.acepto_terminos, 5).toLowerCase() === "si";

  if (
    !dni ||
    !encuentro ||
    encuentro === "Sin evento" ||
    !nombre_apellido ||
    !mail ||
    !provincia ||
    !asociado ||
    profesiones.length === 0 ||
    !origen ||
    !acepto_terminos
  ) {
    return res.status(422).json({
      ok: false,
      error: "Completá todos los campos obligatorios y aceptá los términos."
    });
  }

  if (!isValidEmail(mail)) {
    return res.status(422).json({ ok: false, error: "Mail inválido." });
  }

  if (!ALLOWED_PROVINCIAS.has(provincia)) {
    return res.status(422).json({ ok: false, error: "Provincia inválida." });
  }

  if (!ALLOWED_ASOCIADO.has(asociado)) {
    return res.status(422).json({ ok: false, error: "Valor de asociado inválido." });
  }

  if (!profesiones.every((item) => ALLOWED_PROFESION.has(item))) {
    return res.status(422).json({ ok: false, error: "Profesión inválida." });
  }

  if (!ALLOWED_ORIGEN.has(origen)) {
    return res.status(422).json({ ok: false, error: "Origen inválido." });
  }

  const supabaseUrl = process.env.SUPABASE_URL;
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(500).json({ ok: false, error: "Faltan variables de entorno de Supabase." });
  }

  const endpoint = `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`;

  const record = {
    encuentro,
    dni,
    nombre_apellido,
    mail,
    provincia,
    localidad,
    asociado,
    profesion,
    origen,
    acepto_terminos: true,
    ip: cleanText(getClientIp(req), 120),
    user_agent: cleanText(req.headers["user-agent"], 255)
  };

  try {
    const status = await getEventStatus({
      supabaseUrl,
      serviceRoleKey,
      encuentro
    });

    if (!status.ok) {
      return res.status(500).json({
        ok: false,
        error: "No se pudo validar el estado del encuentro.",
        detail: status.error || "Error desconocido"
      });
    }

    if (status.active === false) {
      return res.status(409).json({
        ok: false,
        error: "Las inscripciones para este encuentro estan cerradas."
      });
    }

    const duplicatedDni = await existsDniInEncuentro({
      endpoint,
      serviceRoleKey,
      dni,
      encuentro
    });

    if (duplicatedDni) {
      return res.status(409).json({
        ok: false,
        error: "Este DNI ya fue inscripto en este encuentro."
      });
    }

    const response = await fetch(endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        apikey: serviceRoleKey,
        Authorization: `Bearer ${serviceRoleKey}`,
        Prefer: "return=representation"
      },
      body: JSON.stringify(record)
    });

    if (!response.ok) {
      const detail = await response.text();
      if (response.status === 409 || isDuplicateConstraintError(detail)) {
        return res.status(409).json({
          ok: false,
          error: "Este DNI ya fue inscripto en este encuentro."
        });
      }

      return res.status(500).json({
        ok: false,
        error: "No se pudo guardar la inscripción en la base de datos.",
        detail
      });
    }

    const inserted = await response.json().catch(() => []);
    const id = Array.isArray(inserted) && inserted[0] ? inserted[0].id : null;
    let numeroRegistro = 0;
    const registroResetAt = getRegistroResetAt();

    try {
      const registroPorEncuentro = await getRegistroNumeroPorEncuentro({
        endpoint,
        serviceRoleKey,
        encuentro,
        id,
        resetAt: registroResetAt
      });
      if (registroPorEncuentro !== null) {
        numeroRegistro = registroPorEncuentro;
      }
    } catch {}

    const mailResult = await sendConfirmationEmail({
      to: mail,
      nombre: nombre_apellido,
      encuentro,
      numeroRegistro
    });

    return res.status(200).json({
      ok: true,
      message: "Inscripción guardada correctamente.",
      id,
      numero_registro: numeroRegistro,
      mail_enviado: mailResult.sent
    });
  } catch (error) {
    return res.status(500).json({
      ok: false,
      error: "Error interno al conectar con la base de datos.",
      detail: error instanceof Error ? error.message : "Error desconocido"
    });
  }
};
