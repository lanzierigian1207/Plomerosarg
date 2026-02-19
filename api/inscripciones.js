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

module.exports = async (req, res) => {
  if (req.method !== "POST") {
    return res.status(405).json({ ok: false, error: "Metodo no permitido. Usa POST." });
  }

  const payload = normalizeBody(req.body);

  const dni = cleanText(payload.dni, 20);
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
      return res.status(500).json({
        ok: false,
        error: "No se pudo guardar la inscripcion en la base de datos.",
        detail
      });
    }

    const inserted = await response.json().catch(() => []);
    const id = Array.isArray(inserted) && inserted[0] ? inserted[0].id : null;

    return res.status(200).json({
      ok: true,
      message: "Inscripcion guardada correctamente.",
      id
    });
  } catch (error) {
    return res.status(500).json({
      ok: false,
      error: "Error interno al conectar con la base de datos.",
      detail: error instanceof Error ? error.message : "Error desconocido"
    });
  }
};
