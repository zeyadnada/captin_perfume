<?php

namespace App\Http\Requests\Role;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('role')) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('name')) {
            $this->merge([
                'name' => strtolower(trim((string) $this->input('name'))),
            ]);
        }
    }

    public function rules(): array
    {
        $role = $this->route('role');
        $roleId = $role?->getKey();
        $isSystemRole = $role && $role->name === RoleEnum::SuperAdmin->value;

        return [
            'name' => [
                $isSystemRole ? 'sometimes' : 'required',
                'string',
                'max:125',
                'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('roles', 'name')->where('guard_name', 'web')->ignore($roleId),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(PermissionEnum::values())],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $role = $this->route('role');

            if ($role && $role->name === RoleEnum::SuperAdmin->value && $this->filled('name') && $this->input('name') !== RoleEnum::SuperAdmin->value) {
                $validator->errors()->add('name', __('admin.validation.cannot_rename_super_admin_role'));
            }
        });
    }
}
