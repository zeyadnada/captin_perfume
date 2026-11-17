# Phase-by-Phase Learning Guide (Phases 1–5)

Study guide based on [`PROJECT_STATUS.md`](PROJECT_STATUS.md) and what exists in the app today.

---

## Phase 1 — Project Foundation

**Tasks:** Inspect Laravel app, MySQL config, CoolAdmin assets under `public/cooladmin`, admin/auth Blade layouts, dashboard routes, app name/timezone.

**Authentication (done):** Session login/logout, remember me. Admin login lives at `/admin/login` (CoolAdmin). Customer login/register live on the MaleFashion storefront (Phase 5).

**Authorization (done):** Layered staff access — Spatie roles/permissions, `staff` middleware, Policies, Form Request + controller `authorize()`, sidebar `@can`, Users/Roles admin UI.

### How authorization works in this app

```mermaid
flowchart TD
  Request[HTTP request] --> AuthMw["auth middleware"]
  AuthMw --> StaffMw["staff middleware: user has at least one role"]
  StaffMw --> FormAuth["FormRequest authorize via Policy"]
  StaffMw --> CtrlAuth["Controller authorize for GET/destroy"]
  FormAuth --> Policy[Model Policy]
  CtrlAuth --> Policy
  Policy --> SpatieCheck["hasPermissionTo Permission enum"]
```

1. **`auth`** — must be logged in.
2. **`staff` (`EnsureUserIsStaff`)** — must have **at least one** Spatie role (customers later will have none → blocked from admin).
3. **Policies** — fine-grained: e.g. `BrandPolicy::create` checks `brands.create`.
4. **Wiring**
   - Form Requests: `$this->user()?->can('create', Brand::class)`
   - Controllers (index/create/edit/show/destroy): `$this->authorize(...)`
   - Dashboard: `$this->authorize(Permission::DashboardView->value)` (Spatie registers permission names with Gate)
5. **Sidebar** — `@can` / `can('viewAny', …)` hides links the user cannot use.

**Roles (seeded):**

| Role | Access |
|------|--------|
| `super_admin` | All permissions, including Administration (Users/Roles). Protected system role. Seeded user gets this. |
| `admin` | All permissions **except** `users.*` and `roles.*`. |

Each staff user has **exactly one role** in the UI (`role_id` select + `syncRoles([$name])`). Spatie still stores roles via pivot; we simply assign one.

**Permission catalog:** backed enum `App\Enums\Permission` → seeded in `RolePermissionSeeder`. Policies call `$user->hasPermissionTo(Permission::…)` (not `$user->can()` inside the policy) to avoid Gate recursion.

**Spatie `Role` model policy:** `Gate::policy(Role::class, RolePolicy::class)` in `AppServiceProvider` because `Spatie\Permission\Models\Role` is not under `App\Models`, so auto-discovery does not apply. `UserPolicy` auto-discovers normally.

**Why both `staff` and Policies?**  
`staff` = may enter the admin area. Policies = which modules/actions that staff member may use. Ready for limited roles without opening admin to every logged-in user.

### Topics

- **Blade layouts / partials** — Shared HTML shell (`layouts/admin`, sidebar, header) so every page reuses one design.
- **Public assets** — CoolAdmin CSS/JS/fonts served from `public/`, referenced with `asset()`.
- **Named routes + route groups** — `prefix('admin')->name('admin.')` keeps URLs and names organized.
- **Invokable controllers** — Single-action controllers (dashboard) with `__invoke`.
- **Session authentication** — `web` guard stores the logged-in user id in the session.
- **`auth` / `guest` middleware** — Guests cannot open admin pages; logged-in users skip the login form.
- **Session regenerate on login** — Prevents session fixation after `Auth::attempt`.
- **Spatie roles & permissions** — Roles group permissions; `$user->hasPermissionTo()` / `$user->can()` check access.
- **Laravel Policies** — Map abilities (`viewAny`, `create`, `update`, `delete`) to permission checks.
- **`AuthorizesRequests`** — On base `Controller` so `$this->authorize()` works.
- **Form Request `authorize()`** — Second gate after validation class is resolved (before `rules()` run for writes).
- **Blade `@can`** — UX only; real enforcement is middleware + Policies.

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
| `redirectGuestsTo(fn (Request $r) => ...)` | Closure returning URL | Where `auth` middleware sends guests (admin vs storefront paths) |
| `$user->assignRole` / `syncRoles` | Role name(s) | Attach role(s); `syncRoles` replaces the set |
| `$user->hasPermissionTo($permission)` | Permission enum/string | `true` if role grants that permission |
| `$user->can('create', Brand::class)` | Ability + model/class | Runs matching Policy method via Gate |
| `$this->authorize('update', $brand)` | Ability + model | Aborts 403 if Policy denies |
| `Gate::policy(Role::class, RolePolicy::class)` | Model class + Policy class | Manual Policy map for non-`App\Models` types |
| `@can('viewAny', Brand::class)` | Ability + model/class | Shows Blade block only if allowed |
| `actingAsAdmin()` / `actingAsAdminWithout(...)` | Optional Permission enums | Test helpers: seed roles, login as `super_admin` (optionally revoke perms) |

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

## Phase 5 — Customer Accounts (MaleFashion)

**Tasks:** Integrate MaleFashion storefront assets/layout, public home shell, split admin vs customer auth URLs, customer register/login/logout + account profile, EN/AR storefront strings, tests.

### How customer vs staff access works

```mermaid
flowchart TD
  Guest[Guest] -->|/login /register| CustomerAuth[Storefront auth]
  Guest -->|/admin/login| AdminAuth[CoolAdmin auth]
  CustomerAuth --> CheckStaff{"User::isStaff?"}
  CheckStaff -->|no roles| Account[account.show]
  CheckStaff -->|has role| AdminDash[admin.dashboard]
  AdminAuth --> MustStaff{"isStaff after login?"}
  MustStaff -->|yes| AdminDash
  MustStaff -->|no| Reject[logout plus validation error]
  CustomerUser[Customer on /admin] --> StaffMw[staff middleware 403]
```

1. **One `users` table** — Customers and staff share `App\Models\User`. No separate customers table.
2. **Customer = zero Spatie roles** — Registration creates a user and never calls `assignRole`.
3. **Staff = at least one role** — `User::isStaff()` / `staff` middleware; Policies still fine-tune modules.
4. **Split login URLs**
   - Storefront: `/login`, `/register`, `/logout`, `/account` (MaleFashion Blade).
   - Admin: `/admin/login`, `/admin/logout` (CoolAdmin).
5. **Smart redirects in [`bootstrap/app.php`](bootstrap/app.php)**
   - Guests hitting `admin*` → `admin.login`; otherwise → `login`.
   - Authenticated users hitting guest-only pages → staff to `admin.dashboard`, customers to `home`.
6. **Admin login rejects non-staff** — After `Auth::attempt`, if `! $user->isStaff()`, logout and throw `ValidationException` (customers cannot use the admin form).
7. **Customer login accepts staff** — Staff who use `/login` are redirected to `admin.dashboard` (convenient, not a second account).

### Topics

- **Second UI template in one Laravel app** — CoolAdmin for admin, MaleFashion for storefront; separate asset trees (`public/cooladmin`, `public/malefashion`) and Blade layouts.
- **Extracting a layout from a static HTML theme** — Copy CSS/JS/img/fonts; turn repeated header/footer into Blade partials; replace relative paths with `asset()`.
- **Public vs protected route groups** — Home is public; account requires `auth`; admin still `auth` + `staff`.
- **Registration without a role** — Creates a customer; admin area stays closed by design.
- **Optional password on profile update** — `nullable` + `confirmed` + `Password::defaults()`; only hash/save when filled.
- **`redirect()->intended(...)`** — After login, send user to the URL they originally tried (or a default).
- **Namespaced storefront code** — Controllers under `App\Http\Controllers\Storefront`, views under `resources/views/storefront`, lang under `storefront.*`.
- **Context-aware guest redirect** — Same `auth` middleware, different login page depending on path.

### Key functions

| Function | Takes | Returns / does |
|----------|-------|----------------|
| `$user->isStaff()` | — | `true` if user has any Spatie role |
| `Auth::login($user)` | User model | Logs in without password (after register) |
| `Auth::attempt($credentials, $remember)` | email/password + remember | Starts session if credentials match |
| `redirect()->intended($defaultUrl)` | Fallback URL | Goes to session “url.intended” or fallback |
| `Password::defaults()` | — | App-wide password rule object for validation |
| `Rule::unique('users', 'email')->ignore($user->id)` | — | Unique email except current user on account update |
| `$request->safe()->except(['password', ...])` | Keys to drop | Validated data without those keys |
| `$request->filled('password')` | Field name | `true` if password was submitted non-empty |
| `$request->is('admin', 'admin/*')` | Path patterns | `true` when current request path matches |
| `asset('malefashion/css/style.css')` | Path under `public/` | Public URL for storefront assets |
| `__('storefront.auth.login_title')` | Lang key | Translated storefront string |

**Why not a `customer` Spatie role?**  
Staff detection is “has any role.” Giving every shopper a `customer` role would make `staff` middleware treat them as admin-area users unless we rewrote the check. Zero roles = customer is the simplest rule and matches Phase 1 design.

**Why two logout routes?**  
Admin header posts to `admin.logout` (CoolAdmin redirect back to `/admin/login`). Storefront posts to `logout` (redirect to storefront `home`). Same session guard; different UX after logout.

---

## Quick “when do I use this?” map

```mermaid
flowchart TD
    formSubmit[Admin_form_submit] --> formRequest[Form_Request_authorize_then_validation]
    formRequest --> controller[Controller]
    controller --> simpleCrud[create_update_delete]
    controller --> businessRule[ValidationException_domain_rules]
    controller --> inventoryWrite[DB_transaction_plus_lockForUpdate]
    inventoryWrite --> fillSave[fill_and_save_or_relation_create]
    everyRequest[Every_web_request] --> setLocale[SetLocale_middleware]
    setLocale --> underscore["__()_translations"]
    adminRoute[Admin_route] --> authStaff["auth_plus_staff"]
    authStaff --> policies[Policy_checks]
    storefrontAuth[Storefront_login_register] --> customerUser[User_with_zero_roles]
    customerUser --> accountPage[account_show_update]
    customerUser -->|admin_URL| staffForbidden[staff_middleware_403]
```

---

## Suggested study order (if re-reading code)

1. Auth login/logout + `Auth::attempt` + `auth`/`guest` middleware + `actingAs`
2. Spatie seed (`RolePermissionSeeder`) + `Permission`/`Role` enums + `staff` middleware
3. One Policy (e.g. `BrandPolicy`) + Form Request `authorize()` + controller `$this->authorize()`
4. Users/Roles admin + single `role_id` + `super_admin` vs `admin` permission split
5. Brands CRUD + Form Request + `Str::slug` + Storage
6. Categories hierarchy + `ValidationException` cycle check
7. Nested variants/images + `abort_unless` + `getKey()`
8. Inventory `DB::transaction` + `lockForUpdate` + `fill()`
9. Stock movements ledger inside the same transaction
10. Locale middleware vs switch route
11. MaleFashion layout/partials + `public/malefashion` assets
12. Split admin/customer auth routes + `User::isStaff()` + `redirectGuestsTo` / `redirectUsersTo`
13. Storefront register/login/account Form Requests + optional password update

**Not used yet (later phases):** Events/Listeners, Jobs/Queues, API Sanctum, email verification, password reset emails. Volume unit is still `Rule::in` (not an Enum).
