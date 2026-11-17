<?php

use App\Enums\Permission;
use App\Models\Brand;

it('allows a full admin to view brands', function () {
    actingAsAdmin();

    $this->get(route('admin.brands.index'))
        ->assertOk();
});

it('forbids viewing brands when brands.view is revoked', function () {
    actingAsAdminWithout(Permission::BrandsView);

    $this->get(route('admin.brands.index'))
        ->assertForbidden();
});

it('forbids deleting a brand when brands.delete is revoked', function () {
    actingAsAdminWithout(Permission::BrandsDelete);

    $brand = Brand::factory()->create();

    $this->delete(route('admin.brands.destroy', $brand))
        ->assertForbidden();

    expect($brand->fresh())->not->toBeNull();
});

it('forbids the dashboard when dashboard.view is revoked', function () {
    actingAsAdminWithout(Permission::DashboardView);

    $this->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('forbids creating a brand when brands.create is revoked', function () {
    actingAsAdminWithout(Permission::BrandsCreate);

    $this->post(route('admin.brands.store'), [
        'name' => 'Chanel',
        'slug' => 'chanel',
        'description' => null,
        'is_active' => 1,
    ])->assertForbidden();

    expect(Brand::query()->where('slug', 'chanel')->exists())->toBeFalse();
});
