@extends('layouts.app')

@section('title', 'CESO | Community Extension Services Office')

@section('content')

<!-- HERO CAROUSEL -->
<style>
    #heroCarousel {
        background-color: #000;
    }
    .carousel-inner {
        max-height: 500px;
    }
    .carousel-item {
        height: 500px;
        background-color: #000;
    }
    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-indicators">
        @foreach(json_decode($contents['carousel_images'] ?? '[]') as $index => $image)
            <button data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach(json_decode($contents['carousel_images'] ?? '[]') as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" alt="CESO Activity">
            </div>
        @endforeach
    </div>

    <button class="carousel-control-prev" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- ABOUT -->
<section id="about" class="about-section py-5">
<div class="container">
<div class="about-overlay text-center">

<h2 class="fw-bold mb-3">About CESO</h2>
<p class="mb-4">
    {{ $contents['about_description'] ?? 'Default about description' }}
</p>

<div class="row g-3">
    <div class="col-md-6">
        <div class="bg-light text-dark p-4 rounded">
            <h4 class="fw-bold">Mission</h4>
            <p>
                {{ $contents['mission'] ?? 'Default mission' }}
            </p>
        </div>
    </div>

    <div class="col-md-6">
        <div class="bg-light text-dark p-4 rounded">
            <h4 class="fw-bold">Vision</h4>
            <p>
                {{ $contents['vision'] ?? 'Default vision' }}
            </p>
        </div>
    </div>
</div>

</div>
</div>
</section>

<!-- NEWS & CTA -->
<section class="py-5">
<div class="container">
<div class="row g-4">

<div class="col-md-6">
<div class="bg-light p-4 rounded h-100">
<h3 class="fw-bold mb-3">Latest News</h3>

@php
    $news = array_filter([
        $contents['news_1'] ?? null,
        $contents['news_2'] ?? null,
        $contents['news_3'] ?? null,
    ]);
@endphp

@if(count($news) > 0)
    <ul class="list-unstyled">
        @foreach($news as $newsItem)
            <li class="mb-3 pb-3 border-bottom">
                <p class="mb-0 text-dark">{{ $newsItem }}</p>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted text-center py-4">No news available at the moment</p>
@endif

</div>
</div>

<div class="col-md-6">
<div class="bg-light p-4 rounded text-center h-100">
<h3 class="fw-bold">{{ $contents['cta_heading'] ?? 'Want to be part of the community?' }}</h3>
<p class="text-muted">
    {{ $contents['cta_description'] ?? 'Join our extension programs and make a difference.' }}
</p>
<a href="{{ route('community.register') }}" class="btn btn-primary mt-3">
    {{ $contents['cta_button_text'] ?? 'Join Us' }}
</a>
</div>
</div>

</div>
</div>
</section>

<!-- CONTACT -->
<section id="contact" class="py-5 bg-light">
<div class="container">
<div class="row g-4">

<div class="col-md-6">
<div class="bg-white p-4 rounded h-100">
<h3 class="fw-bold mb-3">Contact Us</h3>

<form method="POST" action="{{ route('contact.send') }}">
@csrf
<input name="name" class="form-control mb-3" placeholder="Full Name">
<input name="email" type="email" class="form-control mb-3" placeholder="Email">
<textarea name="message" class="form-control mb-3" rows="5" placeholder="Message"></textarea>
<button class="btn btn-primary w-100">Send Message</button>
</form>

</div>
</div>

<div class="col-md-6">
<div class="bg-white p-4 rounded h-100">
<h3 class="fw-bold mb-3">Get in Touch</h3>
<p><strong>Email:</strong> {{ $contents['contact_email'] ?? 'ceso@example.edu.ph' }}</p>
<p><strong>Contact:</strong> {{ $contents['contact_number'] ?? '+63 900 000 0000' }}</p>
<p><strong>Address:</strong> {{ $contents['contact_address'] ?? 'Sample City, Philippines' }}</p>
</div>
</div>

</div>
</div>
</section>

@endsection
