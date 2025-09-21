<style>
    .icon-shape {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        overflow: hidden;
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.02);
    }
    
    .card {
        border-radius: 8px;
    }

    /* Payment Gateway Modal Styles */
    #paymentGatewayModal .modal-dialog {
        max-width: 1000px;
    }

    #paymentGatewayModal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    #paymentGatewayModal .modal-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border: none;
        padding: 2rem 2rem 1.5rem;
    }

    #paymentGatewayModal .modal-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    #paymentGatewayModal .modal-body {
        padding: 2rem;
        background-color: #fafbfc;
    }

    #paymentGatewayModal .modal-footer {
        background-color: #ffffff;
        border: none;
        padding: 1.5rem 2rem;
    }

    /* Gateway Cards */
    .gateway-card {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e1e5e9 !important;
        border-radius: 12px;
        background: #ffffff;
        position: relative;
        overflow: hidden;
    }

    .gateway-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: transparent;
        transition: all 0.3s ease;
    }

    .gateway-card:hover {
        border-color: #d1d9e0 !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transform: translateY(-4px);
    }

    .gateway-card.border-primary {
        border-color: #3b82f6 !important;
        box-shadow: 0 12px 30px rgba(59, 130, 246, 0.15);
        transform: translateY(-4px);
    }

    .gateway-card.border-primary::before {
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }

    .gateway-card.bg-light {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }

    /* Gateway Header */
    .gateway-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 0;
    }

    .gateway-logo {
        width: 48px;
        height: 48px;
        background: #f8fafc;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .gateway-logo img {
        max-height: 28px;
        max-width: 36px;
        object-fit: contain;
    }

    .gateway-header h6 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .gateway-header small {
        color: #64748b;
        font-size: 0.875rem;
    }

    /* Features Section */
    .gateway-features {
        padding: 0 1.5rem 1rem;
    }

    .gateway-features h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .gateway-features ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .gateway-features li {
        font-size: 0.875rem;
        line-height: 1.6;
        color: #4b5563;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: flex-start;
    }

    .gateway-features li i {
        margin-right: 0.75rem;
        margin-top: 0.125rem;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .gateway-features li i.fa-check {
        color: #10b981;
    }

    .gateway-features li i.fa-times {
        color: #ef4444;
    }

    /* Pros and Cons */
    .pros-cons {
        padding: 0 1.5rem 1.5rem;
    }

    .pros-cons h6 {
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .pros-cons ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pros-cons li {
        font-size: 0.8rem;
        line-height: 1.5;
        color: #6b7280;
        margin-bottom: 0.5rem;
        padding-left: 1rem;
        position: relative;
    }

    .pros-cons li::before {
        content: '•';
        position: absolute;
        left: 0;
        color: #9ca3af;
    }

    .pros h6 {
        color: #059669;
    }

    .cons h6 {
        color: #d97706;
    }

    /* Radio Button */
    .gateway-radio {
        position: absolute;
        top: 1rem;
        right: 1rem;
    }

    .gateway-radio input[type="radio"] {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .gateway-radio input[type="radio"]:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }

    .gateway-radio input[type="radio"]:hover {
        border-color: #9ca3af;
    }

    /* Current Gateway Alert */
    .current-gateway-alert {
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 10px;
        color: #1e40af;
    }

    .current-gateway-alert i {
        color: #3b82f6;
    }

    /* Recommendation Alert */
    .recommendation-alert {
        background: linear-gradient(135deg, #f0fdf4 0%, #f7fee7 100%);
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        color: #166534;
    }

    .recommendation-alert i {
        color: #22c55e;
    }

    /* Buttons */
    #paymentGatewayModal .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }

    #paymentGatewayModal .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border: none;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    #paymentGatewayModal .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
    }

    #paymentGatewayModal .btn-light {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    #paymentGatewayModal .btn-light:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }

    /* Success Alert Animation */
    .gateway-success-alert {
        animation: slideInFromTop 0.4s ease-out;
    }

    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Coming Soon Overlay */
    .gateway-card.coming-soon {
        position: relative;
        opacity: 0.7;
        pointer-events: none;
    }

    .coming-soon-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        z-index: 10;
        backdrop-filter: blur(2px);
    }

    .coming-soon-overlay .text-center {
        padding: 2rem;
    }

    .coming-soon-overlay i {
        opacity: 0.8;
    }

    .coming-soon-overlay h6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .coming-soon-overlay small {
        font-size: 0.8rem;
        opacity: 0.8;
    }

    /* Responsive */
    @media (max-width: 768px) {
        #paymentGatewayModal .modal-dialog {
            max-width: 95%;
            margin: 1rem auto;
        }
        
        #paymentGatewayModal .modal-body {
            padding: 1.5rem;
        }
        
        .gateway-header {
            padding: 1rem;
        }
        
        .gateway-features,
        .pros-cons {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }
</style>