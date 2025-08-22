<style>
    .service-details-section {
        background: #151515;
        min-height: 100vh;
    }
    
    .service-details-card {
        background: #151515;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 30px;
    }
    
    .service-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .service-main-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
    }
    
    .service-placeholder {
        height: 300px;
        background: #1a1a1a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #666;
        border: 1px solid #333;
        border-radius: 8px;
    }
    
    .service-title {
        color: #fff;
        margin-bottom: 5px;
    }
    
    .service-subtitle {
        color: #ccc;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
    
    .service-meta {
        margin-bottom: 20px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .meta-item i {
        margin-right: 10px;
        color: #007bff;
        width: 20px;
    }
    
    .meta-item span {
        color: #fff;
    }
    
    .service-description h4 {
        color: #fff;
        margin-bottom: 10px;
    }
    
    .service-description p {
        color: #ccc;
    }
    
    .branches-section, .gallery-section {
        background: #151515;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 30px;
    }
    
    .branches-section h3, .gallery-section h3 {
        color: #fff;
        margin-bottom: 20px;
    }
    
    .branch-card {
        border: 1px solid #333;
        border-radius: 8px;
        padding: 20px;
        background: #1a1a1a;
        transition: border-color 0.2s ease;
    }
    
    .branch-card:hover {
        border-color: #007bff;
    }
    
    .branch-info h5 {
        color: #fff;
        margin-bottom: 10px;
    }
    
    .branch-location, .branch-type {
        color: #ccc;
        margin-bottom: 5px;
    }
    
    .branch-location i, .branch-type i {
        margin-right: 8px;
        color: #007bff;
    }
    
    .gallery-container {
        margin-bottom: 30px;
    }
    
    .gallery-container h5 {
        color: #fff;
        margin-bottom: 10px;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #333;
    }
    
    .gallery-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .gallery-image:hover {
        transform: scale(1.05);
    }
    
    .booking-sidebar {
        position: sticky;
        top: 20px;
    }
    
    .booking-card {
        background: #151515;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 25px;
    }
    
    .booking-card h4 {
        color: #fff;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .booking-price {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .price-label {
        color: #ccc;
        font-size: 0.9rem;
    }
    
    .price-value {
        color: #f36100;
        font-size: 1.5rem;
        font-weight: bold;
        margin-left: 10px;
    }
    
    .payment-options {
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 15px;
        display: flex;
        border-radius: 8px;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }
    
    .payment-option {
        display: flex;
        align-items: center;
    }
    
    .payment-radio {
        display: none; /* Hide default radio button */
    }
    
    .payment-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: #fff;
        padding: 10px 15px;
        border: 1px solid #333;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .payment-label:hover {
        border-color: #f36001;
    }
    
    .payment-label i {
        margin-right: 10px;
        color: #f36001;
    }
    
    .payment-radio:checked + .payment-label {
        border-color: #f36001;
        background-color: #151515;
        color: #fff;
    }
    
    .form-label {
        color: #fff;
    }
    
    .form-control {
        background: #1a1a1a;
        border: 1px solid #333;
        color: #fff;
    }
    
    .booking-sidebar .form-control {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 5px 5px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        background-color: #151515;
        color: white;
        height: 50px;
    }
    
    .form-control:focus {
        background: #1a1a1a;
        border-color: #007bff;
        color: #fff;
        box-shadow: none;
    }
    
    .form-control option {
        background: #1a1a1a;
        color: #fff;
    }
    
    .btn-block {
        padding: 12px;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .booking-sidebar .btn-primary {
        background: #f36100;
        border: none;
        padding: 15px 30px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .booking-info {
        text-align: center;
    }
    
    .booking-info small {
        color: #999;
    }
    
    @media (max-width: 768px) {
        .booking-sidebar {
            position: static;
            margin-top: 30px;
        }
    }
</style>