@extends('layout.admin.master')

@section('title' , 'Payments Dashboard')

@section('main-breadcrumb', 'Payments')
@section('main-breadcrumb-link', route('payments.index'))

@section('sub-breadcrumb', 'Analytics Dashboard')

@section('css')
    @include('admin.payments.assets.styles')
@endsection

@section('toolbar-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentGatewayModal">
        <i class="fas fa-cog me-2"></i>Payment Gateway Settings
    </button>
@endsection

@section('content')

<div class="container-fluid py-4">
    <!-- Summary Cards Row -->
    <div class="row mb-4">
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-success rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-up text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Income</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_paid']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success-subtle text-success">
                            {{ count($payments['newest_transactions']) }} transactions
                        </span>
                        <small class="text-muted">This month</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-warning rounded-circle p-3 me-3">
                            <i class="fas fa-clock text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Pending Payments</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_pending']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-warning-subtle text-warning">
                            {{ count($payments['newest_transactions']->where('status', 'pending')) }} pending
                        </span>
                        <small class="text-muted">Awaiting</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-danger rounded-circle p-3 me-3">
                            <i class="fas fa-times text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Failed Payments</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_failed']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-danger-subtle text-danger">
                            {{ count($payments['failed_transactions']) }} failed
                        </span>
                        <small class="text-muted">Need attention</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-sm-6 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-shape bg-info rounded-circle p-3 me-3">
                            <i class="fas fa-arrow-down text-white fs-5"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1 fw-semibold">Total Outcome</h6>
                            <h4 class="text-dark mb-0 fw-bold">{{ number_format($payments['total_outcome']) }} EGP</h4>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-info-subtle text-info">
                            0 expenses
                        </span>
                        <small class="text-muted">Recorded</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Monthly Revenue Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                Monthly Revenue Trend
                            </h5>
                            <p class="text-muted mb-0">{{ date('Y') }} Revenue Growth</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success-subtle text-success">
                                Growing
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="monthlyRevenueChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Revenue Sources Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            Revenue Sources
                        </h5>
                        <p class="text-muted mb-0">Breakdown by Service Type</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="revenueSourcesChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods and Recent Transactions -->
    <div class="row mb-4">
        <!-- Payment Methods -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">
                            Payment Methods
                        </h5>
                        <p class="text-muted mb-0">Distribution by Method</p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div id="paymentMethodsChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold text-dark">
                                Recent Transactions
                            </h5>
                            <p class="text-muted mb-0">Latest Payment Activities</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 ps-4 fw-semibold">Customer</th>
                                    <th class="border-0 fw-semibold">Service</th>
                                    <th class="border-0 text-center fw-semibold">Amount</th>
                                    <th class="border-0 text-center fw-semibold">Status</th>
                                    <th class="border-0 text-center fw-semibold">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments['newest_transactions'] as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <img src="{{ $transaction->user->user_image }}" class="rounded-circle" alt="user" width="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $transaction->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                {{ class_basename($transaction->paymentable->bookable_type) ?? 'N/A' }}
                                            </span>
                                            <div class="fw-semibold">{{ $transaction->paymentable->bookable->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-success">{{ number_format($transaction->amount) }} EGP</span>
                                    </td>
                                    <td class="text-center">
                                        @if($transaction->status == 'completed')
                                            <span class="badge bg-success text-white">Paid</span>
                                        @elseif($transaction->status == 'pending')
                                            <span class="badge bg-warning text-white">Pending</span>
                                        @elseif($transaction->status == 'failed')
                                            <span class="badge bg-danger text-white">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $transaction->created_at->format('M d') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('Y') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-2x mb-3"></i>
                                            <p class="mb-0">No transactions found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Failed Transactions -->
    @if(count($payments['failed_transactions']) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 bg-white">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-danger rounded-circle p-2 me-3">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-bold text-danger">Failed Transactions</h5>
                            <p class="text-muted mb-0">Requires immediate attention</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-danger">
                                <tr>
                                    <th class="border-0 ps-4 fw-semibold">Customer</th>
                                    <th class="border-0 fw-semibold">Service</th>
                                    <th class="border-0 text-center fw-semibold">Amount</th>
                                    <th class="border-0 text-center fw-semibold">Failed Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments['failed_transactions'] as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar me-3">
                                                <img src="{{ $transaction->user->user_image }}" class="rounded-circle" alt="user" width="40">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold">{{ $transaction->user->name ?? 'N/A' }}</h6>
                                                <small class="text-muted">{{ $transaction->user->email ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge bg-primary-subtle text-primary mb-1">
                                                {{ class_basename($transaction->paymentable->bookable_type) ?? 'N/A' }}
                                            </span>
                                            <div class="fw-semibold">{{ $transaction->paymentable->bookable->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="fw-bold text-danger">{{ number_format($transaction->amount) }} EGP</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold">{{ $transaction->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Payment Gateway Selection Modal -->
<div class="modal fade" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="paymentGatewayModalLabel">
                        Payment Gateway Settings
                    </h5>
                    <p class="text-muted mb-0">Choose your preferred payment gateway for processing transactions</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Current Selection Alert -->
                <div class="alert current-gateway-alert border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-3 fs-5"></i>
                        <div>
                            <div class="fw-semibold mb-1">Current Gateway: <span id="currentGateway">{{ ucfirst($currentGateway) }}</span></div>
                            <small class="opacity-75">You can change this anytime to switch payment providers</small>
                        </div>
                    </div>
                </div>

                <!-- Gateway Comparison -->
                <div class="row g-4">
                    <!-- Paymob Card -->
                    <div class="col-md-4">
                        <div class="gateway-card h-100" data-gateway="paymob">
                            <div class="gateway-header d-flex align-items-center">
                                <div class="gateway-logo">
                                    <img src="https://paymob.com/images/logoC.png" alt="Paymob">
                                </div>
                                <div>
                                    <h6>Paymob</h6>
                                    <small>Egypt's Leading Payment Gateway</small>
                                </div>
                            </div>
                            
                            <!-- Features -->
                            <div class="gateway-features">
                                <h6>
                                    <i class="fas fa-list me-2"></i>Supported Payment Methods
                                </h6>
                                <ul>
                                    <li><i class="fas fa-check"></i>Mobile Wallets (Vodafone Cash, Orange Cash, Etisalat Cash)</li>
                                    <li><i class="fas fa-check"></i>Debit Cards (All Egyptian Banks)</li>
                                    <li><i class="fas fa-check"></i>Credit Cards (Visa, MasterCard)</li>
                                    <li><i class="fas fa-check"></i>Bank Installments</li>
                                    <li><i class="fas fa-check"></i>Cash Collection</li>
                                    <li><i class="fas fa-check"></i>Local Support in Arabic</li>
                                </ul>
                            </div>

                            <!-- Pros & Cons -->
                            <div class="pros-cons">
                                <div class="pros mb-3">
                                    <h6>
                                        <i class="fas fa-thumbs-up me-2"></i>Best For
                                    </h6>
                                    <ul>
                                        <li>Egyptian market businesses</li>
                                        <li>Customers using mobile wallets</li>
                                        <li>Cost-conscious operations</li>
                                        <li>Arabic-speaking support needs</li>
                                    </ul>
                                </div>
                                <div class="cons">
                                    <h6>
                                        <i class="fas fa-info-circle me-2"></i>Limitations
                                    </h6>
                                    <ul>
                                        <li>Basic payment interface design</li>
                                        <li>Limited international reach</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Selection Radio -->
                            <div class="gateway-radio">
                                <input type="radio" name="gateway" value="paymob" id="paymob" {{ $currentGateway === 'paymob' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Fawry Card -->
                    <div class="col-md-4">
                        <div class="gateway-card h-100" data-gateway="fawry">
                            <div class="gateway-header d-flex align-items-center">
                                <div class="gateway-logo">
                                    <img src="https://atfawry.fawrystaging.com/fawry/assets/img/fawry-logo.svg" alt="Fawry">
                                </div>
                                <div>
                                    <h6>Fawry</h6>
                                    <small>Egypt's Trusted Payment Network</small>
                                </div>
                            </div>
                            
                            <!-- Features -->
                            <div class="gateway-features">
                                <h6>
                                    <i class="fas fa-list me-2"></i>Supported Payment Methods
                                </h6>
                                <ul>
                                    <li><i class="fas fa-check"></i>Cash Collection (Fawry Points)</li>
                                    <li><i class="fas fa-check"></i>ATM Payments</li>
                                    <li><i class="fas fa-check"></i>Bank Transfer</li>
                                    <li><i class="fas fa-check"></i>Mobile Wallets</li>
                                    <li><i class="fas fa-check"></i>Debit/Credit Cards</li>
                                    <li><i class="fas fa-check"></i>Wide Network Coverage</li>
                                </ul>
                            </div>

                            <!-- Pros & Cons -->
                            <div class="pros-cons">
                                <div class="pros mb-3">
                                    <h6>
                                        <i class="fas fa-thumbs-up me-2"></i>Best For
                                    </h6>
                                    <ul>
                                        <li>Cash-preferred customers</li>
                                        <li>Wide accessibility across Egypt</li>
                                        <li>Trusted brand recognition</li>
                                        <li>Offline payment options</li>
                                    </ul>
                                </div>
                                <div class="cons">
                                    <h6>
                                        <i class="fas fa-info-circle me-2"></i>Limitations
                                    </h6>
                                    <ul>
                                        <li>Requires physical presence for cash</li>
                                        <li>Longer settlement periods</li>
                                        <li>Limited international reach</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Selection Radio -->
                            <div class="gateway-radio">
                                <input type="radio" name="gateway" value="fawry" id="fawry" {{ $currentGateway === 'fawry' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Card -->
                    <div class="col-md-4">
                        <div class="gateway-card h-100 coming-soon" data-gateway="stripe">
                            <div class="gateway-header d-flex align-items-center">
                                <div class="gateway-logo">
                                    <img src="https://images.ctfassets.net/fzn2n1nzq965/HTTOloNPhisV9P4hlMPNA/cacf1bb88b9fc492dfad34378d844280/Stripe_icon_-_square.svg" alt="Stripe">
                                </div>
                                <div>
                                    <h6>Stripe <span class="badge bg-warning text-dark ms-2">Coming Soon</span></h6>
                                    <small>Global Payment Platform</small>
                                </div>
                            </div>
                            
                            <!-- Features -->
                            <div class="gateway-features">
                                <h6>
                                    <i class="fas fa-list me-2"></i>Supported Payment Methods
                                </h6>
                                <ul>
                                    <li><i class="fas fa-check"></i>Credit Cards (Visa, MasterCard, Amex)</li>
                                    <li><i class="fas fa-check"></i>International Payments</li>
                                    <li><i class="fas fa-check"></i>Advanced Fraud Protection</li>
                                    <li><i class="fas fa-check"></i>Subscription Management</li>
                                    <li><i class="fas fa-times"></i>No Mobile Wallets in Egypt</li>
                                    <li><i class="fas fa-times"></i>Limited Debit Card Support</li>
                                </ul>
                            </div>

                            <!-- Pros & Cons -->
                            <div class="pros-cons">
                                <div class="pros mb-3">
                                    <h6>
                                        <i class="fas fa-thumbs-up me-2"></i>Best For
                                    </h6>
                                    <ul>
                                        <li>International businesses</li>
                                        <li>Premium user experience</li>
                                        <li>Advanced analytics needs</li>
                                        <li>Global market expansion</li>
                                    </ul>
                                </div>
                                <div class="cons">
                                    <h6>
                                        <i class="fas fa-info-circle me-2"></i>Limitations
                                    </h6>
                                    <ul>
                                        <li>Higher transaction fees</li>
                                        <li>No mobile wallets support</li>
                                        <li>Limited for Egyptian customers</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Coming Soon Overlay -->
                            <div class="coming-soon-overlay">
                                <div class="text-center">
                                    <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                    <h6 class="text-warning">Stripe Coming Soon</h6>
                                    <small class="text-muted">This gateway will be available in the next update</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendation -->
                <div class="alert recommendation-alert border-0 mt-4" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-lightbulb me-3 fs-5"></i>
                        <div>
                            <div class="fw-semibold mb-2">Our Recommendation</div>
                            <p class="mb-0 small">For Egyptian businesses, we recommend <strong>Paymob</strong> for digital payments or <strong>Fawry</strong> for cash-based transactions. Paymob supports all local payment methods including mobile wallets, while Fawry provides excellent cash collection options with wide network coverage across Egypt.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="saveGatewayBtn">
                    <i class="fas fa-check me-2"></i>Save Settings
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    @include('admin.payments.assets.scripts')
@endsection

