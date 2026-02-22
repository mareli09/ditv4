@extends('layouts.ceso')

@section('title', $activity->title)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold mb-0">{{ $activity->title }}</h3>
        @if(auth()->user()?->role === 'CESO')
            <div>
                <a href="{{ route('ceso.activities.edit', $activity->id) }}" class="btn btn-sm btn-outline-primary">Edit Activity</a>
                @if(is_null($activity->archived_at))
                    <form method="POST" action="{{ route('ceso.activities.archive', $activity->id) }}" style="display:inline-block;" class="ms-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Archive this activity?')">Archive</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('ceso.activities.restore', $activity->id) }}" style="display:inline-block;" class="ms-2">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Restore this activity?')">Restore</button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Venue</label>
                    <div class="label-value">{{ $activity->venue }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Start Date</label>
                    <div class="label-value">{{ $activity->start_date?->format('Y-m-d') }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">End Date</label>
                    <div class="label-value">{{ $activity->end_date?->format('Y-m-d') }}</div>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold">Description</label>
                    <div class="label-value">{{ $activity->description }}</div>
                </div>

                <div class="col-12 mt-3">
                    <h5>Invited Participants</h5>
                    <div class="label-value">
                        @php
                            $participantsByType = $activity->participants->groupBy('participant_type');
                        @endphp

                        @if($participantsByType->isNotEmpty())
                            @foreach(['faculty' => 'Faculty', 'staff' => 'Staff', 'student' => 'Student', 'community' => 'Community', 'other' => 'Other'] as $type => $label)
                                @if(isset($participantsByType[$type]))
                                    <h6 class="mt-2">{{ $label }}:</h6>
                                    <ul class="mb-2">
                                        @foreach($participantsByType[$type] as $participant)
                                            <li>
                                                @if($participant->user_id && $participant->user)
                                                    {{ $participant->user->name }}
                                                @else
                                                    {{ $participant->name }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        @else
                            <p class="mb-0">No invited participants</p>
                        @endif
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <h5>Attachments</h5>
                    <div class="label-value">
                        @if($activity->attachments)
                            <ul class="mb-0">
                                @foreach($activity->attachments as $att)
                                    <li>{{ $att }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="mb-0">No attachments</p>
                        @endif
                    </div>
                </div>

            </div>

            <hr>

            <h5>Feedback</h5>
            <div class="mb-3">
                @foreach($activity->feedback as $fb)
                    <div class="card mb-2">
                        <div class="card-body">
                            <strong>{{ $fb->user?->name ?? $fb->source ?? $fb->role ?? 'Anonymous' }}</strong>
                            <p class="mb-1 small text-muted">Rating: {{ $fb->rating ?? 'N/A' }}</p>
                            <p>{{ $fb->comment }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <h6>Submit Feedback</h6>
            <form method="POST" action="{{ route('ceso.activities.feedback', $activity->id) }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Your Role</label>
                        <select name="role" class="form-select">
                            <option value="">Select role</option>
                            <option>IT</option>
                            <option>CESO</option>
                            <option>Faculty</option>
                            <option>Student</option>
                            <option>Community</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select">
                            <option value="staff">Staff</option>
                            <option value="faculty">Faculty</option>
                            <option value="student">Student</option>
                            <option value="community">Community</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rating (0-5)</label>
                        <input type="number" name="rating" min="0" max="5" class="form-control">
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="3"></textarea>
                </div>

                <button class="btn btn-primary">Submit Feedback</button>
            </form>

        </div>
    </div>
</div>
@endsection

        