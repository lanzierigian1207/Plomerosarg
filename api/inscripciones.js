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
  "otros"
]);

const ALLOWED_ORIGEN = new Set([
  "facebook",
  "grupo_whatsapp",
  "instagram",
  "casa_sanitarios",
  "centro_formacion"
]);

function cleanText(value, maxLength = 120) {
  const text = String(value ?? "").trim().replace(/<[^>]*>/g, "");
  return text.length > maxLength ? text.slice(0, maxLength) : text;
}

function normalizeDni(value) {
  return cleanText(value, 20).replace(/\D+/g, "");
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

async function sendConfirmationEmail({ to, nombre, encuentro, id }) {
  const resendApiKey = process.env.RESEND_API_KEY;
  const mailFrom = process.env.MAIL_FROM;
  const replyTo = process.env.MAIL_REPLY_TO;
  const whatsappGroupUrl =
    process.env.MAIL_WHATSAPP_GRUPO_URL ||
    "https://wa.me/5491100000000?text=Hola%2C%20quiero%20sumarme%20al%20grupo%20del%20encuentro";
  const logoUrl = "https://plomerosarg.com/Prueba_2/assets/logo-plomeros-circular.png";
  const normalizedTo = cleanText(to, 120).toLowerCase();

  if (!resendApiKey || !mailFrom || !normalizedTo) {
    return { sent: false, skipped: true, reason: "missing_config" };
  }

  const safeNombre = escapeHtml(nombre || "participante");
  const safeEncuentro = escapeHtml(formatEncuentroLabel(encuentro) || "encuentro");
  const safeWhatsappGroupUrl = escapeHtml(whatsappGroupUrl);
  const safeLogoUrl = escapeHtml(logoUrl);
  const html = `
    <div style="font-family:Arial,Helvetica,sans-serif;line-height:1.55;color:#10263f">
      <h2 style="margin:0 0 12px">Inscripci&oacute;n confirmada</h2>
      <p>Hola ${safeNombre}</p>
      <p>Ya est&aacute; confirmada su vacante para el encuentro <strong>${safeEncuentro}</strong>.</p>
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
      <p style="margin:0 0 14px;color:#c62828;font-weight:800;">
        Unos d&iacute;as antes del encuentro recibir&aacute; un mail para que confirme su asistencia.
      </p>
      <p style="margin:0 0 10px;">Que tengan buen d&iacute;a</p>
      <p style="margin:0 0 16px;">Saludos Equipo de Plomeros y Sanitaristas</p>
      <p style="margin:0 0 14px;color:#5b6b80;font-size:13px;">
        N&uacute;mero de registro: <strong>${escapeHtml(id || "pendiente")}</strong>
      </p>
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
    return res.status(405).json({ ok: false, error: "Metodo no permitido. Usa POST." });
  }

  const payload = normalizeBody(req.body);

  const dni = normalizeDni(payload.dni);
  const encuentro = cleanText(payload.encuentro, 80);
  const nombre_apellido = cleanText(payload.nombre_apellido, 120);
  const mail = cleanText(payload.mail, 120).toLowerCase();
  const provincia = cleanText(payload.provincia, 40);
  const localidad = cleanText(payload.localidad, 120);
  const asociado = cleanText(payload.asociado, 5);
  const profesion = cleanText(payload.profesion, 40);
  const origen = cleanText(payload.origen, 40);
  const acepto_terminos = cleanText(payload.acepto_terminos, 5).toLowerCase() === "si";

  if (
    !dni ||
    !encuentro ||
    !nombre_apellido ||
    !mail ||
    !provincia ||
    !asociado ||
    !profesion ||
    !origen ||
    !acepto_terminos
  ) {
    return res.status(422).json({
      ok: false,
      error: "Completa todos los campos obligatorios y acepta los terminos."
    });
  }

  if (!isValidEmail(mail)) {
    return res.status(422).json({ ok: false, error: "Mail invalido." });
  }

  if (!ALLOWED_PROVINCIAS.has(provincia)) {
    return res.status(422).json({ ok: false, error: "Provincia invalida." });
  }

  if (!ALLOWED_ASOCIADO.has(asociado)) {
    return res.status(422).json({ ok: false, error: "Valor de asociado invalido." });
  }

  if (!ALLOWED_PROFESION.has(profesion)) {
    return res.status(422).json({ ok: false, error: "Profesion invalida." });
  }

  if (!ALLOWED_ORIGEN.has(origen)) {
    return res.status(422).json({ ok: false, error: "Origen invalido." });
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
        error: "No se pudo guardar la inscripcion en la base de datos.",
        detail
      });
    }

    const inserted = await response.json().catch(() => []);
    const id = Array.isArray(inserted) && inserted[0] ? inserted[0].id : null;
    const mailResult = await sendConfirmationEmail({
      to: mail,
      nombre: nombre_apellido,
      encuentro,
      id
    });

    return res.status(200).json({
      ok: true,
      message: "Inscripcion guardada correctamente.",
      id,
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
