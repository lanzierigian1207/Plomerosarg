const { cleanText, getCanonicalEventName } = require("./_encuentros");

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

module.exports = async (req, res) => {
  if (req.method !== "GET" && req.method !== "POST") {
    return res.status(405).json({
      ok: false,
      error: "Metodo no permitido. Usa GET o POST."
    });
  }

  const payload = getPayload(req);
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

  const endpoint = new URL(`${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones`);
  endpoint.searchParams.set("select", "encuentro,nombre_apellido,dni,created_at");
  endpoint.searchParams.set("dni", `eq.${dni}`);
  endpoint.searchParams.set("order", "created_at.desc");
  endpoint.searchParams.set("limit", "200");

  try {
    const response = await fetch(endpoint.toString(), {
      method: "GET",
      headers: buildHeaders(serviceRoleKey)
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
    const list = Array.isArray(rows) ? rows : [];

    if (list.length === 0) {
      return res.status(200).json({
        ok: true,
        found: false,
        dni,
        nombre_apellido: "",
        encuentros: []
      });
    }

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

    const encuentros = [...encuentrosMap.values()].sort((a, b) =>
      a.encuentro.localeCompare(b.encuentro, "es")
    );

    return res.status(200).json({
      ok: true,
      found: encuentros.length > 0,
      dni,
      nombre_apellido: cleanText(list[0].nombre_apellido, 120),
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
