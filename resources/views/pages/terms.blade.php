@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Terms & Conditions</h1>
            <p class="text-muted mb-4">Last updated: {{ date('F d, Y') }}</p>

            <div class="card">
                <div class="card-body">
                    <h3>1. Acceptance of Terms</h3>
                    <p>By accessing and using Hatmontaro, you accept and agree to be bound by the terms and provision of this agreement.</p>

                    <h3>2. Use License</h3>
                    <p>Permission is granted to temporarily download one copy of the materials on Hatmontaro for personal, non-commercial transitory viewing only.</p>

                    <h3>3. Disclaimer</h3>
                    <p>The materials on Hatmontaro are provided on an 'as is' basis. Hatmontaro makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

                    <h3>4. Limitations</h3>
                    <p>In no event shall Hatmontaro or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on Hatmontaro, even if Hatmontaro or a Hatmontaro authorized representative has been notified orally or in writing of the possibility of such damage.</p>

                    <h3>5. Privacy Policy</h3>
                    <p>Your privacy is important to us. Please review our Privacy Policy, which also governs your use of the Site, to understand our practices.</p>

                    <h3>6. User Accounts</h3>
                    <p>When you create an account with us, you must provide information that is accurate, complete, and current at all times. You are responsible for safeguarding the password and for all activities that occur under your account.</p>

                    <h3>7. Prohibited Uses</h3>
                    <p>You may not use our service:</p>
                    <ul>
                        <li>For any unlawful purpose or to solicit others to perform unlawful acts</li>
                        <li>To violate any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                        <li>To infringe upon or violate our intellectual property rights or the intellectual property rights of others</li>
                        <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate</li>
                    </ul>

                    <h3>8. Contact Information</h3>
                    <p>If you have any questions about these Terms & Conditions, please contact us at <a href="{{ route('contact') }}">our contact page</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection