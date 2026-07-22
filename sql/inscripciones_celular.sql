alter table public.inscripciones
  add column if not exists celular text;

comment on column public.inscripciones.celular is 'Numero de celular informado en la inscripcion.';
