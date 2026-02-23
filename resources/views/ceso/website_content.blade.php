@extends('layouts.ceso')

@section('title', 'Website Content Management')

@section('content')

    <div class="container-fluid py-4">

        <h3 class="fw-bold mb-4">Website Content Management</h3>
        <p class="text-muted mb-4">
            Manage and update the public-facing CESO website content.
        </p>

        {{-- ================= HERO / CAROUSEL ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">1. Landing Page – Hero / Carousel</h5>

                <form action="{{ route('ceso.website.hero.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @for ($i = 1; $i <= 3; $i++)
                        <div class="border rounded p-3 mb-3">
                            <h6 class="fw-bold mb-3">Slide {{ $i }}</h6>

                            <label class="form-label">Upload Image</label>
                            <input type="file" name="hero_image_{{ $i }}" class="form-control mb-3" accept="image/*">

                            <div class="text-center text-muted mb-3">— OR —</div>

                            <label class="form-label">Image URL</label>
                            <input type="text" name="hero_url_{{ $i }}" class="form-control mb-3"
                                placeholder="https://example.com/hero{{ $i }}.jpg">

                            <label class="form-label">Caption (optional)</label>
                            <input type="text" name="hero_caption_{{ $i }}" class="form-control">
                        </div>
                    @endfor

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Hero Carousel
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= ABOUT ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">2. About CESO</h5>

                <form action="{{ route('ceso.website.about.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="form-label">About Description</label>
                    <textarea name="about_description" class="form-control mb-3" rows="3"></textarea>

                    <label class="form-label">Mission</label>
                    <textarea name="mission" class="form-control mb-3" rows="2"></textarea>

                    <label class="form-label">Vision</label>
                    <textarea name="vision" class="form-control mb-3" rows="2"></textarea>

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update About Section
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= NEWS (LIMITED TO 3) ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">3. Latest News (Maximum 3 Items)</h5>

                <form action="{{ route('ceso.website.news.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    @for ($i = 1; $i <= 3; $i++)
                        <label class="form-label">News Item {{ $i }}</label>
                        <input type="text" name="news_{{ $i }}" class="form-control mb-3"
                            placeholder="Enter news headline {{ $i }}">
                    @endfor

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update News Section
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= CALL TO ACTION ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">4. Community Call-to-Action</h5>

                <form action="{{ route('ceso.website.cta.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="form-label">CTA Heading</label>
                    <input type="text" name="cta_heading" class="form-control mb-3">

                    <label class="form-label">CTA Description</label>
                    <textarea name="cta_description" class="form-control mb-3" rows="2"></textarea>

                    <label class="form-label">Button Text</label>
                    <input type="text" name="cta_button_text" class="form-control mb-3">

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Call-to-Action
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= CONTACT ================= --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3">5. Contact Information</h5>

                <form action="{{ route('ceso.website.contact.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="form-label">Email Address</label>
                    <input type="email" name="contact_email" class="form-control mb-3">

                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control mb-3">

                    <label class="form-label">Office Address</label>
                    <textarea name="contact_address" class="form-control mb-3" rows="2"></textarea>

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Contact Information
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= FOOTER ================= --}}
        <div class="card shadow-sm mb-5">
            <div class="card-body">
                <h5 class="fw-bold mb-3">6. Footer & Social Links</h5>

                <form action="{{ route('ceso.website.footer.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <label class="form-label">Copyright Text</label>
                    <input type="text" name="footer_copyright" class="form-control mb-3">

                    <label class="form-label">Facebook URL</label>
                    <input type="text" name="facebook_url" class="form-control mb-2">

                    <label class="form-label">Instagram URL</label>
                    <input type="text" name="instagram_url" class="form-control mb-2">

                    <label class="form-label">YouTube URL</label>
                    <input type="text" name="youtube_url" class="form-control mb-3">

                    <button class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Footer
                    </button>
                </form>
            </div>
        </div>

    </div>

@endsection