@extends('layout.user.master')

@section('title', $membership->name)

@section('content')

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="{{ asset('assets/user/img/breadcrumb-bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb-text">
                        <h2>{{ $membership->name }}</h2>
                        <div class="bt-option">
                            <a href="{{ route('user.home' , ['siteSetting' => $siteSetting->slug]) }}">Home</a>
                            <a href="{{ route('user.memberships.index' , ['siteSetting' => $siteSetting->slug]) }}">Memberships</a>
                            <span>{{ $membership->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Membership Details Section Begin -->
    <section class="pricing-section spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="ps-item text-center">
                        <h3>{{ $membership->name }}</h3>
                        <div class="pi-price">
                            <h2>$ {{ $membership->price }}</h2>
                            <span>{{ $membership->duration }}</span>
                        </div>
                        <p class="mb-4">{{ $membership->description }}</p>
                        
                        @if($membership->offers->count() > 0)
                            <div class="offers-section mb-4">
                                <h5 class="text-success">Special Offers Available!</h5>
                                @foreach($membership->offers as $offer)
                                    <div class="offer-item">
                                        <span class="badge bg-warning">{{ $offer->title }}</span>
                                        <small class="text-muted d-block">{{ $offer->description }}</small>
                                        @if($offer->remaining_days)
                                            <small class="text-danger">Expires in {{ $offer->remaining_days }} days</small>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <ul class="features-list mb-4">
                            @foreach ($membership->features as $feature)
                                <li><i class="fa fa-check text-success"></i> {{ $feature->name }}</li>
                            @endforeach
                        </ul>
                        
                        <div class="text-center">
                            @auth
                                <button type="button" class="btn btn-primary btn-lg enroll-btn" 
                                        data-membership-id="{{ $membership->id }}"
                                        data-membership-name="{{ $membership->name }}"
                                        data-membership-price="${{ $membership->price }}"
                                        data-offer-id="{{ $membership->offers->first()?->id ?? "" }}">
                                    Enroll Now
                                </button>
                            @else
                                <div class="d-grid gap-2">
                                    <a href="{{ route('auth.register.index') }}" class="btn btn-primary btn-lg">
                                        Create Account to Enroll
                                    </a>
                                    <a href="{{ route('auth.login.index') }}" class="btn btn-outline-primary">
                                        Already have an account? Login
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Membership Details Section End -->

    <!-- Trainers Section Begin -->
    @if($trainers->count() > 0)
        <section class="team-section spad">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <span>Our Expert Trainers</span>
                            <h2>TRAIN WITH EXPERTS</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="ts-slider owl-carousel">
                        @foreach ($trainers as $trainer)
                            <x-our-team-card :trainer="$trainer" />
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- Trainers Section End -->

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Complete Your Enrollment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="enrollment-summary mb-4">
                        <h6>Enrollment Summary</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Membership:</strong> <span id="modal-membership-name"></span></p>
                                <p><strong>Price:</strong> $<span id="modal-membership-price"></span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Duration:</strong> <span id="modal-membership-duration"></span></p>
                                @if($membership->offers->count() > 0)
                                    <p><strong>Offer Applied:</strong> <span class="text-success">{{ $membership->offers->first()->title }}</span></p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div id="stripe-payment-form">
                        <div id="card-element" class="form-control mb-3"></div>
                        <div id="card-errors" class="text-danger mb-3" role="alert"></div>
                        
                        <button type="button" class="btn btn-primary w-100 border-0" id="submit-payment">
                            <span id="payment-button-text">Pay Now</span>
                            <div id="payment-spinner" class="spinner-border spinner-border-sm d-none" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('Js')
<script src="https://js.stripe.com/v3/"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Stripe
        const stripe = Stripe('{{ config("services.stripe.public") }}');
        const elements = stripe.elements();
        
        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });
        
        cardElement.mount('#card-element');
        
        // Handle enrollment button click
        document.querySelectorAll('.enroll-btn').forEach(button => {
            button.addEventListener('click', function() {
                console.log('Enrollment button clicked');
                const membershipId = this.dataset.membershipId;
                const membershipName = this.dataset.membershipName;
                const membershipPrice = this.dataset.membershipPrice;
                const offerId = this.dataset.offerId;
                
                console.log('Membership data:', { membershipId, membershipName, membershipPrice, offerId });
                
                // Update modal content
                document.getElementById('modal-membership-name').textContent = membershipName;
                document.getElementById('modal-membership-price').textContent = membershipPrice;
                document.getElementById('modal-membership-duration').textContent = '{{ $membership->duration }}';
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
                modal.show();
            });
        });
        
        // Handle payment submission
        document.getElementById('submit-payment').addEventListener('click', function() {
            console.log('Payment submission started');
            const button = this;
            const buttonText = document.getElementById('payment-button-text');
            const spinner = document.getElementById('payment-spinner');
            
            // Show loading state
            button.disabled = true;
            buttonText.textContent = 'Processing...';
            spinner.classList.remove('d-none');
            
            const paymentData = {
                membership_id: '{{ $membership->id }}',
                offer_id: '{{ $membership->offers->first()?->id ?? "" }}',
                site_setting_id: '{{ $siteSetting->id }}'
            };
            
            console.log('Payment data:', paymentData);
            console.log('Payment URL:', '{{ route("user.payments.create-intent", ["siteSetting" => $siteSetting->slug]) }}');
            
            // Create payment intent
            fetch('{{ route("user.payments.create-intent", ["siteSetting" => $siteSetting->slug]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(paymentData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Payment intent response:', data);
                if (data.success) {
                    // Confirm payment with Stripe
                    return stripe.confirmCardPayment(data.data.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: '{{ auth()->user()->name ?? "" }}',
                                email: '{{ auth()->user()->email ?? "" }}'
                            }
                        }
                    });
                } else {
                    throw new Error(data.message || 'Failed to create payment intent');
                }
            })
            .then(result => {
                console.log('Stripe payment result:', result);
                if (result.error) {
                    // Show error
                    console.error('Payment error:', result.error);
                    document.getElementById('card-errors').textContent = result.error.message;
                    button.disabled = false;
                    buttonText.textContent = 'Pay Now';
                    spinner.classList.add('d-none');
                } else {
                    // Payment successful
                    console.log('Payment successful:', result.paymentIntent);
                    if (result.paymentIntent.status === 'succeeded') {
                        // Redirect to success page or show success message
                        window.location.href = '{{ route("user.memberships.success", ["siteSetting" => $siteSetting->slug]) }}?payment_intent=' + result.paymentIntent.id;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('card-errors').textContent = error.message;
                button.disabled = false;
                buttonText.textContent = 'Pay Now';
                spinner.classList.add('d-none');
            });
        });
    });
</script>
@endsection
