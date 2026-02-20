@extends('layouts.it')

@section('title', 'User Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">User Management</h3>
    <a href="{{ route('it.users.create') }}" class="btn btn-primary">
        <i class="fas fa-user-plus me-2"></i> Create User
    </a>
</div>

<!-- SEARCH & FILTER -->
<div class="row g-2 mb-3">
    <div class="col-md-7">
        <input type="text" class="form-control" placeholder="Search name or email..." id="searchInput">
    </div>
    <div class="col-md-3">
        <select class="form-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-secondary w-100" id="filterBtn">Filter</button>
    </div>
</div>

<!-- IMPORT USERS (CSV) -->
<div class="card shadow-sm mb-4">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-file-csv me-2 text-success"></i> Import Users (CSV)
            </h5>

            <!-- Optional sample CSV -->
            <a href="{{ route('it.users.sample-csv') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download me-1"></i> Download Sample
            </a>
        </div>

        <form action="{{ route('it.users.import') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row g-3 align-items-end">

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Upload CSV File</label>
                    <input type="file" class="form-control" name="csv_file" accept=".csv" required>
                    <small class="text-muted">
                        Accepted format: .csv (UTF-8)
                    </small>
                </div>

                <div class="col-md-4 d-grid">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload me-1"></i> Import Users
                    </button>
                </div>

            </div>
        </form>

        <!-- CSV FORMAT GUIDE -->
        <div class="alert alert-info mt-3 mb-0">
            <strong>CSV Columns Required:</strong><br>
            email, name, role, is_active
        </div>

    </div>
</div>

<!-- TABLE -->
<div class="card shadow-sm">
    <div class="card-body table-responsive">

        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge" style="background: #dcfce7; color: #166534;">Active</span>
                        @else
                            <span class="badge" style="background: #fee2e2; color: #991b1b;">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('it.users.edit', $user->id) }}"><button class="btn btn-sm btn-outline-primary">Edit</button></a>
                        @if($user->is_active)
                            <form action="{{ route('it.users.deactivate', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Deactivate this user?')">Deactivate</button>
                            </form>
                        @else
                            <form action="{{ route('it.users.activate', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-success">Activate</button>
                            </form>
                        @endif
                        <form action="{{ route('it.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Archive this user?')">Archive</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">No users found</td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
