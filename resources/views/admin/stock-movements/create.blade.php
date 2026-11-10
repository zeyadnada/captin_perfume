@extends('layouts.admin', ['title' => 'Record Stock Movement'])

@section('content')
    <x-page-header title="Record Stock Movement" subtitle="Every physical stock change creates a permanent movement record.">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.index') }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Movement history
            </a>
        </x-slot:actions>
    </x-page-header>

    <div class="row">
        <div class="col-lg-12">
            <div class="au-card">
                <div class="au-card-inner">
                    <form method="POST" action="{{ route('admin.stock-movements.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="variant_id">Product variant</label>
                            <select id="variant_id" name="variant_id" class="form-select @error('variant_id') is-invalid @enderror" required>
                                <option value="">Select a product variant</option>
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" @selected(old('variant_id', $selectedVariantId) == $variant->id)>
                                        {{ $variant->product->name }} — {{ $variant->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('variant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="type">Movement direction</label>
                                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select direction</option>
                                    <option value="in" @selected(old('type') === 'in')>Incoming (add stock)</option>
                                    <option value="out" @selected(old('type') === 'out')>Outgoing (remove stock)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="quantity">Quantity</label>
                                <input id="quantity" name="quantity" type="number" min="1" step="1" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="reason">Reason</label>
                            <textarea id="reason" name="reason" rows="3" maxlength="1000" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label" for="reference_type">Reference type <span class="text-muted">(optional)</span></label>
                                <input id="reference_type" name="reference_type" type="text" maxlength="255" class="form-control @error('reference_type') is-invalid @enderror" value="{{ old('reference_type') }}" placeholder="Example: purchase">
                                @error('reference_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="reference_id">Reference ID <span class="text-muted">(optional)</span></label>
                                <input id="reference_id" name="reference_id" type="number" min="1" step="1" class="form-control @error('reference_id') is-invalid @enderror" value="{{ old('reference_id') }}">
                                @error('reference_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-light border">
                            Outgoing movements cannot reduce available stock below the quantity reserved for pending orders.
                        </div>

                        <button class="au-btn au-btn--green" type="submit">Record movement</button>
                        <a href="{{ route('admin.stock-movements.index') }}" class="au-btn btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
