@extends('layouts.app')

@section('title', 'About Us - Aksharam Fashion')

@section('content')
<!-- Hero Section -->
<section class="py-5" style="margin-top: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                @php $heroSection = $sections->where('section', 'hero')->first(); @endphp
                <h1 class="display-4 fw-bold mb-4">{{ $heroSection->title ?? 'About Aksharam Fashion' }}</h1>
                <p class="lead mb-4">
                    {{ $heroSection->content ?? 'Revolutionizing fashion retail with AI-powered personalization and cutting-edge technology.' }}
                </p>
                @if($heroSection && $heroSection->extra_data && isset($heroSection->extra_data['stats']))
                <div class="d-flex gap-3">
                    @foreach($heroSection->extra_data['stats'] as $stat)
                    <div class="text-center">
                        <h3 class="fw-bold">{{ $stat['value'] }}</h3>
                        <small>{{ $stat['label'] }}</small>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="d-flex gap-3">
                    <div class="text-center">
                        <h3 class="fw-bold">10K+</h3>
                        <small>Happy Customers</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold">5K+</h3>
                        <small>Products</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold">99%</h3>
                        <small>AI Accuracy</small>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <img src="{{ $heroSection && $heroSection->image ? asset('storage/' . $heroSection->image) : 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80' }}" 
                     class="img-fluid rounded-4 shadow-lg" alt="Fashion Store">
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-4">Our Story</h2>
                <p class="lead text-muted mb-5">
                    Founded with a vision to democratize fashion through artificial intelligence, 
                    Aksharam Fashion began as a dream to make personalized styling accessible to everyone.
                </p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 h-100 text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-lightbulb text-white fs-3"></i>
                    </div>
                    <h4>Innovation First</h4>
                    <p class="text-muted">
                        We leverage cutting-edge AI technology to understand your unique style preferences 
                        and deliver personalized fashion recommendations.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100 text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-heart text-white fs-3"></i>
                    </div>
                    <h4>Customer Centric</h4>
                    <p class="text-muted">
                        Every decision we make is driven by our commitment to providing exceptional 
                        customer experiences and building lasting relationships.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 h-100 text-center p-4">
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-globe text-white fs-3"></i>
                    </div>
                    <h4>Global Impact</h4>
                    <p class="text-muted">
                        From local communities to global markets, we're committed to making fashion 
                        more sustainable, accessible, and inclusive for everyone.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                @php $missionSection = $sections->where('section', 'mission')->first(); @endphp
                <div class="card border-0 h-100 p-4" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-bullseye fs-1 me-3"></i>
                            <h3 class="fw-bold mb-0">{{ $missionSection->title ?? 'Our Mission' }}</h3>
                        </div>
                        <p class="lead">
                            {{ $missionSection->content ?? 'To revolutionize the fashion industry by combining artificial intelligence with human creativity.' }}
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>AI-Powered Personalization</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Sustainable Fashion</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Inclusive Design</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Customer Empowerment</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                @php $visionSection = $sections->where('section', 'vision')->first(); @endphp
                <div class="card border-0 h-100 p-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-eye fs-1 me-3"></i>
                            <h3 class="fw-bold mb-0">{{ $visionSection->title ?? 'Our Vision' }}</h3>
                        </div>
                        <p class="lead">
                            {{ $visionSection->content ?? 'To become the world\'s leading AI-driven fashion platform.' }}
                        </p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Global Fashion Leader</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Technology Innovation</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Style Democracy</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Future of Fashion</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Team -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">Meet Our Team</h2>
            <p class="lead text-muted">
                The brilliant minds behind Aksharam Fashion's AI-powered revolution
            </p>
        </div>
        
        <div class="row g-4">
            @forelse($teamMembers as $member)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            @if($member->image)
                                <img src="{{ asset('storage/' . $member->image) }}" 
                                     class="rounded-circle shadow" alt="{{ $member->name }}" style="width: 120px; height: 120px; object-fit: cover;"
                                     onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80'">
                            @else
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                     class="rounded-circle shadow" alt="{{ $member->name }}" style="width: 120px; height: 120px; object-fit: cover;">
                            @endif
                            <div class="position-absolute bottom-0 end-0 rounded-circle p-2" style="background-color: {{ $member->color }};">
                                <i class="{{ $member->icon }} text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">{{ $member->name }}</h4>
                        <p class="fw-semibold mb-3" style="color: {{ $member->color }};">{{ $member->position }}</p>
                        <p class="text-muted mb-4">
                            {{ $member->description }}
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            @if($member->linkedin_url)
                            <a href="{{ $member->linkedin_url }}" style="color: {{ $member->color }};"><i class="bi bi-linkedin fs-5"></i></a>
                            @endif
                            @if($member->twitter_url)
                            <a href="{{ $member->twitter_url }}" style="color: {{ $member->color }};"><i class="bi bi-twitter fs-5"></i></a>
                            @endif
                            @if($member->email)
                            <a href="mailto:{{ $member->email }}" style="color: {{ $member->color }};"><i class="bi bi-envelope fs-5"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <h5>No team members found</h5>
                <p class="text-muted">Team members will appear here once added by admin.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-4">Our Core Values</h2>
            <p class="lead text-muted">
                The principles that guide everything we do at Aksharam Fashion
            </p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-shield-check text-primary fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Integrity</h5>
                    <p class="text-muted">
                        We believe in honest, transparent business practices and building trust with our customers.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-rocket text-success fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Innovation</h5>
                    <p class="text-muted">
                        We continuously push boundaries to create cutting-edge solutions for the fashion industry.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-people text-warning fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Inclusivity</h5>
                    <p class="text-muted">
                        Fashion is for everyone. We celebrate diversity and create inclusive experiences for all.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="text-center p-4">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="bi bi-recycle text-info fs-3"></i>
                    </div>
                    <h5 class="fw-bold">Sustainability</h5>
                    <p class="text-muted">
                        We're committed to environmentally responsible practices and sustainable fashion choices.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Join Our Team -->
<section class="py-5" style="background: linear-gradient(135deg, #a29bfe 0%, #6c5ce7 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 text-white">
                <h2 class="display-5 fw-bold mb-4">Join Our Team</h2>
                <p class="lead mb-4">
                    Ready to revolutionize fashion with AI? We're always looking for passionate, 
                    talented individuals to join our mission of making fashion more personal and accessible.
                </p>
                <ul class="list-unstyled mb-4">
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Competitive salaries and equity</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Flexible work arrangements</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Learning and development opportunities</li>
                    <li class="mb-2"><i class="bi bi-check-circle-fill me-2"></i>Health and wellness benefits</li>
                </ul>
            </div>
            <div class="col-lg-4 text-center">
                <a href="{{ route('contact') }}" class="btn btn-light btn-lg rounded-pill px-5">
                    <i class="bi bi-briefcase me-2"></i>View Open Positions
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="text-center">
            <h2 class="fw-bold mb-4">Get in Touch</h2>
            <p class="lead text-muted mb-4">
                Have questions about our mission, products, or want to partner with us? 
                We'd love to hear from you.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="bi bi-envelope me-2"></i>Contact Us
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                    <i class="bi bi-shop me-2"></i>Shop Now
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .team-card img {
        transition: transform 0.3s ease;
    }
    
    .team-card:hover img {
        transform: scale(1.05);
    }
</style>
@endpush