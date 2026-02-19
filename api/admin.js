const crypto = require("crypto");

const COOKIE_NAME = "admin_session";
const SESSION_MAX_AGE = 60 * 60 * 12;

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

function verifyToken(token, secret) {
  if (!token || typeof token !== "string" || !token.includes(".")) {
    return false;
  }

  const [payload, signature] = token.split(".");
  if (!payload || !signature) return false;

  const expected = sign(payload, secret);
  if (!timingSafeEqual(signature, expected)) return false;

  try {
    const decoded = JSON.parse(Buffer.from(payload, "base64url").toString("utf8"));
    if (!decoded || typeof decoded !== "object") return false;
    if (typeof decoded.exp !== "number") return false;
    if (Date.now() > decoded.exp) return false;
    return true;
  } catch {
    return false;
  }
}

function parseBody(req) {
  if (!req.body) return {};

  if (typeof req.body === "object") {
    return req.body;
  }

  if (typeof req.body === "string") {
    const params = new URLSearchParams(req.body);
    return Object.fromEntries(params.entries());
  }

  return {};
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

function loginView(errorMessage = "") {
  const errorBlock = errorMessage
    ? `<p class="error">${errorMessage}</p>`
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

function panelView() {
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
    .panel {
      width: min(760px, 100%);
      background: var(--card);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 26px;
      box-shadow: 0 16px 28px rgba(15, 47, 87, 0.12);
    }
    h1 {
      font-size: clamp(1.8rem, 4vw, 2.6rem);
      margin-bottom: 8px;
    }
    p {
      color: #355d88;
      margin-bottom: 18px;
    }
    .status {
      border: 1px solid #c7d9f2;
      background: #f4f9ff;
      border-radius: 12px;
      padding: 14px;
      margin-bottom: 16px;
      font-weight: 600;
    }
    .actions {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
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
  </style>
</head>
<body>
  <main class="panel">
    <h1>Panel Admin</h1>
    <p>Acceso interno de Plomeros ARG.</p>
    <div class="status">Ruta protegida: <strong>/admin</strong></div>
    <div class="actions">
      <a class="btn btn-primary" href="https://supabase.com/dashboard" target="_blank" rel="noopener noreferrer">Ver inscripciones (Supabase)</a>
      <a class="btn btn-ghost" href="/">Volver al inicio</a>
      <form method="post" action="/api/admin">
        <input type="hidden" name="action" value="logout">
        <button class="btn btn-ghost" type="submit">Cerrar sesion</button>
      </form>
    </div>
  </main>
</body>
</html>`;
}

function sendHtml(res, statusCode, html) {
  res.status(statusCode);
  res.setHeader("Content-Type", "text/html; charset=utf-8");
  return res.send(html);
}

module.exports = async (req, res) => {
  const ADMIN_SESSION_SECRET = process.env.ADMIN_SESSION_SECRET;
  const ADMIN_CREDENTIALS = getAdminCredentials(process.env);

  if (!ADMIN_SESSION_SECRET || ADMIN_CREDENTIALS.length === 0) {
    return res.status(500).json({
      ok: false,
      error: "Faltan variables de entorno para login admin (ADMIN_SESSION_SECRET y usuarios admin)."
    });
  }

  const cookies = parseCookies(req);
  const hasValidSession = verifyToken(cookies[COOKIE_NAME], ADMIN_SESSION_SECRET);

  if (req.method === "GET") {
    if (hasValidSession) {
      return sendHtml(res, 200, panelView());
    }
    return sendHtml(res, 200, loginView());
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
