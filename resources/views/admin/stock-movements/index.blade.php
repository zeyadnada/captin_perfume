@extends('layouts.admin', ['title' => __('admin.stock_movements.title')])

@section('content')
    <x-page-header :title="__('admin.stock_movements.title')" :subtitle="__('admin.stock_movements.index_subtitle')">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> {{ __('admin.inventory.record_movement') }}
            </a>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('admin.common.close') }}"></button>
        </div>
    @endif

    <div class="au-card m-b-30">
        <div class="au-card-inner">
            <div class="table-responsive">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th>{{ __('admin.stock_movements.date') }}</th>
                            <th>{{ __('admin.common.product') }}</th>
                            <th>{{ __('admin.common.variant') }}</th>
                            <th>{{ __('admin.stock_movements.direction') }}</th>
                            <th>{{ __('admin.common.quantity') }}</th>
                            <th>{{ __('admin.stock_movements.balance_after') }}</th>
                            <th>{{ __('admin.common.reason') }}</th>
                            <th>{{ __('admin.stock_movements.reference') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $movement->stock->variant->product->name }}</td>
                                <td>{{ $movement->stock->variant->display_name }}</td>
                                <td>
                                    <span class="{{ $movement->type === 'in' ? 'status--process' : 'status--denied' }}">
                                        {{ $movement->type === 'in' ? __('admin.stock_movements.incoming') : __('admin.stock_movements.outgoing') }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->balance_after }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>
                                    @if ($movement->reference_type)
                                        {{ __('admin.stock_movements.reference_display', ['type' => $movement->reference_type, 'id' => $movement->reference_id]) }}
                                    @else
                                        {{ __('admin.stock_movements.not_linked') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">{{ __('admin.stock_movements.empty') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $movements->links() }}</div>
        </div>
    </div>
@endsection