@extends('layouts.it')

@section('title', 'IT Dashboard')

@section('content')

<h3 class="fw-bold mb-4">System Overview</h3>

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Users</h6>
                <h3 class="fw-bold">{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Active Users</h6>
                <h3 class="fw-bold text-success">{{ $activeUsers }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Inactive Users</h6>
                <h3 class="fw-bold text-warning">{{ $inactiveUsers }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card stat-card shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Archived Users</h6>
                <h3 class="fw-bold text-danger">{{ $archivedUsers }}</h3>
            </div>
        </div>
    </div>

</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-bold mb-3">User Role Breakdown</h5>
        <ul class="mb-0">
            <li>CESO Staff – {{ $cesoCount }}</li>
            <li>Faculty – {{ $facultyCount }}</li>
            <li>Students – {{ $studentCount }}</li>
            <li>Community Users – {{ $communityCount }}</li>
            <li>IT Staff – {{ $itCount }}</li>
        </ul>
    </div>
</div>

@endsection
