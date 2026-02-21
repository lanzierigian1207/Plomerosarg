const {
  KNOWN_EVENTS,
  fetchEventStatusMap,
  resolveEventActive
} = require("./_encuentros");

function buildDefaultEvents() {
  return KNOWN_EVENTS.map((evento) => ({
    evento,
    activo: true
  }));
}

module.exports = async (req, res) => {
  if (req.method !== "GET") {
    return res.status(405).json({
      ok: false,
      error: "Metodo no permitido. Usa GET."
    });
  }

  const supabaseUrl = process.env.SUPABASE_URL;
  const serviceRoleKey = process.env.SUPABASE_SERVICE_ROLE_KEY;

  if (!supabaseUrl || !serviceRoleKey) {
    return res.status(200).json({
      ok: true,
      eventos: buildDefaultEvents(),
      status_available: false,
      warning: "Sin configuracion de Supabase. Se muestran todos los encuentros activos por defecto."
    });
  }

  try {
    const statusResult = await fetchEventStatusMap({ supabaseUrl, serviceRoleKey });

    const known = [...KNOWN_EVENTS];
    const extra = [...statusResult.map.keys()]
      .filter((name) => !known.includes(name) && name !== "Sin evento")
      .sort((a, b) => a.localeCompare(b, "es"));
    const orderedEvents = [...known, ...extra];

    return res.status(200).json({
      ok: true,
      eventos: orderedEvents.map((eventName) => ({
        evento: eventName,
        activo: resolveEventActive({
          eventName,
          statusMap: statusResult.map
        })
      })),
      status_available: statusResult.available,
      warning: statusResult.warning || ""
    });
  } catch (error) {
    return res.status(200).json({
      ok: true,
      eventos: buildDefaultEvents(),
      status_available: false,
      warning:
        error instanceof Error
          ? error.message
          : "No se pudo leer el estado de encuentros. Se usan activos por defecto."
    });
  }
};
