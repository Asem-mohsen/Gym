<style>
    .membership-details-card {
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
        border: 1px solid #808080;
    }

    .membership-title {
        font-weight: 700;
        font-size: 2.5rem;
        color: white;
    }

    .membership-description{
        background: #262626;
        border-radius: 10px;
        padding: 20px;
    }

    .price-section {
        background: linear-gradient(1157deg, #141414 0%, #f36001 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin: 1rem 0;
    }

    .price-amount {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .price-period {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .subscription-status-alert {
        border: none;
        border-radius: 15px;
        /* background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); */
    }

    .section-title {
        color: white;
        font-weight: 600;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
    }

    .description-content {
        line-height: 1.8;
        color: #6c757d;
        font-size: 1.1rem;
    }

    .offers-grid {
        display: grid;
        gap: 1rem;
    }

    .offer-card {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 1rem;
        position: relative;
    }

    .offer-badge {
        background: #ffc107;
        color: #212529;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 0.5rem;
    }

    .offer-description {
        margin-bottom: 0.5rem;
        color: #856404;
    }

    .offer-expiry {
        font-size: 0.875rem;
        color: #dc3545;
    }

    .enroll-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .enroll-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .already-subscribed-message .btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 50px;
        padding: 1rem 2rem;
    }

    .guest-message .btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 1rem 2rem;
    }

    /* Features Sidebar Styles */
    .features-sidebar {
        position: sticky;
        top: 2rem;
    }

    .features-card {
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: 1px solid gray;
    }

    .features-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .features-title {
        color: white;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .features-subtitle {
        color: #d1d1d1;
        font-size: 0.95rem;
    }

    .features-list {
        margin-bottom: 2rem;
    }

    .feature-item {
        display: flex;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .feature-item:last-child {
        border-bottom: none;
    }

    .feature-icon {
        margin-right: 1rem;
        margin-top: 0.25rem;
    }

    .feature-icon i {
        font-size: 1.2rem;
    }

    .feature-content {
        flex: 1;
    }

    .feature-name {
        color: white;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .feature-description {
        color: white;
        font-size: 0.9rem;
        margin: 0;
    }

    .no-features {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }

    .no-features i {
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    /* Membership Summary */
    .membership-summary {
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
        border: 1px solid #373737;
    }

    .summary-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: white;
        text-align: center;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #6c757d;
        font-weight: 500;
    }

    .summary-value {
        color: white;
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 991.98px) {
        .features-sidebar {
            position: static;
            margin-top: 2rem;
        }

        .membership-title {
            font-size: 2rem;
        }

        .price-amount {
            font-size: 2rem;
        }

        .membership-details-card,
        .features-card {
            padding: 1.5rem;
        }
    }

    @media (max-width: 767.98px) {
        .membership-title {
            font-size: 1.75rem;
        }

        .price-amount {
            font-size: 1.75rem;
        }

        .membership-details-card,
        .features-card {
            padding: 1rem;
        }

        .enroll-btn,
        .already-subscribed-message .btn,
        .guest-message .btn {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>