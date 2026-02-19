@extends('layouts.app')

@section('title', 'CESO | Community Registration')

@section('content')

<section class="registration-section py-5" style="background-color:#f4f6f8;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="bg-white p-5 rounded shadow">
                    <h2 class="mb-4 text-center">Community Registration</h2>

                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('community.store') }}">
                        @csrf

                        <!-- NAME -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Last Name *</label>
                                <input type="text" name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">First Name *</label>
                                <input type="text" name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name"
                                       class="form-control"
                                       value="{{ old('middle_name') }}">
                            </div>
                        </div>

                        <!-- PERSONAL -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input type="number" name="age"
                                       class="form-control"
                                       value="{{ old('age') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                    <option value="Prefer not to say" {{ old('gender')=='Prefer not to say'?'selected':'' }}>
                                        Prefer not to say
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="mb-3">
                            <label class="form-label">Address *</label>
                            <input type="text" name="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Barangay *</label>
                            <input type="text" name="barangay"
                                   class="form-control @error('barangay') is-invalid @enderror"
                                   value="{{ old('barangay') }}" required>
                            @error('barangay')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CONTACT -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">E-mail *</label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- PREVIOUS CESO -->
                        <div class="mb-3">
                            <label class="form-label d-block">
                                Previously joined CESO activity?
                            </label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                       name="joined" value="yes"
                                       {{ old('joined')=='yes'?'checked':'' }}>
                                <label class="form-check-label">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                       name="joined" value="no"
                                       {{ old('joined')=='no'?'checked':'' }}>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>

                        <!-- PRIVACY -->
                        <div class="border p-3 mb-4 rounded">
                            <p class="small mb-2">
                                By submitting this form, you consent to the collection,
                                use, and processing of your personal information
                                for community engagement purposes only.
                            </p>

                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="privacy"
                                       value="1"
                                       {{ old('privacy') ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    I agree to the Data Privacy Consent *
                                </label>
                            </div>

                            @error('privacy')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Register
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
