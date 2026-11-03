<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="mb-3">
        <label class="form-label" for="parent_id">Parent category</label>
        <select class="au-input @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
            <option value="">None (top-level category)</option>
            @foreach ($parents as $parent)
                <option value="{{ $parent->id }}" @selected((string) old('parent_id', $category->parent_id) === (string) $parent->id)>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="name">Name <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="slug">Slug <span class="text-danger" aria-hidden="true">*</span></label>
        <input class="au-input @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required>
        @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label" for="description">Description</label>
        <textarea class="au-input @error('description') is-invalid @enderror" id="description" name="description" rows="8" style="min-height: 180px; resize: vertical;">{{ old('description', $category->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-check mb-4">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $category->is_active))>
        <label class="form-check-label" for="is_active">Active</label>
    </div>

    <div class="d-flex align-items-center gap-2 pt-3 border-top">
        <button class="btn btn-primary" type="submit">{{ $submitLabel }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Cancel</a>
    </div>
</form>
