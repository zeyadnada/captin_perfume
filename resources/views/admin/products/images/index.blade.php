@extends('layouts.admin', ['title' => "{$product->name} Images"])

@section('content')
    <x-page-header :title="$product->name.' Images'" subtitle="Upload, order, and manage the gallery images for this product.">
        <x-slot:actions>
            <a href="{{ route('admin.products.show', $product) }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to product
            </a>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <h3 class="title-2 m-b-20">Upload images</h3>
            <form method="POST" action="{{ route('admin.products.images.store', $product) }}" enctype="multipart/form-data">
                @csrf
                <div class="d-flex align-items-center gap-2">
                    <div class="flex-grow-1" style="min-width: 0;">
                        <label for="images" class="form-label">Image files</label>
                        <input class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple required>
                        @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">JPG, PNG or WebP. Maximum 5 MB per image.</small>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="au-btn au-btn--green px-4 py-2" type="submit">
                            <i class="fa-solid fa-upload" aria-hidden="true"></i> Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse ($images as $image)
            <div class="col-md-6 col-lg-4 m-b-30">
                <div class="au-card h-100">
                    <div class="au-card-inner">
                        <div class="position-relative mb-3 text-center bg-light" style="height: 220px;">
                            <img src="{{ asset('storage/'.$image->path) }}" alt="{{ $product->name }}" class="h-100 w-100" style="object-fit: contain;">
                        </div>

                        <div class="d-flex align-items-end gap-2">
                            <form method="POST" action="{{ route('admin.products.images.update', [$product, $image]) }}" class="d-flex align-items-end gap-2 flex-grow-1">
                                @csrf
                                @method('PUT')
                                <div class="flex-grow-1">
                                    <label for="sort_order_{{ $image->id }}" class="form-label">Sort order</label>
                                    <input class="form-control" id="sort_order_{{ $image->id }}" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order', $image->sort_order) }}" required>
                                </div>
                                <button class="btn btn-warning btn-sm flex-shrink-0" type="submit">
                                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" onsubmit="return confirm('Delete this product image?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm flex-shrink-0" type="submit">
                                    <i class="fa-solid fa-trash" aria-hidden="true"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="au-card">
                    <div class="au-card-inner text-center py-5 text-muted">
                        No images have been uploaded for this product.
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
