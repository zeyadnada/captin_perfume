# Project Status

## Current Position

- Roadmap phase: Phase 3 - Inventory Management
- Current module: Stock movements
- Status: Implemented; runtime verification pending
- Next module: Inventory adjustments

## Completed Work

### Phase 1 - Project Foundation

- Laravel 13 project inspected and configured.
- PHP 8.3 application and MySQL application database configured.
- CoolAdmin template assets integrated under `public/cooladmin`.
- Admin and authentication Blade layout foundations created.
- Admin dashboard route and reusable admin layout added.
- Root dashboard route added.
- Application name and timezone configured.

Authentication and Spatie permissions remain pending.

### Phase 2 - Brands Management

- Added the `brands` migration with:
  - Name
  - Unique slug
  - Description
  - Logo
  - Active status
  - Soft deletion
- Added the `Brand` model and factory.
- Added create and update Form Requests.
- Added brand CRUD controller logic.
- Added search, status filtering, and pagination.
- Added logo upload and replacement handling.
- Added admin index, create, and edit Blade views.
- Added brand resource routes and sidebar navigation.
- Added Brand feature tests.

Verification:

- Brands and homepage feature tests pass: 5 tests, 18 assertions.
- Laravel Pint formatting passes.

### Phase 2 - Categories Management

- Added hierarchical category data, validation, CRUD, search, status filtering, and pagination.
- Added parent relationships and prevention of descendant cycles.
- Added category admin views and sidebar navigation.
- Added Category feature tests.

Verification:

- Category management tests pass: 5 tests, 14 assertions.
- Laravel Pint formatting passes.

### Phase 2 - Product Management

- Added the `products` migration with brand and category relationships, naming/slug/SKU fields, descriptions, pricing, active status, and soft deletion.
- Added the `Product` model, factory, and Brand/Category product relationships.
- Added create and update Form Requests with slug/SKU normalization, unique constraints, and pricing validation.
- Added product CRUD, search, brand/category/status filtering, and pagination.
- Added CoolAdmin product index, show, create, and edit Blade views and sidebar navigation.
- Added Product feature tests.

### Phase 2 - Product Variants

- Added the `product_variants` migration with structured volume/unit, unique SKU, price, compare-at price, active status, and soft deletion.
- Added the `ProductVariant` model, factory, and Product relationship.
- Added nested product-variant routes and admin CRUD controller.
- Added Store/Update Form Requests with duplicate volume/unit and compare-at-price validation.
- Added CoolAdmin variant index, create, edit, and Product show-page preview views.
- Added variant counts to the Product index and Product feature integration.
- Added Product Variant feature tests.

Verification status:

- Runtime test and Pint results remain pending terminal verification.

### Phase 2 - Product Images

- Added the `product_images` migration with product relationship, public file path, and sort order.
- Added the `ProductImage` model, factory, and product gallery relationship.
- Added nested image routes and the admin gallery controller.
- Added upload and metadata Form Request validation.
- Added multi-image upload, ordering, deletion, and physical file cleanup.
- Added gallery preview and gallery management access to the Product show page.

Verification status:

- Product Image feature tests have been added.
- Runtime test and Pint results remain pending terminal verification.

### Phase 3 - Stock Management

- Added a one-to-one stock record for each product variant.
- Added physical quantity, reserved quantity, available quantity, and low-stock threshold handling.
- Added the inventory index and stock update pages using the CoolAdmin layout.
- Added inventory sidebar navigation.
- Added stock feature tests and route integrity validation.

### Phase 3 - Stock Movements

- Added an append-only movement ledger with incoming/outgoing type, quantity, reason, resulting balance, and optional source references.
- Existing nonzero stock balances are recorded as opening-balance movements when the ledger migration runs.
- Added admin movement history and movement recording pages.
- Stock balance changes and movement records are saved atomically; outgoing movements cannot use reserved stock.
- Removed direct physical quantity editing from inventory settings so on-hand changes must be recorded as movements.
- Added stock movement feature tests.

Verification status:

- Runtime test and Pint results remain pending terminal verification.

## Known Pending Items

- Authentication.
- Spatie Laravel Permission.
- Admin authorization policies and middleware.
- Remaining Phase 2 master-data modules.
