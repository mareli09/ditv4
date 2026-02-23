@extends('layouts.ceso')

@section('title', $activity->title)

@section('content')
<style>
    .badge-light-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
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
                            <button class="btn btn-sm btn-outline-info explain-sentiment" data-feedback-id="{{ $fb->id }}">Explain Sentiment</button>
                            <div class="sentiment-explanation mt-2" id="explanation-{{ $fb->id }}" style="display: none;"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            @push('scripts')
            <script>
                document.querySelectorAll('.explain-sentiment').forEach(button => {
                    button.addEventListener('click', function() {
                        const feedbackId = this.getAttribute('data-feedback-id');
                        const explanationDiv = document.getElementById(`explanation-${feedbackId}`);

                        if (explanationDiv.style.display === 'none') {
                            fetch(`{{ url('/activities/' . $activity->id . '/feedback') }}/${feedbackId}/explain`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                explanationDiv.innerHTML = `<p>${data.explanation}</p>`;
                                explanationDiv.style.display = 'block';
                            })
                            .catch(error => {
                                explanationDiv.innerHTML = '<p class="text-danger">Failed to fetch explanation.</p>';
                                explanationDiv.style.display = 'block';
                            });
                        } else {
                            explanationDiv.style.display = 'none';
                        }
                    });
                });
            </script>
            @endpush

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

            {{-- DECISION SUPPORT INSIGHTS --}}
            <h5><i class="fas fa-lightbulb me-2"></i>Decision Support & Insights</h5>
            <div class="card shadow-sm mb-4" style="border-left: 4px solid #2563eb;">
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted small">Effectiveness Score</h6>
                                <div style="font-size: 2.5rem; font-weight: bold; color: 
                                    @if($insights['effectiveness_score'] >= 80) #28a745
                                    @elseif($insights['effectiveness_score'] >= 60) #ffc107
                                    @else #dc3545
                                    @endif;">
                                    {{ $insights['effectiveness_score'] }}%
                                </div>
                                <small class="text-muted">Overall Activity Performance</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted small">Overall Performance</h6>
                                <div style="font-size: 1.8rem; font-weight: bold;">
                                    <span class="badge 
                                        @if($insights['overall_performance'] === 'Excellent') bg-success
                                        @elseif($insights['overall_performance'] === 'Good') bg-info
                                        @elseif($insights['overall_performance'] === 'Fair') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ $insights['overall_performance'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h6 class="text-muted small">Sentiment Distribution</h6>
                                <div class="d-flex gap-2 justify-content-center mt-2">
                                    <div>
                                        <span class="badge bg-success">{{ $insights['sentiment_distribution']['positive'] }}%</span>
                                        <small class="d-block text-muted">Positive</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-secondary">{{ $insights['sentiment_distribution']['neutral'] }}%</span>
                                        <small class="d-block text-muted">Neutral</small>
                                    </div>
                                    <div>
                                        <span class="badge bg-danger">{{ $insights['sentiment_distribution']['negative'] }}%</span>
                                        <small class="d-block text-muted">Negative</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-search me-2"></i>Key Findings</h6>
                            @if(!empty($insights['key_findings']))
                                <ul class="list-unstyled">
                                    @foreach($insights['key_findings'] as $finding)
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-info me-2"></i>
                                            {{ $finding }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted small">No findings available.</p>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3"><i class="fas fa-tasks me-2"></i>Recommendations</h6>
                            @if(!empty($insights['recommendations']))
                                <ul class="list-unstyled">
                                    @foreach($insights['recommendations'] as $rec)
                                        <li class="mb-2" style="font-size: 0.95rem;">
                                            {{ $rec }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted small">No recommendations available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARTICIPANT-SPECIFIC IMPROVEMENTS --}}
            @if(!empty($insights['participant_improvements']))
            <h5 class="mt-4"><i class="fas fa-users-cog me-2"></i>Participant-Specific Improvements</h5>
            <div class="row g-3">
                @foreach($insights['participant_improvements'] as $role => $data)
                <div class="col-md-6">
                    <div class="card shadow-sm" style="border-top: 3px solid #2563eb;">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ ucfirst($role) }} 
                                <span class="badge bg-warning ms-2">{{ $data['low_rating_count'] }}/{{ $data['total_feedback'] }} Low Ratings</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h7 class="text-muted small fw-bold mb-2">Common Issues Reported:</h7>
                                <div>
                                    @foreach($data['common_issues'] as $issue)
                                        <span class="badge badge-light-danger me-2 mb-1 px-2 py-1">{{ ucwords($issue) }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <h7 class="text-muted small fw-bold mb-2">Specific Improvements:</h7>
                                <ul class="list-unstyled mb-0">
                                    @foreach($data['specific_improvements'] as $improvement)
                                        <li class="mb-2">
                                            <span class="badge bg-success me-2">✓</span>
                                            <span style="font-size: 0.9rem;">{{ $improvement }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

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

