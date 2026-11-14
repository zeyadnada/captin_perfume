<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="brand_id">{{ __('admin.common.brand') }} <span class="text-danger">*</span></label>
            <select class="au-input @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id" required>
                <option value="">{{ __('admin.products.select_brand') }}</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected((string) old('brand_id', $product->brand_id) === (string) $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="category_id">{{ __('admin.common.category') }} <span class="text-danger">*</span></label>
            <select class="au-input @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="">{{ __('admin.products.select_category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="name">{{ __('admin.common.name') }} <span class="text-danger">*</span></label>
            <input class="au-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label" for="slug">{{ __('admin.common.slug') }} <span class="text-danger">*</span></label>
            <input class="au-input @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" required>
            @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label" for="sku">{{ __('admin.common.sku') }}</label>
            <input class="au-input @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label" for="short_description">{{ __('admin.products.short_description') }}</label>
        <input class="au-input @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="255">
        @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="description">{{ __('admin.common.description') }}</label>
        <textarea class="au-input @error('description') is-invalid @enderror" id="description" name="description" rows="8" style="min-height: 180px; resize: vertical;">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-4">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->is_active))>
        <label class="form-check-label" for="is_active">{{ __('admin.common.active') }}</label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-danger">{{ __('admin.common.cancel') }}</a>
    </div>
</form>