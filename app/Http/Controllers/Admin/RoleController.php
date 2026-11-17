<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::query()
            ->where('guard_name', 'web')
            ->withCount('permissions', 'users')
            ->when($request->string('search')->trim()->value(), function ($query, string $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $this->authorize('create', Role::class);

        return view('admin.roles.create', [
            'permissionGroups' => PermissionEnum::groupedByModule(),
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return to_route('admin.roles.index')->with('success', __('admin.flash.role_created'));
    }

    public function edit(Role $role): View
    {
        $this->authorize('update', $role);

        return view('admin.roles.edit', [
            'role' => $role->load('permissions:id,name'),
            'permissionGroups' => PermissionEnum::groupedByModule(),
            'isSystemRole' => $role->name === RoleEnum::SuperAdmin->value,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $data = $request->validated();

        if ($role->name !== RoleEnum::SuperAdmin->value) {
            $role->name = $data['name'];
            $role->save();
        }

        $role->syncPermissions($data['permissions'] ?? []);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return to_route('admin.roles.index')->with('success', __('admin.flash.role_updated'));
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        if ($role->name === RoleEnum::SuperAdmin->value) {
            throw ValidationException::withMessages([
                'role' => __('admin.validation.cannot_delete_super_admin_role'),
            ]);
        }

        $role->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return to_route('admin.roles.index')->with('success', __('admin.flash.role_deleted'));
    }
}
