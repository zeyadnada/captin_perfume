@extends('layouts.admin', ['title' => 'Stock Movements'])

@section('content')
    <x-page-header title="Stock Movements" subtitle="Review the history of physical stock changes.">
        <x-slot:actions>
            <a href="{{ route('admin.stock-movements.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Record movement
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
                            <th>Date</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Direction</th>
                            <th>Quantity</th>
                            <th>Balance after</th>
                            <th>Reason</th>
                            <th>Reference</th>
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
                                        {{ $movement->type === 'in' ? 'Incoming' : 'Outgoing' }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->balance_after }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>
                                    @if ($movement->reference_type)
                                        {{ $movement->reference_type }} #{{ $movement->reference_id }}
                                    @else
                                        Not linked
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">No stock movements recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $movements->links() }}</div>
        </div>
    </div>
@endsection
