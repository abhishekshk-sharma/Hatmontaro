@extends('layouts.app')

@section('title', 'About Us - Aksharam Fashion')

@section('content')
<!-- Hero Section -->
<section class="py-5" style="margin-top: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-4">About Aksharam Fashion</h1>
                <p class="lead mb-4">
                    Revolutionizing fashion retail with AI-powered personalization and cutting-edge technology. 
                    We're not just selling clothes - we're crafting your perfect style story.
                </p>
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
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <img src="https://images.unsplash.com/photo-1441986300917-64674bd600d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
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
                <div class="card border-0 h-100 p-4" style="background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-bullseye fs-1 me-3"></i>
                            <h3 class="fw-bold mb-0">Our Mission</h3>
                        </div>
                        <p class="lead">
                            To revolutionize the fashion industry by combining artificial intelligence 
                            with human creativity, making personalized style accessible to everyone while 
                            promoting sustainable and ethical fashion practices.
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
                <div class="card border-0 h-100 p-4" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-eye fs-1 me-3"></i>
                            <h3 class="fw-bold mb-0">Our Vision</h3>
                        </div>
                        <p class="lead">
                            To become the world's leading AI-driven fashion platform, where technology 
                            and style converge to create unique, personalized experiences that inspire 
                            confidence and self-expression in every individual.
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
            <!-- CEO -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="CEO" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2">
                                <i class="bi bi-crown text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Arjun Sharma</h4>
                        <p class="text-primary fw-semibold mb-3">Chief Executive Officer</p>
                        <p class="text-muted mb-4">
                            Visionary leader with 15+ years in fashion tech. Former VP at major fashion retailers, 
                            passionate about democratizing style through AI.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-primary"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-primary"><i class="bi bi-twitter fs-5"></i></a>
                            <a href="#" class="text-primary"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CTO -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="CTO" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2">
                                <i class="bi bi-cpu text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Priya Patel</h4>
                        <p class="text-success fw-semibold mb-3">Chief Technology Officer</p>
                        <p class="text-muted mb-4">
                            AI/ML expert with PhD in Computer Vision. Former Google AI researcher, 
                            specializing in fashion recommendation systems and computer vision.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-success"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-success"><i class="bi bi-github fs-5"></i></a>
                            <a href="#" class="text-success"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Head of Design -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="Head of Design" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-warning rounded-circle p-2">
                                <i class="bi bi-palette text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Rahul Gupta</h4>
                        <p class="text-warning fw-semibold mb-3">Head of Design</p>
                        <p class="text-muted mb-4">
                            Creative director with 12+ years in fashion design. Former design lead at luxury brands, 
                            expert in trend forecasting and user experience design.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-warning"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-warning"><i class="bi bi-dribbble fs-5"></i></a>
                            <a href="#" class="text-warning"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Head of Marketing -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="Head of Marketing" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-info rounded-circle p-2">
                                <i class="bi bi-megaphone text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Sneha Reddy</h4>
                        <p class="text-info fw-semibold mb-3">Head of Marketing</p>
                        <p class="text-muted mb-4">
                            Digital marketing strategist with expertise in fashion e-commerce. 
                            Former marketing director at leading fashion brands, growth hacking specialist.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-info"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-instagram fs-5"></i></a>
                            <a href="#" class="text-info"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Head of Operations -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="Head of Operations" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-danger rounded-circle p-2">
                                <i class="bi bi-gear text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Vikram Singh</h4>
                        <p class="text-danger fw-semibold mb-3">Head of Operations</p>
                        <p class="text-muted mb-4">
                            Operations expert with 10+ years in supply chain management. 
                            Former operations manager at major e-commerce platforms, logistics optimization specialist.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="text-danger"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" class="text-danger"><i class="bi bi-twitter fs-5"></i></a>
                            <a href="#" class="text-danger"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Success Manager -->
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 team-card">
                    <div class="card-body text-center p-4">
                        <div class="position-relative mb-4">
                            <img src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" 
                                 class="rounded-circle shadow" alt="Customer Success Manager" style="width: 120px; height: 120px; object-fit: cover;">
                            <div class="position-absolute bottom-0 end-0 bg-purple rounded-circle p-2" style="background-color: #7c3aed;">
                                <i class="bi bi-people text-white"></i>
                            </div>
                        </div>
                        <h4 class="fw-bold">Ananya Joshi</h4>
                        <p class="fw-semibold mb-3" style="color: #7c3aed;">Customer Success Manager</p>
                        <p class="text-muted mb-4">
                            Customer experience specialist with passion for fashion. 
                            Expert in building customer relationships and ensuring satisfaction through personalized service.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" style="color: #7c3aed;"><i class="bi bi-linkedin fs-5"></i></a>
                            <a href="#" style="color: #7c3aed;"><i class="bi bi-twitter fs-5"></i></a>
                            <a href="#" style="color: #7c3aed;"><i class="bi bi-envelope fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
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