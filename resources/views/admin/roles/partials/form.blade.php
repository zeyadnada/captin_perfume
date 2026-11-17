<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label" for="name">{{ __('admin.common.name') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <input
            class="au-input @error('name') is-invalid @enderror"
            id="name"
            name="name"
            value="{{ old('name', $role->name) }}"
            @disabled(! empty($isSystemRole))
            required
        >
        @if (! empty($isSystemRole))
            <input type="hidden" name="name" value="{{ $role->name }}">
            <div class="form-text">{{ __('admin.roles.system_role_locked') }}</div>
        @endif
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">{{ __('admin.roles.name_hint') }}</div>
    </div>

    <fieldset class="mb-4">
        <legend class="form-label fs-6 mb-3">{{ __('admin.roles.permissions') }}</legend>
        @error('permissions') <div class="text-danger small mb-2">{{ $message }}</div> @enderror

        @php
            $selected = old('permissions', $role->relationLoaded('permissions') ? $role->permissions->pluck('name')->all() : []);
        @endphp

        @foreach ($permissionGroups as $module => $permissions)
            <div class="mb-3 p-3 rounded border bg-light">
                <div class="fw-semibold mb-2">{{ __('admin.roles.modules.'.$module) }}</div>
                <div class="row g-2">
                    @foreach ($permissions as $permission)
                        <div class="col-md-6 col-lg-4">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    id="permission-{{ str_replace('.', '-', $permission->value) }}"
                                    value="{{ $permission->value }}"
                                    @checked(in_array($permission->value, $selected, true))
                                >
                                <label class="form-check-label" for="permission-{{ str_replace('.', '-', $permission->value) }}">
                                    <code>{{ $permission->value }}</code>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </fieldset>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-danger">{{ __('admin.common.cancel') }}</a>
    </div>
</form>
