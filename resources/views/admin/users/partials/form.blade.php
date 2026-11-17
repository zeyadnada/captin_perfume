<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label" for="name">{{ __('admin.common.name') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="email">{{ __('admin.auth.email') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="phone">{{ __('admin.users.phone') }} <span class="text-muted">{{ __('admin.common.optional') }}</span></label>
        <input class="au-input @error('phone') is-invalid @enderror" id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" maxlength="30">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="password">
            {{ __('admin.auth.password') }}
            @if ($method === 'POST')
                <span class="text-danger" aria-hidden="true">*</span>
            @else
                <span class="text-muted">{{ __('admin.common.optional') }}</span>
            @endif
        </label>
        <input class="au-input @error('password') is-invalid @enderror" id="password" name="password" type="password" @if ($method === 'POST') required @endif autocomplete="new-password">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        @if ($method !== 'POST')
            <div class="form-text">{{ __('admin.users.password_hint') }}</div>
        @endif
    </div>

    <div class="mb-3">
        <label class="form-label" for="password_confirmation">{{ __('admin.users.password_confirmation') }}</label>
        <input class="au-input" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
    </div>

    <div class="mb-4">
        <label class="form-label" for="role_id">{{ __('admin.users.role') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <select class="au-input @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
            <option value="">{{ __('admin.users.select_role') }}</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected((string) old('role_id', $user->roles->first()?->id) === (string) $role->id)>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>
        @error('role_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-danger">{{ __('admin.common.cancel') }}</a>
    </div>
</form>
