@extends('layouts.ceso')

@section('title', 'CESO Staff Dashboard')

@section('content')

<div class="container-fluid">
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Previous Activities</h5>
                    <h2 class="fw-bold">{{ $stats['previousActivities'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>On-going Activities</h5>
                    <h2 class="fw-bold">{{ $stats['ongoingActivities'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h5>Upcoming Activities</h5>
                    <h2 class="fw-bold">{{ $stats['upcomingActivities'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            Sample Sentiment Analysis (OpenAI)
        </div>
        <div class="card-body">
            <p><strong>Community Feedback:</strong>
                "The CESO outreach program was very helpful and well-organized."
            </p>
            <p><strong>Sentiment:</strong>
                <span class="badge bg-success">Positive</span>
            </p>
            <p><strong>AI Insight:</strong>
                Community sentiment reflects satisfaction and strong engagement.
            </p>
        </div>
    </div>
</div>

@endsection