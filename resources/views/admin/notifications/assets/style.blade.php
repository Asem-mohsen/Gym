<style>
    .role-selection-card {
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .role-card-label {
        display: block;
        width: 100%;
        cursor: pointer;
        margin: 0;
    }
    
    .role-card-content {
        background: #fff;
        border: 2px solid #e4e6ea;
        border-radius: 12px;
        padding: 20px 15px;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
        height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .role-selection-card:hover .role-card-content {
        border-color: #007bff;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
        transform: translateY(-2px);
    }
    
    .role-checkbox:checked + .role-card-label .role-card-content {
        border-color: #007bff;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
        transform: translateY(-2px);
    }
    
    .role-checkbox:checked + .role-card-label .role-card-content .role-icon i {
        color: white !important;
    }
    
    .role-checkbox:checked + .role-card-label .role-card-content .role-description {
        color: rgba(255, 255, 255, 0.8);
    }
    
    .role-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .role-name {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 5px;
        color: #2c3e50;
        transition: all 0.3s ease;
    }
    
    .role-description {
        font-size: 0.85rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .role-check-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s ease;
        color: white;
        font-size: 1.2rem;
    }
    
    .role-checkbox:checked + .role-card-label .role-check-indicator {
        opacity: 1;
        transform: scale(1);
    }
    
    .role-selection-card:active .role-card-content {
        transform: translateY(0);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .role-card-content {
            height: 120px;
            padding: 15px 10px;
        }
        
        .role-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }
        
        .role-name {
            font-size: 0.9rem;
        }
        
        .role-description {
            font-size: 0.8rem;
        }
    }
    
    /* Animation for selection */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
        }
    }
    
    .role-checkbox:checked + .role-card-label .role-card-content {
        animation: pulse 0.6s ease-out;
    }
</style>