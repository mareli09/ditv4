@extends('layouts.ceso')

@section('title', 'Create Activity - CESO')

@section('content')

<div class="container-fluid">
    <h3 class="fw-bold mb-3">Create Activity</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('ceso.activities.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Activity Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" value="{{ old('venue') }}">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" value="{{ old('end_time') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Conducted By</label>
                    <input type="text" name="conducted_by" class="form-control" value="{{ old('conducted_by') }}">
                </div>

                <h5 class="mt-4">Invited Participants</h5>
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <select name="invited_faculty" class="form-select">
                            <option value="">Faculty</option>
                            <option value="na">N/A</option>
                            <option value="yes">With Invited Faculty</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="invited_staff" class="form-select">
                            <option value="">Staff</option>
                            <option value="na">N/A</option>
                            <option value="yes">With Invited Staff</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="invited_student" class="form-select">
                            <option value="">Student</option>
                            <option value="na">N/A</option>
                            <option value="yes">With Invited Student</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3 mb-3">
                    <label class="form-label">Fee / Expenses (₱)</label>
                    <input type="number" name="fee" class="form-control" value="{{ old('fee') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Attachments</label>
                    <input type="file" name="attachments[]" class="form-control" multiple>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>

                <button class="btn btn-primary w-100">Submit Activity</button>

            </form>

        </div>
    </div>
</div>

@endsection
