@extends('layouts.ceso')

@section('title', 'Database Setup - CESO')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-3">Database Setup</h3>

    <div class="alert alert-info">
        <h5>Click the button below to set up the entry code column for activities:</h5>
    </div>

    <form method="POST" action="{{ route('setup.add-entry-codes') }}" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fas fa-database me-2"></i>Add Entry Code Column
        </button>
    </form>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</div>
@endsection
