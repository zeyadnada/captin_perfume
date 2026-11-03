<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()
            ->with('parent:id,name')
            ->when($request->string('search')->trim()->value(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->boolean('status')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'parents' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return to_route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => Category::query()->whereKeyNot($category->getKey())->active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $parentId = $request->validated('parent_id');
        $this->ensureParentIsNotDescendant($category, $parentId);
        $category->update($request->validated());

        return to_route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->children()->update(['parent_id' => null]);
        $category->delete();

        return to_route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    private function ensureParentIsNotDescendant(Category $category, ?int $parentId): void
    {
        if (! $parentId) {
            return;
        }

        $descendantIds = $category->children()->pluck('id');

        while ($descendantIds->isNotEmpty()) {
            if ($descendantIds->contains($parentId)) {
                throw ValidationException::withMessages(['parent_id' => 'A category cannot be moved under its own descendant.']);
            }

            $descendantIds = Category::query()->whereIn('parent_id', $descendantIds)->pluck('id');
        }
    }
}
