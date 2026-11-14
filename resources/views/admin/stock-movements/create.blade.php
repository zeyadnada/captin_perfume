@extends('layouts.admin', ['title' => __('admin.stock_movements.create_title')])

@section('content')
    <x-page-header :title="__('admin.stock_movements.create_title')" :subtitle="__('admin.stock_movements.create_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.index') }}" class="au-btn btn btn-secondary au-btn--small">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> {{ __('admin.stock_movements.history') }}
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
                            <label class="form-label" for="variant_id">{{ __('admin.stock_movements.product_variant') }}</label>
                            <select id="variant_id" name="variant_id" class="form-select @error('variant_id') is-invalid @enderror" required>
                                <option value="">{{ __('admin.stock_movements.select_variant') }}</option>
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
                                <label class="form-label" for="type">{{ __('admin.stock_movements.movement_direction') }}</label>
                                <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">{{ __('admin.stock_movements.select_direction') }}</option>
                                    <option value="in" @selected(old('type') === 'in')>{{ __('admin.stock_movements.incoming_add') }}</option>
                                    <option value="out" @selected(old('type') === 'out')>{{ __('admin.stock_movements.outgoing_remove') }}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="quantity">{{ __('admin.common.quantity') }}</label>
                                <input id="quantity" name="quantity" type="number" min="1" step="1" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="reason">{{ __('admin.common.reason') }}</label>
                            <textarea id="reason" name="reason" rows="3" maxlength="1000" class="form-control @error('reason') is-invalid @enderror" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label" for="reference_type">{{ __('admin.stock_movements.reference_type') }} <span class="text-muted">{{ __('admin.common.optional') }}</span></label>
                                <input id="reference_type" name="reference_type" type="text" maxlength="255" class="form-control @error('reference_type') is-invalid @enderror" value="{{ old('reference_type') }}" placeholder="{{ __('admin.stock_movements.reference_type_placeholder') }}">
                                @error('reference_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label" for="reference_id">{{ __('admin.stock_movements.reference_id') }} <span class="text-muted">{{ __('admin.common.optional') }}</span></label>
                                <input id="reference_id" name="reference_id" type="number" min="1" step="1" class="form-control @error('reference_id') is-invalid @enderror" value="{{ old('reference_id') }}">
                                @error('reference_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-light border">
                            {{ __('admin.stock_movements.outgoing_notice') }}
                        </div>

                        <button class="au-btn au-btn--green" type="submit">{{ __('admin.inventory.record_movement') }}</button>
                        <a href="{{ route('admin.stock-movements.index') }}" class="au-btn btn btn-secondary">{{ __('admin.common.cancel') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection