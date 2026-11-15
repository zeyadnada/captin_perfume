# Phase-by-Phase Learning Guide (Phases 1–4)

Study guide based on [`PROJECT_STATUS.md`](PROJECT_STATUS.md) and what exists in the app today.

---

## Phase 1 — Project Foundation

**Tasks:** Inspect Laravel app, MySQL config, CoolAdmin assets under `public/cooladmin`, admin/auth Blade layouts, dashboard routes, app name/timezone.

**Authentication (done):** Session login/logout, remember me, protect admin routes, CoolAdmin login page, seeded admin user.

**Still pending:** Spatie Permission, Policies/Gates.

### Topics

- **Blade layouts / partials** — Shared HTML shell (`layouts/admin`, sidebar, header) so every page reuses one design.
- **Public assets** — CoolAdmin CSS/JS/fonts served from `public/`, referenced with `asset()`.
- **Named routes + route groups** — `prefix('admin')->name('admin.')` keeps URLs and names organized.
- **Invokable controllers** — Single-action controllers (dashboard) with `__invoke`.
- **Session authentication** — `web` guard stores the logged-in user id in the session.
- **`auth` / `guest` middleware** — Guests cannot open admin pages; logged-in users skip the login form.
- **Session regenerate on login** — Prevents session fixation after `Auth::attempt`.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `route('name')` | Route name (+ params) | URL string for that route |
| `view('admin.x', $data)` | View name + array | Renders Blade HTML |
| `asset('cooladmin/...')` | Path under `public/` | Public URL to CSS/JS/images |
| `config('app.name')` | Config key | Value from `config/*.php` |
| `Auth::attempt($credentials, $remember)` | email/password array + remember bool | `true` if login succeeds; starts session |
| `$request->session()->regenerate()` | — | New session id after login |
| `Auth::logout()` | — | Clears authenticated user from session |
| `$this->actingAs($user)` | User model | Fakes login in tests |
| `redirectGuestsTo(fn () => route('login'))` | Closure returning URL | Where `auth` middleware sends guests |

---

## Phase 2 — Brands

**Tasks:** Migration, model, factory, Form Requests, CRUD, search/filter/pagination, logo upload, tests.

### Topics

- **Migrations** — Define table columns once; `php artisan migrate` applies them.
- **Eloquent models + `$fillable` / `#[Fillable]`** — Whitelist mass-assignable attributes.
- **Soft deletes** — `deleted_at` instead of hard delete; rows hidden by default.
- **Form Request classes** — Validation lives outside the controller (`StoreBrandRequest`, etc.).
- **`prepareForValidation()`** — Normalize input (e.g. slug) before rules run.
- **File uploads + Storage** — Store logo on `public` disk; delete old file on replace.
- **Query scopes** — e.g. `scopeActive` → `$query->active()`.
- **Feature tests + factories** — Simulate HTTP requests; seed models easily.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `Brand::create($attrs)` | Array of attributes | New saved `Brand` model |
| `$brand->update($attrs)` | Array | Updates row; returns bool |
| `$brand->delete()` | — | Soft-deletes (sets `deleted_at`) |
| `$request->validated()` | Optional field key | Validated data array (or one value) |
| `Str::slug($text)` | String | URL-safe slug string |
| `filled($value)` | Mixed | `true` if not empty |
| `$file->store($path, 'public')` | Folder + disk | Relative path string of stored file |
| `Storage::disk('public')->delete($path)` | File path | Deletes file from disk |
| `paginate(15)` | Per-page int | Length-aware paginator |
| `withQueryString()` | — | Keeps `?search=&status=` on page links |
| `to_route('admin.brands.index')` | Route name | Redirect response to that route |
| `->with('success', $msg)` | Flash key + message | Stores flash in session for next request |
| `Rule::unique(...)->ignore($id)` | Table/column + id | Unique rule that allows current row on update |
| `$model->getKey()` | — | Primary key value (usually `id`) — safer than hardcoding `->id` |

---

## Phase 2 — Categories

**Tasks:** Hierarchy (`parent_id`), CRUD, search/filter, cycle prevention, views, tests.

### Topics

- **Self-referencing relationships** — Category `belongsTo` parent / `hasMany` children.
- **Eager loading (`with`)** — Avoid N+1 when listing parent names.
- **Business rule validation** — Not only Form Request rules; controller logic blocks parent = descendant.
- **`ValidationException`** — Turn a domain error into a normal validation error (redirect + errors bag).
- **`whereKeyNot`** — Exclude current category from parent dropdown.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `->with('parent:id,name')` | Relation (+ columns) | Eager-loads parent; returns query/collection with relation set |
| `when($cond, $callback)` | Condition + closure | Applies `where` only if condition true |
| `whereKeyNot($id)` | Primary key | Excludes that model from query |
| `pluck('id')` | Column | Collection of that column’s values |
| `ValidationException::withMessages([...])` | Field => message map | Throws; Laravel redirects back with errors |
| `$category->children()->update([...])` | Attributes | Mass-updates related rows (orphan children on delete) |

---

## Phase 2 — Products

**Tasks:** Product table with brand/category FKs, CRUD, filters, show page, soft deletes, tests.

### Topics

- **Foreign keys + `belongsTo` / `hasMany`** — Product → Brand, Category; Brand/Category → products.
- **Route model binding** — `edit(Product $product)` loads model from `{product}` or 404.
- **Eager loading multiple relations** — `with(['brand:id,name', 'category:id,name'])`.
- **Unique slug/SKU with ignore on update** — `Rule::unique()->ignore($product)`.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `$this->belongsTo(Brand::class)` | Related model class | `BelongsTo` relation object |
| `$this->hasMany(ProductVariant::class)` | Related model class | `HasMany` relation object |
| `$product->load('variants')` | Relations | Loads relations onto existing model; returns model |
| `Rule::unique('products', 'slug')->ignore($product)` | — | Unique except this product’s row |

---

## Phase 2 — Product Variants

**Tasks:** Nested routes under product, volume/unit/price, Form Requests, duplicate volume validation, show-page preview.

### Topics

- **Nested resource routes** — `products.variants` → URLs like `/admin/products/{product}/variants`.
- **Ownership checks** — `abort_unless($variant->product_id === $product->getKey(), 404)` so URL can’t mix product A + variant of B.
- **Conditional unique rules** — Same volume+unit only once per product (`Rule::unique(...)->where(...)`).
- **Accessors** — `getDisplayNameAttribute` → `$variant->display_name`.
- **Casts** — decimals/booleans cast automatically.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `abort_unless($condition, 404)` | Bool + status | Continues if true; aborts HTTP error if false |
| `Rule::in(['ml', 'oz'])` | Allowed list | Validation rule |
| `Rule::unique(...)->where(fn)` | Extra query constraints | Scoped uniqueness |
| `$variant->getKey()` | — | Variant’s primary key |

---

## Phase 2 — Product Images

**Tasks:** Gallery table, multi-upload, sort order, delete DB row + file, nested routes.

### Topics

- **Multi-file upload** — `images[]` validated in Form Request; loop + `store()`.
- **Keeping disk + DB in sync** — Delete `Storage` file when deleting image row.
- **Ordered `hasMany`** — Images ordered by `sort_order`.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `$image->store("products/{$id}", 'public')` | Path + disk | Stored relative path |
| `Storage::disk('public')->delete($path)` | Path | Removes physical file |
| `$product->images()->create([...])` | Attributes | Creates related `ProductImage` and saves |

---

## Phase 3 — Stock / Inventory

**Tasks:** One stock row per variant (physical, reserved, available, low-stock threshold); inventory index + settings; no direct physical edit from settings anymore (movements own that).

### Topics

- **One-to-one (`hasOne` / `belongsTo`)** — Variant ↔ `ProductVariantStock`.
- **Database transactions** — Multiple writes succeed or fail together.
- **`lockForUpdate()`** — Row-level lock inside a transaction (prevents race conditions if two admins update stock at once).
- **`fill()` vs assigning FKs** — Mass-assign safe fields; set `product_variant_id` explicitly when creating.
- **Derived values** — `availableQuantity()` = physical − reserved (computed, not always a DB column write).
- **`whereHas`** — Filter variants whose product is active.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `DB::transaction(function () { ... })` | Closure | Runs closure in a DB transaction; commits on success, rolls back on exception; returns closure’s return value |
| `->lockForUpdate()` | — | `SELECT ... FOR UPDATE` on the query; returns same query builder |
| `findOrFail($id)` | Primary key | Model, or throws `ModelNotFoundException` (404) |
| `$stock->fill([...])` | Attribute array | Sets attributes on model **in memory**; does **not** save; returns `$stock` |
| `$stock->save()` | — | INSERT or UPDATE; returns bool |
| `$stock->exists` | — (property) | `true` if model already has a DB row |
| `$variant->stock()->first()` | — | Related stock model or `null` |
| `whereHas('product', fn ($q) => ...)` | Relation + constraint | Only parents that have matching related rows |

**Why `getKey()` here?**  
`findOrFail($variant->getKey())` and `$stock->product_variant_id = $variant->getKey()` use the real primary key without assuming the column is named `id`.

**Why `fill()`?**  
Sets `reserved_quantity` / `low_stock_threshold` in one call respecting fillable; then `save()` persists. FK is set separately when the row is new.

---

## Phase 3 — Stock Movements

**Tasks:** Append-only ledger (`in`/`out`), atomic stock change + movement row, outgoing cannot eat reserved qty, history UI, opening-balance migration seed.

### Topics

- **Ledger / audit pattern** — Don’t silently change stock; record every change.
- **Same transaction for balance + movement** — If movement insert fails, stock update rolls back.
- **`withTrashed()` on nested eager load** — History still shows soft-deleted variants/products.
- **`Rule::exists(...)->where(...)`** — Variant must exist and be active (and product active).

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `$stock->movements()->create([...])` | Attributes | Creates movement linked to this stock; returns model |
| `->with(['stock.variant' => fn ($q) => $q->withTrashed()])` | Nested relations + constraints | Eager-loads including soft-deleted |
| `latest('id')` | Column | Orders newest first |
| `Rule::exists('product_variants', 'id')->where(...)` | Table + extra where | Exists rule with conditions |

---

## Phase 4 — Localization (EN/AR)

**Tasks:** Session locale, `SetLocale` middleware, switch route, `lang/en` + `lang/ar`, `__()` everywhere, RTL CSS, language switcher, tests.

### Topics

- **Session as user preference store** — Remember `locale` across requests.
- **Web-group (“global”) middleware** — Registered in [`bootstrap/app.php`](bootstrap/app.php) via `$middleware->web(append: ...)`, not per-route in `web.php`.
- **Translation files + `__()`** — UI strings live in `lang/{locale}/*.php`.
- **Config-driven locales** — `available_locales` holds label + `dir` (ltr/rtl).
- **RTL front-end** — `dir="rtl"`, Bootstrap RTL, `rtl.css`.
- **Route `whereIn`** — Constrain `{locale}` to allowed codes.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `$request->session()->put('locale', $locale)` | Key + value | Stores in session; returns void |
| `$request->session()->get('locale', $default)` | Key + default | Session value or default |
| `App::setLocale($locale)` | Locale string | Sets active locale for `__()` this request |
| `__('admin.nav.brands')` | Key (+ replace + locale) | Translated string |
| `abort_unless(in_array(...), 404)` | Condition + status | Guard invalid locale in controller |
| `config('app.available_locales')` | Key | Locale map from config |
| `back()` | — | Redirect to previous URL |
| `array_keys($array)` | Associative array | List of keys (`['en','ar']`) |

---

## Quick “when do I use this?” map

```mermaid
flowchart TD
    formSubmit[Admin_form_submit] --> formRequest[Form_Request_validation]
    formRequest --> controller[Controller]
    controller --> simpleCrud[create_update_delete]
    controller --> businessRule[ValidationException_domain_rules]
    controller --> inventoryWrite[DB_transaction_plus_lockForUpdate]
    inventoryWrite --> fillSave[fill_and_save_or_relation_create]
    everyRequest[Every_web_request] --> setLocale[SetLocale_middleware]
    setLocale --> underscore["__()_translations"]
```

---

## Suggested study order (if re-reading code)

1. Auth login/logout + `Auth::attempt` + `auth`/`guest` middleware + `actingAs`
2. Brands CRUD + Form Request + `Str::slug` + Storage
3. Categories hierarchy + `ValidationException` cycle check
4. Nested variants/images + `abort_unless` + `getKey()`
5. Inventory `DB::transaction` + `lockForUpdate` + `fill()`
6. Stock movements ledger inside the same transaction
7. Locale middleware vs switch route

**Not used yet (later phases / pending Phase 1):** Policies, Gates, Spatie roles, Events/Listeners, Jobs/Queues, API Sanctum, PHP Enums classes (volume unit is still `Rule::in`, not an Enum).
