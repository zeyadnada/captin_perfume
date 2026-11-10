@extends('layouts.admin', ['title' => "{$product->name} {$variant->display_name} Stock Settings"])

@section('content')
    <x-page-header title="Stock Settings" subtitle="{{ $product->name }} - {{ $variant->display_name }}">
        <x-slot:actions>
            <a href="{{ route('admin.inventory.index') }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to inventory
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <div class="au-card">
                <div class="au-card-inner">
                    <form method="POST" action="{{ route('admin.inventory.update', [$product, $variant]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="quantity">Physical quantity</label>
                                <input id="quantity" type="number" class="form-control" value="{{ $variant->stock?->quantity ?? 0 }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="reserved_quantity">Reserved quantity</label>
                                <input id="reserved_quantity" name="reserved_quantity" type="number" min="0" class="form-control @error('reserved_quantity') is-invalid @enderror" value="{{ old('reserved_quantity', $variant->stock?->reserved_quantity ?? 0) }}" required>
                                @error('reserved_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="low_stock_threshold">Low-stock threshold</label>
                                <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0" class="form-control @error('low_stock_threshold') is-invalid @enderror" value="{{ old('low_stock_threshold', $variant->stock?->low_stock_threshold ?? 0) }}" required>
                                @error('low_stock_threshold')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-light border">
                            Physical quantity can only be changed by recording an incoming or outgoing stock movement.
                        </div>

                        <button class="au-btn au-btn--green" type="submit">Save settings</button>
                        <a href="{{ route('admin.inventory.index') }}" class="au-btn btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
