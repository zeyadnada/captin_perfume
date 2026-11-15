<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label" for="name">{{ __('admin.common.name') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $brand->name) }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="slug">{{ __('admin.common.slug') }} <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $brand->slug) }}" required>
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="description">{{ __('admin.common.description') }}</label>
        <textarea class="au-input @error('description') is-invalid @enderror" id="description" name="description" rows="8" style="min-height: 180px; resize: vertical;">{{ old('description', $brand->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="logo">{{ __('admin.brands.logo') }}</label>
        @if ($brand->logo)
            <div class="mb-2">
                <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}" width="96" height="96" style="object-fit: cover;">
            </div>
        @endif
        <input class="au-input @error('logo') is-invalid @enderror" id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp">
        @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-4">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $brand->is_active))>
        <label class="form-check-label" for="is_active">{{ __('admin.common.active') }}</label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.brands.index') }}" class="btn btn-danger">{{ __('admin.common.cancel') }}</a>
    </div>
</form>