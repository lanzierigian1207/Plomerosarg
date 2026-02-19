const crypto = require("crypto");

const COOKIE_NAME = "admin_session";
const SESSION_MAX_AGE = 60 * 60 * 12;

const EXPORT_COLUMNS = [
  { key: "id", label: "ID" },
  { key: "created_at", label: "Fecha" },
  { key: "encuentro", label: "Encuentro" },
  { key: "dni", label: "DNI" },
  { key: "nombre_apellido", label: "Nombre y Apellido" },
  { key: "mail", label: "Mail" },
  { key: "provincia", label: "Provincia" },
  { key: "localidad", label: "Localidad" },
  { key: "asociado", label: "Asociado" },
  { key: "profesion", label: "Profesion" },
  { key: "origen", label: "Origen" }
];

function parseCookies(req) {
  const raw = req.headers.cookie || "";
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

function createToken(username, secret) {
  const payloadObject = {
    u: username,
    exp: Date.now() + SESSION_MAX_AGE * 1000
  };

  const payload = Buffer.from(JSON.stringify(payloadObject), "utf8").toString("base64url");
  const signature = sign(payload, secret);
  return `${payload}.${signature}`;
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

function parseBody(req) {
  if (!req.body) return {};

  if (typeof req.body === "object" && !Buffer.isBuffer(req.body)) {
    return req.body;
  }

  const raw = Buffer.isBuffer(req.body) ? req.body.toString("utf8") : String(req.body);
  const params = new URLSearchParams(raw);
  return Object.fromEntries(params.entries());
}

function parseAdminsFromJson(value) {
  if (!value) return [];

  try {
    const parsed = JSON.parse(value);
    if (!Array.isArray(parsed)) return [];

    return parsed
      .filter((item) => item && typeof item === "object")
      .map((item) => ({
        username: String(item.username || "").trim(),
        password: String(item.password || "")
      }))
      .filter((item) => item.username && item.password);
  } catch {
    return [];
  }
}

function parseAdminsFromList(value) {
  if (!value) return [];

  return String(value)
    .split(/[\n,;]+/)
    .map((entry) => entry.trim())
    .filter(Boolean)
    .map((entry) => {
      const index = entry.indexOf(":");
      if (index <= 0) return null;
      const username = entry.slice(0, index).trim();
      const password = entry.slice(index + 1);
      if (!username || !password) return null;
      return { username, password };
    })
    .filter(Boolean);
}

function getAdminCredentials(env) {
  const fromJson = parseAdminsFromJson(env.ADMIN_USERS_JSON);
  if (fromJson.length > 0) return fromJson;

  const fromList = parseAdminsFromList(env.ADMIN_USERS);
  if (fromList.length > 0) return fromList;

  const singleUser = String(env.ADMIN_USER || "").trim();
  const singlePass = String(env.ADMIN_PASSWORD || "");
  if (singleUser && singlePass) {
    return [{ username: singleUser, password: singlePass }];
  }

  return [];
}

function hasValidCredentials(username, password, admins) {
  return admins.some((admin) => {
    return timingSafeEqual(username, admin.username) && timingSafeEqual(password, admin.password);
  });
}

function getSearchParams(req) {
  try {
    const url = new URL(req.url || "/", "http://localhost");
    return url.searchParams;
  } catch {
    return new URLSearchParams();
  }
}

function escapeHtml(value) {
  const text = String(value ?? "");
  return text
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}

function toCsvCell(value) {
  const text = String(value ?? "");
  return `"${text.replace(/"/g, "\"\"")}"`;
}

function formatDate(value) {
  if (!value) return "";
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) return String(value);
  return date.toLocaleString("es-AR");
}

function buildCsv(rows) {
  const headers = EXPORT_COLUMNS.map((col) => toCsvCell(col.label)).join(",");
  const lines = rows.map((row) => {
    return EXPORT_COLUMNS
      .map((col) => {
        const val = col.key === "created_at" ? formatDate(row[col.key]) : row[col.key];
        return toCsvCell(val);
      })
      .join(",");
  });

  return `\uFEFF${[headers, ...lines].join("\n")}`;
}

async function fetchInscripciones(env) {
  const supabaseUrl = String(env.SUPABASE_URL || "").trim();
  const serviceRoleKey = String(env.SUPABASE_SERVICE_ROLE_KEY || "").trim();

  if (!supabaseUrl || !serviceRoleKey) {
    return {
      rows: [],
      error: "Faltan SUPABASE_URL o SUPABASE_SERVICE_ROLE_KEY en Vercel."
    };
  }

  const endpoint = `${supabaseUrl.replace(/\/$/, "")}/rest/v1/inscripciones?select=id,created_at,encuentro,dni,nombre_apellido,mail,provincia,localidad,asociado,profesion,origen&order=created_at.desc&limit=1000`;

  try {
    const response = await fetch(endpoint, {
      method: "GET",
      headers: {
        apikey: serviceRoleKey,
        Authorization: `Bearer ${serviceRoleKey}`,
        Accept: "application/json"
      }
    });

    if (!response.ok) {
      const detail = await response.text();
      return {
        rows: [],
        error: `No se pudo leer inscripciones (${response.status}). ${detail}`
      };
    }

    const rows = await response.json();
    if (!Array.isArray(rows)) {
      return { rows: [], error: "Respuesta invalida de Supabase." };
    }

    return { rows, error: "" };
  } catch (error) {
    return {
      rows: [],
      error: `Error al consultar Supabase: ${error instanceof Error ? error.message : "desconocido"}`
    };
  }
}

function loginView(errorMessage = "") {
  const errorBlock = errorMessage
    ? `<p class="error">${escapeHtml(errorMessage)}</p>`
    : "";

  return `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | Plomeros ARG</title>
  <style>
    :root {
      --ink: #0f2f57;
      --bg: #dff1ff;
      --card: #ffffff;
      --line: #dbe6f2;
      --accent: #0d63c7;
      --error: #b3261e;
    }
    * { box-sizing: border-box; margin: 0; }
    body {
      font-family: "Barlow", sans-serif;
      color: var(--ink);
      background: var(--bg);
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 20px;
    }
    .card {
      width: min(420px, 100%);
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 24px;
      box-shadow: 0 16px 28px rgba(15, 47, 87, 0.12);
    }
    h1 {
      font-size: clamp(1.7rem, 4.4vw, 2.2rem);
      margin-bottom: 8px;
    }
    p {
      color: #355d88;
      margin-bottom: 16px;
      font-size: 0.95rem;
    }
    .error {
      background: #fdeaea;
      border: 1px solid #f0b9b6;
      color: var(--error);
      border-radius: 10px;
      padding: 10px 12px;
      margin-bottom: 14px;
      font-weight: 700;
    }
    form {
      display: grid;
      gap: 12px;
    }
    label {
      font-weight: 700;
      font-size: 0.93rem;
    }
    input {
      width: 100%;
      border: 1px solid var(--line);
      border-radius: 10px;
      padding: 10px 12px;
      font: inherit;
      color: var(--ink);
      background: #fff;
    }
    button {
      border: 0;
      border-radius: 10px;
      background: var(--accent);
      color: #fff;
      font-weight: 700;
      padding: 10px 14px;
      cursor: pointer;
      margin-top: 4px;
    }
  </style>
</head>
<body>
  <main class="card">
    <h1>Admin</h1>
    <p>Ingresa con tus credenciales de administrador.</p>
    ${errorBlock}
    <form method="post" action="/api/admin">
      <div>
        <label for="username">Usuario</label>
        <input id="username" name="username" type="text" autocomplete="username" required>
      </div>
      <div>
        <label for="password">Contrasena</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
      </div>
      <button type="submit">Entrar</button>
    </form>
  </main>
</body>
</html>`;
}

function buildRowsHtml(rows) {
  return rows
    .map((row) => {
      return `<tr>
        <td>${escapeHtml(row.id)}</td>
        <td>${escapeHtml(formatDate(row.created_at))}</td>
        <td>${escapeHtml(row.encuentro)}</td>
        <td>${escapeHtml(row.dni)}</td>
        <td>${escapeHtml(row.nombre_apellido)}</td>
        <td>${escapeHtml(row.mail)}</td>
        <td>${escapeHtml(row.provincia)}</td>
        <td>${escapeHtml(row.localidad)}</td>
        <td>${escapeHtml(row.asociado)}</td>
        <td>${escapeHtml(row.profesion)}</td>
        <td>${escapeHtml(row.origen)}</td>
      </tr>`;
    })
    .join("");
}

function panelView({ rows, error }) {
  const errorBlock = error
    ? `<p class="error">${escapeHtml(error)}</p>`
    : "";

  const countLabel = `Total de inscripciones: ${rows.length}`;

  const tableBlock = rows.length > 0
    ? `<div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Fecha</th>
              <th>Encuentro</th>
              <th>DNI</th>
              <th>Nombre y Apellido</th>
              <th>Mail</th>
              <th>Provincia</th>
              <th>Localidad</th>
              <th>Asociado</th>
              <th>Profesion</th>
              <th>Origen</th>
            </tr>
          </thead>
          <tbody>
            ${buildRowsHtml(rows)}
          </tbody>
        </table>
      </div>`
    : `<p class="empty">No hay inscripciones para mostrar.</p>`;

  return `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Plomeros ARG</title>
  <style>
    :root {
      --ink: #0f2f57;
      --bg: #dff1ff;
      --card: #ffffff;
      --line: #dbe6f2;
      --accent: #0d63c7;
      --error: #b3261e;
    }
    * { box-sizing: border-box; margin: 0; }
    body {
      font-family: "Barlow", sans-serif;
      color: var(--ink);
      background: var(--bg);
      min-height: 100vh;
      padding: 20px;
    }
    .panel {
      width: min(1220px, 100%);
      margin: 0 auto;
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 22px;
      box-shadow: 0 16px 28px rgba(15, 47, 87, 0.12);
    }
    h1 {
      font-size: clamp(1.7rem, 4vw, 2.3rem);
      margin-bottom: 8px;
    }
    p {
      color: #355d88;
      margin-bottom: 14px;
    }
    .status {
      border: 1px solid #c7d9f2;
      background: #f4f9ff;
      border-radius: 12px;
      padding: 12px 14px;
      margin-bottom: 12px;
      font-weight: 700;
    }
    .error {
      background: #fdeaea;
      border: 1px solid #f0b9b6;
      color: var(--error);
      border-radius: 10px;
      padding: 10px 12px;
      margin-bottom: 12px;
      font-weight: 700;
    }
    .actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
      margin-bottom: 14px;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 10px 14px;
      border-radius: 10px;
      font-weight: 700;
      border: 1px solid transparent;
      text-decoration: none;
      font: inherit;
      cursor: pointer;
    }
    .btn-primary {
      background: var(--accent);
      color: #fff;
    }
    .btn-ghost {
      background: #fff;
      color: var(--accent);
      border-color: var(--accent);
    }
    form { margin: 0; }
    .table-wrap {
      width: 100%;
      overflow: auto;
      border: 1px solid var(--line);
      border-radius: 12px;
      background: #fff;
    }
    table {
      width: 100%;
      min-width: 1140px;
      border-collapse: collapse;
      font-size: 0.9rem;
    }
    thead th {
      text-align: left;
      background: #f2f7ff;
      color: #264f7d;
      padding: 10px;
      border-bottom: 1px solid var(--line);
      position: sticky;
      top: 0;
      z-index: 1;
    }
    tbody td {
      padding: 9px 10px;
      border-bottom: 1px solid #eef3fa;
      vertical-align: top;
    }
    tbody tr:nth-child(even) {
      background: #fbfdff;
    }
    .empty {
      border: 1px dashed #c7d9f2;
      border-radius: 10px;
      padding: 14px;
      background: #f8fbff;
      margin: 0;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <main class="panel">
    <h1>Panel Admin</h1>
    <p>Inscripciones registradas en tiempo real.</p>
    <div class="status">${escapeHtml(countLabel)}</div>
    ${errorBlock}
    <div class="actions">
      <a class="btn btn-primary" href="/admin?download=excel">Descargar Excel (.csv)</a>
      <a class="btn btn-ghost" href="/">Volver al inicio</a>
      <form method="post" action="/api/admin">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-ghost" type="submit">Cerrar sesion</button>
      </form>
    </div>
    ${tableBlock}
  </main>
</body>
</html>`;
}

function sendHtml(res, statusCode, html) {
  res.status(statusCode);
  res.setHeader("Content-Type", "text/html; charset=utf-8");
  res.setHeader("Cache-Control", "no-store");
  return res.send(html);
}

module.exports = async (req, res) => {
  const ADMIN_SESSION_SECRET = process.env.ADMIN_SESSION_SECRET;
  const ADMIN_CREDENTIALS = getAdminCredentials(process.env);

  if (!ADMIN_SESSION_SECRET || ADMIN_CREDENTIALS.length === 0) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables para login admin (ADMIN_SESSION_SECRET y usuarios admin)."
    });
  }

  const cookies = parseCookies(req);
  const session = decodeToken(cookies[COOKIE_NAME], ADMIN_SESSION_SECRET);
  const isAuthenticated = Boolean(session);

  if (req.method === "GET") {
    if (!isAuthenticated) {
      return sendHtml(res, 200, loginView());
    }

    const params = getSearchParams(req);
    const { rows, error } = await fetchInscripciones(process.env);

    if (params.get("download") === "excel") {
      if (error) {
        return res.status(500).json({ ok: false, error });
      }

      const csv = buildCsv(rows);
      const today = new Date().toISOString().slice(0, 10);
      res.status(200);
      res.setHeader("Content-Type", "text/csv; charset=utf-8");
      res.setHeader("Content-Disposition", `attachment; filename=\"inscripciones-${today}.csv\"`);
      res.setHeader("Cache-Control", "no-store");
      return res.send(csv);
    }

    return sendHtml(res, 200, panelView({ rows, error }));
  }

  if (req.method !== "POST") {
    return res.status(405).json({ ok: false, error: "Metodo no permitido." });
  }

  const body = parseBody(req);
  const action = String(body.action || "").trim();

  if (action === "logout") {
    res.setHeader(
      "Set-Cookie",
      `${COOKIE_NAME}=; HttpOnly; Secure; SameSite=Lax; Path=/; Max-Age=0`
    );
    res.status(302);
    res.setHeader("Location", "/admin");
    return res.end();
  }

  const username = String(body.username || "").trim();
  const password = String(body.password || "");

  if (!hasValidCredentials(username, password, ADMIN_CREDENTIALS)) {
    return sendHtml(res, 401, loginView("Credenciales invalidas."));
  }

  const token = createToken(username, ADMIN_SESSION_SECRET);
  res.setHeader(
    "Set-Cookie",
    `${COOKIE_NAME}=${encodeURIComponent(token)}; HttpOnly; Secure; SameSite=Lax; Path=/; Max-Age=${SESSION_MAX_AGE}`
  );
  res.status(302);
  res.setHeader("Location", "/admin");
  return res.end();
};
