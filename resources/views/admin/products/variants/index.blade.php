@extends('layouts.admin', ['title' => "{$product->name} Variants"])

@section('content')
    <x-page-header :title="$product->name.' Variants'" subtitle="Manage sellable sizes and prices for this perfume.">
        <x-slot:actions>
            <a href="{{ route('admin.products.variants.create', $product) }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add variant
            </a>
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
            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Variant</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($variants as $variant)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td><strong>{{ $variant->display_name }}</strong></td>
                                <td>{{ $variant->sku ?: 'Not assigned' }}</td>
                                <td>{{ number_format((float) $variant->price, 2) }}</td>
                                <td>
                                    <span class="{{ $variant->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $variant->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="btn btn-warning btn-sm d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" aria-label="Edit {{ $variant->display_name }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm delete-variant-trigger d-inline-flex align-items-center justify-content-center p-0" style="width: 34px; height: 34px;" type="button" data-variant-name="{{ $variant->display_name }}" data-delete-url="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" aria-label="Delete {{ $variant->display_name }}">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No variants found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $variants->links() }}</div>
        </div>
    </div>

    <form id="delete-variant-form" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-variant-trigger').forEach(button => {
                button.addEventListener('click', async () => {
                    const { isConfirmed } = await Swal.fire({
                        title: 'Delete variant?',
                        text: `Delete "${button.dataset.variantName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Delete',
                        confirmButtonColor: '#dc3545'
                    });

                    if (isConfirmed) {
                        document.getElementById('delete-variant-form').action = button.dataset.deleteUrl;
                        document.getElementById('delete-variant-form').submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
