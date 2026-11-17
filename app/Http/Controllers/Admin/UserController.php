<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role as RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->with('roles:id,name')
            ->when($request->string('search')->trim()->value(), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);

        return view('admin.users.create', [
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $roleId = (int) $data['role_id'];
        unset($data['role_id']);

        $user = User::create($data);
        $user->syncRoles([
            Role::query()->where('guard_name', 'web')->findOrFail($roleId)->name,
        ]);

        return to_route('admin.users.index')->with('success', __('admin.flash.user_created'));
    }

    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('admin.users.edit', [
            'user' => $user->load('roles:id,name'),
            'roles' => Role::query()->where('guard_name', 'web')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        $roleId = (int) $data['role_id'];
        unset($data['role_id']);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $role = Role::query()->where('guard_name', 'web')->findOrFail($roleId);

        $this->ensureNotRemovingLastSuperAdminRole($user, $role);

        $user->update($data);
        $user->syncRoles([$role->name]);

        return to_route('admin.users.index')->with('success', __('admin.flash.user_updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        if ($user->is(auth()->user())) {
            throw ValidationException::withMessages([
                'user' => __('admin.validation.cannot_delete_yourself'),
            ]);
        }

        if ($user->hasRole(RoleEnum::SuperAdmin) && User::role(RoleEnum::SuperAdmin->value)->count() <= 1) {
            throw ValidationException::withMessages([
                'user' => __('admin.validation.cannot_delete_last_super_admin'),
            ]);
        }

        $user->delete();

        return to_route('admin.users.index')->with('success', __('admin.flash.user_deleted'));
    }

    private function ensureNotRemovingLastSuperAdminRole(User $user, Role $newRole): void
    {
        if (! $user->hasRole(RoleEnum::SuperAdmin)) {
            return;
        }

        if ($newRole->name === RoleEnum::SuperAdmin->value) {
            return;
        }

        if (User::role(RoleEnum::SuperAdmin->value)->count() <= 1) {
            throw ValidationException::withMessages([
                'role_id' => __('admin.validation.cannot_remove_last_super_admin_role'),
            ]);
        }
    }
}
