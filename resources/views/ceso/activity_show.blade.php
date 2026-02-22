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

            <h5>Feedback Results</h5>
            <div class="row g-3">
                @foreach($sentiments as $sentiment)
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <strong>{{ $sentiment['comment'] }}</strong>
                            <p class="mb-1 small text-muted">Sentiment: {{ $sentiment['sentiment'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <h5>Sentiment Analysis Results</h5>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <p>{{ $analysis ?? 'Sentiment analysis is not available at the moment.' }}</p>
                </div>
            </div>

            <h5>Sentiment Chart</h5>
            <canvas id="sentimentChart"></canvas>
            @push('scripts')
            <script>
                // Dynamic chart data from the controller
                const sentimentData = {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        data: [
                            {{ $sentimentCounts['Positive'] ?? 0 }},
                            {{ $sentimentCounts['Neutral'] ?? 0 }},
                            {{ $sentimentCounts['Negative'] ?? 0 }}
                        ],
                        backgroundColor: ['#198754', '#6c757d', '#dc3545'],
                        hoverBackgroundColor: ['#145a32', '#495057', '#a71d2a']
                    }]
                };

                const config = {
                    type: 'doughnut',
                    data: sentimentData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        }
                    }
                };

                new Chart(document.getElementById('sentimentChart'), config);
            </script>
            @endpush
        </div>
    </div>
</div>
@endsection

