const DEFAULT_SUPABASE_PAGE_SIZE = 1000;

async function fetchSupabaseRows({
  endpoint,
  headers,
  pageSize = DEFAULT_SUPABASE_PAGE_SIZE,
  select
}) {
  const baseEndpoint = endpoint instanceof URL ? endpoint : new URL(String(endpoint));
  const effectivePageSize = Number.isFinite(Number(pageSize)) && Number(pageSize) > 0
    ? Number(pageSize)
    : DEFAULT_SUPABASE_PAGE_SIZE;
  const rows = [];

  for (let offset = 0; ; offset += effectivePageSize) {
    const pageEndpoint = new URL(baseEndpoint.toString());

    if (typeof select === "string" && select.trim()) {
      pageEndpoint.searchParams.set("select", select);
    }

    pageEndpoint.searchParams.set("limit", String(effectivePageSize));
    pageEndpoint.searchParams.set("offset", String(offset));

    const response = await fetch(pageEndpoint.toString(), {
      method: "GET",
      headers
    });

    if (!response.ok) {
      const detail = await response.text().catch(() => "");
      return {
        ok: false,
        status: response.status,
        detail,
        rows
      };
    }

    const pageRows = await response.json().catch(() => []);
    const list = Array.isArray(pageRows) ? pageRows : [];
    rows.push(...list);

    if (list.length < effectivePageSize) {
      return {
        ok: true,
        rows
      };
    }
  }
}

module.exports = {
  DEFAULT_SUPABASE_PAGE_SIZE,
  fetchSupabaseRows
};
