-- ============================================================
-- MARCHÉ AUBEDE — Schéma de base de données Supabase
-- À coller entièrement dans SQL Editor > New query > Run
-- ============================================================

-- ---------- TABLE PRODUITS ----------
create table if not exists products (
  id text primary key,
  name text not null,
  category text not null,
  price integer not null,
  promo_price integer,
  stock integer default 0,
  type text default 'physique',
  featured boolean default false,
  description text,
  full_description text,
  specs jsonb default '[]',
  delivery text,
  warranty text,
  video text,
  images jsonb default '[]',
  created_at timestamptz default now()
);

-- ---------- TABLE AVIS CLIENTS ----------
create table if not exists reviews (
  id text primary key,
  name text not null,
  rating integer not null,
  product_id text,
  review_text text not null,
  created_at timestamptz default now()
);

-- ---------- TABLE CODES PROMO ----------
create table if not exists promos (
  code text primary key,
  type text not null,
  value integer not null,
  active boolean default true
);

-- ---------- TABLE COMMANDES (privée, admin uniquement) ----------
create table if not exists orders (
  id text primary key,
  order_date text,
  name text not null,
  phone text not null,
  items text,
  total integer default 0,
  payment_status text default 'En attente',
  delivery_status text default 'En attente',
  created_at timestamptz default now()
);

-- ---------- TABLE PARAMÈTRES (une seule ligne) ----------
create table if not exists settings (
  id integer primary key default 1,
  site_title text,
  tagline text,
  mtn_number text,
  moov_number text,
  celtiis_number text,
  wa_mtn text,
  wa_moov text,
  wa_celtiis text
);

-- ============================================================
-- SÉCURITÉ (Row Level Security)
-- Lecture publique pour le catalogue / avis / promos / paramètres.
-- Écriture réservée à l'administrateur connecté (vous).
-- Les commandes restent entièrement privées (lecture + écriture admin uniquement).
-- ============================================================

alter table products enable row level security;
alter table reviews  enable row level security;
alter table promos   enable row level security;
alter table orders   enable row level security;
alter table settings enable row level security;

-- Produits : lecture publique, écriture admin
create policy "products_select_public" on products for select using (true);
create policy "products_insert_auth"   on products for insert with check (auth.role() = 'authenticated');
create policy "products_update_auth"   on products for update using (auth.role() = 'authenticated');
create policy "products_delete_auth"   on products for delete using (auth.role() = 'authenticated');

-- Avis : lecture publique, écriture admin
create policy "reviews_select_public" on reviews for select using (true);
create policy "reviews_insert_auth"   on reviews for insert with check (auth.role() = 'authenticated');
create policy "reviews_update_auth"   on reviews for update using (auth.role() = 'authenticated');
create policy "reviews_delete_auth"   on reviews for delete using (auth.role() = 'authenticated');

-- Promotions : lecture publique (nécessaire pour vérifier un code au paiement), écriture admin
create policy "promos_select_public" on promos for select using (true);
create policy "promos_insert_auth"   on promos for insert with check (auth.role() = 'authenticated');
create policy "promos_update_auth"   on promos for update using (auth.role() = 'authenticated');
create policy "promos_delete_auth"   on promos for delete using (auth.role() = 'authenticated');

-- Paramètres : lecture publique (numéros affichés en pied de page), écriture admin
create policy "settings_select_public" on settings for select using (true);
create policy "settings_insert_auth"   on settings for insert with check (auth.role() = 'authenticated');
create policy "settings_update_auth"   on settings for update using (auth.role() = 'authenticated');

-- Commandes : entièrement privées, admin uniquement (lecture ET écriture)
create policy "orders_all_auth" on orders for all
  using (auth.role() = 'authenticated')
  with check (auth.role() = 'authenticated');

-- Ligne de paramètres par défaut (vous la modifierez depuis l'administration)
insert into settings (id, site_title, tagline, mtn_number, moov_number, celtiis_number, wa_mtn, wa_moov, wa_celtiis)
values (1, 'MARCHÉ AUBEDE', 'Votre boutique en ligne qui vous facilite la vie — Bénin',
        '+229 01 67 92 92 69', '+229 01 64 63 84 29', '+229 01 49 09 82 89',
        '2290167929269', '2290164638429', '2290149098289')
on conflict (id) do nothing;
