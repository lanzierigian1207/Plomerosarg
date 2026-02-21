create table if not exists public.encuentros_estado (
  encuentro text primary key,
  activo boolean not null default true,
  updated_at timestamptz not null default now()
);
