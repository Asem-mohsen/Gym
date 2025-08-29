<style>
    .checkin-section {
        background: #151515;
        padding: 80px 0;
    }
    
    .welcome-card {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        background: linear-gradient(135deg, #f36100, #e55a00);
        color: white;
        margin-bottom: 30px;
    }
    
    .welcome-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }
    
    .checkin-status {
        margin-bottom: 25px;
    }
    
    .status-info {
        padding: 15px;
        border-radius: 10px;
        background: rgba(243, 97, 0, 0.1);
        border-left: 4px solid #f36100;
        color: white;
    }
    
    .checkin-instructions {
        background: rgba(255, 193, 7, 0.1);
        border: 1px solid rgba(255, 193, 7, 0.3);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .checkin-instructions h6 {
        color: #f36100;
        margin-bottom: 10px;
    }
    
    .qr-card {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .qr-icon {
        font-size: 3rem;
        color: #f36100;
        margin-bottom: 20px;
    }
    
    .qr-display {
        margin-top: 20px;
    }
    
    .qr-placeholder {
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.1);
        border: 2px dashed rgba(255,255,255,0.3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
        color: rgba(255,255,255,0.7);
    }
    
    .quick-actions, .checkin-info {
        padding: 25px;
        border-radius: 15px;
        background: #252525;
        border: 1px solid #363636;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .info-list {
        list-style: none;
        padding: 0;
    }
    
    .info-list li {
        padding: 8px 0;
        border-bottom: 1px solid #363636;
    }
    
    .info-list li:last-child {
        border-bottom: none;
    }
    
    .primary-btn {
        background: #f36100;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .primary-btn:hover {
        background: #e55a00;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .secondary-btn {
        background: transparent;
        color: #f36100;
        border: 2px solid #f36100;
        padding: 12px 25px;
        border-radius: 8px;
        text-decoration: none;
        display: block;
        text-align: center;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .secondary-btn:hover {
        background: #f36100;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
</style>