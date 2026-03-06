const { cleanText, getCanonicalEventName } = require("./_encuentros");

const RESEND_ENDPOINT = "https://api.resend.com/emails";
const RECONFIRM_KEY_PREFIX = "__reconfirm__::";

function normalizeDni(value) {
  return String(value ?? "")
    .replace(/\D+/g, "")
    .trim()
    .slice(0, 20);
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

function normalizeEncuentros(value) {
  const source = Array.isArray(value)
    ? value
    : typeof value === "string"
      ? value.split(",")
      : [];

  const seen = new Set();
  const result = [];

  for (const item of source) {
    const canonical = getCanonicalEventName(cleanText(item, 80));
    if (!canonical || canonical === "Sin evento") continue;
    if (seen.has(canonical)) continue;
    seen.add(canonical);
    result.push(canonical);
  }

  return result;
}

function buildReconfirmStorageKey(eventName, dni) {
  const canonicalEvent = getCanonicalEventName(cleanText(eventName, 120));
  const normalizedDni = normalizeDni(dni);

  if (!canonicalEvent || canonicalEvent === "Sin evento" || !normalizedDni) {
    return "";
  }

  return `${RECONFIRM_KEY_PREFIX}${encodeURIComponent(canonicalEvent)}::${normalizedDni}`;
}

function getPayload(req) {
  if (req.method === "GET") {
    return req.query || {};
  }
  return normalizeBody(req.body);
}

function buildHeaders(serviceRoleKey) {
  return {
    apikey: serviceRoleKey,
    Authorization: `Bearer ${serviceRoleKey}`,
    Accept: "application/json"
  };
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function escapeHtml(text) {
  return String(text ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function pickContacto(rows) {
  const list = Array.isArray(rows) ? rows : [];
  let nombre = "";

  for (const row of list) {
    const rowNombre = cleanText(row?.nombre_apellido, 120);
    if (!nombre && rowNombre) {
      nombre = rowNombre;
    }

    const rowMail = cleanText(row?.mail, 120).toLowerCase();
    if (isValidEmail(rowMail)) {
      return {
        nombre: rowNombre || nombre || "participante",
        mail: rowMail
      };
    }
  }

  return {
    nombre: nombre || "participante",
    mail: ""
  };
}

function buildEncuentrosSummary(rows) {
  const list = Array.isArray(rows) ? rows : [];
  const encuentrosMap = new Map();

  for (const row of list) {
    const eventName = getCanonicalEventName(row.encuentro);
    const key = cleanText(eventName, 120);
    if (!key) continue;

    if (!encuentrosMap.has(key)) {
      encuentrosMap.set(key, {
        encuentro: key,
        cantidad: 0,
        ultima_inscripcion: row.created_at || null
      });
    }

    const item = encuentrosMap.get(key);
    item.cantidad += 1;

    const currentTs = Date.parse(String(item.ultima_inscripcion || ""));
    const nextTs = Date.parse(String(row.created_at || ""));
    if (Number.isFinite(nextTs) && (!Number.isFinite(currentTs) || nextTs > currentTs)) {
      item.ultima_inscripcion = row.created_at || null;
    }
  }

  return [...encuentrosMap.values()].sort((a, b) =>
    a.encuentro.localeCompare(b.encuentro, "es")
  );
}

async function fetchInscripcionesByDni({
  supabaseUrl,
  serviceRoleKey,
  dni,
  select
}) {
  const endpoint = new URL(`${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`);
  endpoint.searchParams.set("select", select);
  endpoint.searchParams.set("dni", `eq.${dni}`);
  endpoint.searchParams.set("order", "created_at.desc");
  endpoint.searchParams.set("limit", "500");

  const response = await fetch(endpoint.toString(), {
    method: "GET",
    headers: buildHeaders(serviceRoleKey)
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    throw new Error(`No se pudo consultar la base de datos. ${detail}`.trim());
  }

  const rows = await response.json().catch(() => []);
  return Array.isArray(rows) ? rows : [];
}

async function sendReconfirmacionEmail({ to, nombre, encuentros }) {
  const resendApiKey = String(process.env.RESEND_API_KEY || "").trim();
  const mailFrom = String(process.env.MAIL_FROM || "").trim();
  const replyTo = String(process.env.MAIL_REPLY_TO || "").trim();
  const normalizedTo = cleanText(to, 120).toLowerCase();
  const encuentrosList = Array.isArray(encuentros)
    ? encuentros.map((item) => cleanText(item, 120)).filter(Boolean)
    : [];

  if (!resendApiKey || !mailFrom || !normalizedTo) {
    return { sent: false, reason: "missing_config" };
  }

  if (!isValidEmail(normalizedTo)) {
    return { sent: false, reason: "invalid_mail" };
  }

  if (encuentrosList.length === 0) {
    return { sent: false, reason: "missing_events" };
  }

  const safeNombre = escapeHtml(cleanText(nombre, 120) || "participante");
  const eventosHtml = encuentrosList
    .map((item) => `<li style="margin:0 0 6px;">${escapeHtml(item)}</li>`)
    .join("");

  const html = `
    <div style="font-family:Arial,Helvetica,sans-serif;line-height:1.55;color:#10263f">
      <h2 style="margin:0 0 12px;">Reconfirmaci&oacute;n de asistencia</h2>
      <p style="margin:0 0 10px;">Hola ${safeNombre}</p>
      <p style="margin:0 0 10px;">
        Recibimos tu reconfirmaci&oacute;n para los siguientes encuentros de Plomeros ARG:
      </p>
      <ul style="margin:0 0 12px 20px;padding:0;color:#12304f;">
        ${eventosHtml}
      </ul>
      <p style="margin:0 0 10px;">Nos vemos en el/los encuentro/s.</p>
      <p style="margin:0;">Saludos, Equipo de Plomeros y Sanitaristas</p>
    </div>
  `;

  const payload = {
    from: mailFrom,
    to: [normalizedTo],
    subject: "Reconfirmacion de asistencia - Plomeros ARG",
    html
  };

  if (replyTo) {
    payload.reply_to = replyTo;
  }

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
      sent: false,
      reason: `resend_error_${response.status}`,
      detail
    };
  }

  return { sent: true };
}

async function upsertReconfirmStates({
  supabaseUrl,
  serviceRoleKey,
  dni,
  encuentros
}) {
  const records = (Array.isArray(encuentros) ? encuentros : [])
    .map((eventName) => {
      const storageKey = buildReconfirmStorageKey(eventName, dni);
      if (!storageKey) return null;
      return {
        encuentro: storageKey,
        activo: true
      };
    })
    .filter(Boolean);

  if (records.length === 0) {
    return { ok: false, error: "No hay encuentros validos para guardar reconfirmacion." };
  }

  const endpoint = new URL(`${supabaseUrl.replace(/\/$/, "")}/rest/v1/encuentros_estado`);
  endpoint.searchParams.set("on_conflict", "encuentro");

  const response = await fetch(endpoint.toString(), {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      apikey: serviceRoleKey,
      Authorization: `Bearer ${serviceRoleKey}`,
      Prefer: "resolution=merge-duplicates,return=representation"
    },
    body: JSON.stringify(records)
  });

  if (!response.ok) {
    const detail = await response.text().catch(() => "");
    return {
      ok: false,
      error: `No se pudo guardar la reconfirmacion (${response.status}).`,
      detail
    };
  }

  return { ok: true };
}

module.exports = async (req, res) => {
  if (req.method !== "GET" && req.method !== "POST") {
    return res.status(405).json({
      ok: false,
      error: "Metodo no permitido. Usa GET o POST."
    });
  }

  const payload = getPayload(req);
  const action = cleanText(payload.action, 30).toLowerCase();
  const dni = normalizeDni(payload.dni);

  if (!dni) {
    return res.status(422).json({
      ok: false,
      error: "Tenes que indicar un DNI valido."
    });
  }

  const supabaseUrl = String(process.env.SUPABASE_URL || "").trim();
  const serviceRoleKey = String(process.env.SUPABASE_SERVICE_ROLE_KEY || "").trim();

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables de entorno de Supabase."
    });
  }

  try {
    if (action === "reconfirmar") {
      if (req.method !== "POST") {
        return res.status(405).json({
          ok: false,
          error: "Metodo no permitido para reconfirmacion. Usa POST."
        });
      }

      const encuentrosSolicitados = normalizeEncuentros(payload.encuentros);
      if (encuentrosSolicitados.length === 0) {
        return res.status(422).json({
          ok: false,
          error: "Tenes que seleccionar al menos un encuentro para reconfirmar."
        });
      }

      const rows = await fetchInscripcionesByDni({
        supabaseUrl,
        serviceRoleKey,
        dni,
        select: "encuentro,nombre_apellido,dni,mail,created_at"
      });

      if (rows.length === 0) {
        return res.status(404).json({
          ok: false,
          error: "No encontramos inscripciones para ese DNI."
        });
      }

      const encuentrosInscriptosSet = new Set(
        rows
          .map((row) => cleanText(getCanonicalEventName(row.encuentro), 120))
          .filter(Boolean)
      );

      const encuentrosReconfirmados = encuentrosSolicitados.filter((encuentro) =>
        encuentrosInscriptosSet.has(encuentro)
      );
      const encuentrosNoEncontrados = encuentrosSolicitados.filter(
        (encuentro) => !encuentrosInscriptosSet.has(encuentro)
      );

      if (encuentrosReconfirmados.length === 0) {
        return res.status(409).json({
          ok: false,
          error: "Ese DNI no tiene inscripcion en los encuentros seleccionados.",
          encuentros_no_encontrados: encuentrosNoEncontrados
        });
      }

      const rowsFiltradas = rows.filter((row) => {
        const canonical = cleanText(getCanonicalEventName(row.encuentro), 120);
        return encuentrosReconfirmados.includes(canonical);
      });

      const contacto = pickContacto(rowsFiltradas);
      if (!contacto.mail) {
        return res.status(422).json({
          ok: false,
          error: "No encontramos un mail valido para este DNI."
        });
      }

      const saveReconfirm = await upsertReconfirmStates({
        supabaseUrl,
        serviceRoleKey,
        dni,
        encuentros: encuentrosReconfirmados
      });

      if (!saveReconfirm.ok) {
        return res.status(500).json({
          ok: false,
          error: saveReconfirm.error || "No se pudo guardar la reconfirmacion.",
          detail: saveReconfirm.detail || ""
        });
      }

      const mailResult = await sendReconfirmacionEmail({
        to: contacto.mail,
        nombre: contacto.nombre,
        encuentros: encuentrosReconfirmados
      });

      if (!mailResult.sent) {
        return res.status(502).json({
          ok: false,
          error: "No se pudo enviar el mail de reconfirmacion.",
          detail: mailResult.detail || mailResult.reason || ""
        });
      }

      return res.status(200).json({
        ok: true,
        sent: true,
        dni,
        nombre_apellido: contacto.nombre,
        encuentros_reconfirmados: encuentrosReconfirmados,
        encuentros_no_encontrados: encuentrosNoEncontrados
      });
    }

    const rows = await fetchInscripcionesByDni({
      supabaseUrl,
      serviceRoleKey,
      dni,
      select: "encuentro,nombre_apellido,dni,created_at"
    });

    if (rows.length === 0) {
      return res.status(200).json({
        ok: true,
        found: false,
        dni,
        nombre_apellido: "",
        encuentros: []
      });
    }

    const encuentros = buildEncuentrosSummary(rows);

    return res.status(200).json({
      ok: true,
      found: encuentros.length > 0,
      dni,
      nombre_apellido: cleanText(rows[0].nombre_apellido, 120),
      encuentros
    });
  } catch (error) {
    return res.status(500).json({
      ok: false,
      error: "Error interno al consultar reconfirmacion.",
      detail: error instanceof Error ? error.message : "Error desconocido"
    });
  }
};
