<?php

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lists categories in the admin panel', function () {
    Category::factory()->create(['name' => 'Floral']);

    $this->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Floral');
});

it('creates a category with a parent', function () {
    $parent = Category::factory()->create(['name' => 'Women']);

    $this->post(route('admin.categories.store'), [
        'parent_id' => $parent->id,
        'name' => 'Floral',
        'slug' => '',
        'description' => 'Flowery perfumes.',
        'is_active' => 1,
    ])->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', [
        'name' => 'Floral',
        'slug' => 'floral',
        'parent_id' => $parent->id,
    ]);
});

it('updates a category', function () {
    $category = Category::factory()->create();

    $this->put(route('admin.categories.update', $category), [
        'parent_id' => null,
        'name' => 'Oriental',
        'slug' => 'oriental',
        'description' => null,
        'is_active' => 1,
    ])->assertRedirect(route('admin.categories.index'));

    expect($category->fresh()->name)->toBe('Oriental');
});

it('rejects a category parent cycle', function () {
    $parent = Category::factory()->create();
    $child = Category::factory()->create(['parent_id' => $parent->id]);

    $this->put(route('admin.categories.update', $parent), [
        'parent_id' => $child->id,
        'name' => $parent->name,
        'slug' => $parent->slug,
        'is_active' => 1,
    ])
        ->assertSessionHasErrors('parent_id');
});

it('soft deletes a category and promotes its children', function () {
    $parent = Category::factory()->create();
    $child = Category::factory()->create(['parent_id' => $parent->id]);

    $this->delete(route('admin.categories.destroy', $parent))
        ->assertRedirect(route('admin.categories.index'));

    $this->assertSoftDeleted('categories', ['id' => $parent->id]);
    expect($child->fresh()->parent_id)->toBeNull();
});
