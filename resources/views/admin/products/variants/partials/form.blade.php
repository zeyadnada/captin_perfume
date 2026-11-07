<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label" for="volume">Volume <span class="text-danger">*</span></label>
            <input class="au-input @error('volume') is-invalid @enderror" id="volume" name="volume" type="number" min="0.01" step="0.01" value="{{ old('volume', $variant->volume) }}" required>
            @error('volume') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="volume_unit">Unit <span class="text-danger">*</span></label>
            <select class="au-input @error('volume_unit') is-invalid @enderror" id="volume_unit" name="volume_unit" required>
                @foreach (['ml' => 'Milliliters (ml)', 'oz' => 'Fluid ounces (oz)'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('volume_unit', $variant->volume_unit) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('volume_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label" for="sku">SKU</label>
            <input class="au-input @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $variant->sku) }}">
            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
            <input class="au-input @error('price') is-invalid @enderror" id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $variant->price) }}" required>
            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label" for="compare_at_price">Compare-at price</label>
            <input class="au-input @error('compare_at_price') is-invalid @enderror" id="compare_at_price" name="compare_at_price" type="number" min="0" step="0.01" value="{{ old('compare_at_price', $variant->compare_at_price) }}">
            @error('compare_at_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="form-check mb-4">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $variant->is_active))>
        <label class="form-check-label" for="is_active">Active</label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-danger">Cancel</a>
    </div>
</form>
