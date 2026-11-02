@extends('layouts.admin', ['title' => 'Brands'])

@section('content')
    <x-page-header title="Brands" subtitle="Manage perfume brands used by products.">
        <x-slot:actions>
            <a href="{{ route('admin.brands.create') }}" class="au-btn au-btn--green au-btn--small">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add brand
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
            <form method="GET" action="{{ route('admin.brands.index') }}" class="row g-3 align-items-end mb-4 p-3 rounded bg-light">
                <div class="col-md-6">
                    <label class="form-label" for="search">Search</label>
                    <input class="au-input" id="search" name="search" value="{{ request('search') }}" placeholder="Name or slug">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="status">Status</label>
                    <select class="au-input" id="status" name="status">
                        <option value="">All statuses</option>
                        <option value="1" @selected(request('status') === '1')>Active</option>
                        <option value="0" @selected(request('status') === '0')>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="au-btn au-btn--blue au-btn--small flex-grow-1" type="submit" title="Apply filters" aria-label="Apply filters">
                        <i class="fa-solid fa-filter me-1" aria-hidden="true"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center justify-content-center" style="height: 30px; width: 30px; padding: 0;" title="Reset filters" aria-label="Reset filters">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    </a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-borderless table-data3">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                            <tr>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}" width="48" height="48" style="object-fit: cover;">
                                    @else
                                        <span class="badge bg-secondary">No logo</span>
                                    @endif
                                </td>
                                <td>{{ $brand->name }}</td>
                                <td><code>{{ $brand->slug }}</code></td>
                                <td>
                                    <span class="{{ $brand->is_active ? 'status--process' : 'status--denied' }}">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="table-data-feature">
                                        <a href="{{ route('admin.brands.edit', $brand) }}" class="item text-decoration-none" data-bs-toggle="tooltip" title="Edit {{ $brand->name }}" aria-label="Edit {{ $brand->name }}">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="d-inline" onsubmit="return confirm('Delete this brand?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="item p-0" type="submit" data-bs-toggle="tooltip" title="Delete {{ $brand->name }}" aria-label="Delete {{ $brand->name }}">
                                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No brands found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $brands->links() }}
        </div>
    </div>
@endsection
