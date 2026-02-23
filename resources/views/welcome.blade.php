@extends('layouts.app')

@section('title', 'CESO | Community Extension Services Office')

@section('content')

<!-- HERO CAROUSEL -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-indicators">
        @foreach($contents['carousel_images'] ?? [] as $index => $image)
            <button data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></button>
        @endforeach
    </div>

    <div class="carousel-inner">
        @foreach($contents['carousel_images'] ?? [] as $index => $image)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ $image }}" class="d-block w-100" alt="CESO Activity">
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

<ul class="list-unstyled">
@foreach([1,2,3] as $news)
<li class="d-flex mb-3">
    <img src="https://picsum.photos/100?{{ $news }}" class="me-3 rounded">
    <p class="mb-0">
        Sample CESO announcement {{ $news }}
    </p>
</li>
@endforeach
</ul>

</div>
</div>

<div class="col-md-6">
<div class="bg-light p-4 rounded text-center h-100">
<h3 class="fw-bold">Want to be part of the community?</h3>
<p class="text-muted">
Join our extension programs and make a difference.
</p>
<a href="{{ route('community.register') }}" class="btn btn-primary mt-3">
Join Us
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
<p><strong>Email:</strong> ceso@example.edu.ph</p>
<p><strong>Contact:</strong> +63 900 000 0000</p>
<p><strong>Address:</strong> Sample City, Philippines</p>
</div>
</div>

</div>
</div>
</section>

@endsection
