const {
  KNOWN_EVENTS,
  fetchEventCatalog,
  fetchEventStatusMap,
  fetchCertificateStatusMap,
  getDefaultEventCatalog,
  resolveCatalogCertificateActive,
  resolveCatalogEventActive,
  resolveCertificateActive,
  resolveEventActive
} = require("./_encuentros");

function buildDefaultEvents() {
  const defaults = getDefaultEventCatalog();
  return (defaults.length > 0 ? defaults : KNOWN_EVENTS.map((evento) => ({ evento }))).map((item) => ({
    ...item,
    evento: item.evento || item.nombre,
    activo: true,
    certificado_activo: true
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
    const [catalogResult, statusResult, certificateStatusResult] = await Promise.all([
      fetchEventCatalog({ supabaseUrl, serviceRoleKey }),
      fetchEventStatusMap({ supabaseUrl, serviceRoleKey }),
      fetchCertificateStatusMap({ supabaseUrl, serviceRoleKey })
    ]);

    const catalogEvents = Array.isArray(catalogResult.eventos)
      ? catalogResult.eventos
      : [];
    const known = catalogEvents.length > 0
      ? catalogEvents.map((item) => item.evento)
      : [...KNOWN_EVENTS];
    const extra = [...statusResult.map.keys()]
      .filter((name) => !known.includes(name) && name !== "Sin evento")
      .sort((a, b) => a.localeCompare(b, "es"));
    const orderedEvents = [...known, ...extra];

    return res.status(200).json({
      ok: true,
      eventos: orderedEvents.map((eventName) => {
        const catalogItem = catalogEvents.find((item) => item.evento === eventName);
        return {
          ...(catalogItem || {}),
          evento: eventName,
          activo: catalogItem
            ? resolveCatalogEventActive(catalogItem, statusResult.map)
            : resolveEventActive({
                eventName,
                statusMap: statusResult.map
              }),
          certificado_activo: catalogItem
            ? resolveCatalogCertificateActive(catalogItem, certificateStatusResult.map)
            : resolveCertificateActive({
                eventName,
                certificateMap: certificateStatusResult.map
              })
        };
      }),
      catalog_available: catalogResult.available,
      status_available: statusResult.available,
      certificate_status_available: certificateStatusResult.available,
      warning: [catalogResult.warning, statusResult.warning].filter(Boolean).join(" "),
      certificate_warning: certificateStatusResult.warning || ""
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
