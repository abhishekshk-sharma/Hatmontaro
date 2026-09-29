@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Privacy Policy</h1>
            <p class="text-muted mb-4">Last updated: {{ date('F d, Y') }}</p>

            <div class="card">
                <div class="card-body">
                    <h3>1. Information We Collect</h3>
                    <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
                    <ul>
                        <li>Personal information (name, email, phone number)</li>
                        <li>Payment information (processed securely through third-party providers)</li>
                        <li>Profile information (preferences, sizes, style choices)</li>
                        <li>Usage data (how you interact with our AI recommendations)</li>
                    </ul>

                    <h3>2. How We Use Your Information</h3>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Provide, maintain, and improve our services</li>
                        <li>Process transactions and send related information</li>
                        <li>Send you technical notices, updates, security alerts</li>
                        <li>Provide AI-powered fashion recommendations</li>
                        <li>Respond to your comments, questions, and customer service requests</li>
                    </ul>

                    <h3>3. Information Sharing</h3>
                    <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy:</p>
                    <ul>
                        <li>With service providers who assist us in operating our website</li>
                        <li>When required by law or to protect our rights</li>
                        <li>In connection with a business transfer or merger</li>
                    </ul>

                    <h3>4. Data Security</h3>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                    <h3>5. AI and Machine Learning</h3>
                    <p>Our AI system analyzes your preferences and behavior to provide personalized recommendations. This data is processed securely and used solely to improve your shopping experience.</p>

                    <h3>6. Cookies</h3>
                    <p>We use cookies to enhance your experience, analyze site usage, and assist in our marketing efforts. You can choose to disable cookies through your browser settings.</p>

                    <h3>7. Your Rights</h3>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access and update your personal information</li>
                        <li>Delete your account and associated data</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Request a copy of your data</li>
                    </ul>

                    <h3>8. Contact Us</h3>
                    <p>If you have questions about this Privacy Policy, please contact us at <a href="{{ route('contact') }}">our contact page</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection