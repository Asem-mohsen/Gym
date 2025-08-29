<style>
    .personal-qr-section {
        background: #151515;
        padding: 80px 0;
    }
    
    .user-info-card {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        background: linear-gradient(135deg, #f36100, #e55a00);
        color: white;
        margin-bottom: 30px;
    }
    
    .user-avatar {
        font-size: 3rem;
        margin-bottom: 20px;
    }
    
    .qr-instructions {
        background: rgba(243, 97, 0, 0.1);
        border: 1px solid rgba(243, 97, 0, 0.3);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .qr-instructions h6 {
        color: #f36100;
        margin-bottom: 10px;
    }
    
    .qr-instructions p {
        color: #ffffff;
        margin-bottom: 0;
    }
    
    .qr-actions {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .qr-display-card {
        background: #252525;
        border-radius: 15px;
        padding: 30px;
        border: 1px solid #363636;
        text-align: center;
    }
    
    .qr-header h6 {
        color: #f36100;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }
    
    .qr-header p {
        color: #cccccc;
        margin-bottom: 25px;
    }
    
    .qr-code-container {
        background: #ffffff;
        border-radius: 10px;
        padding: 20px;
        margin: 0 auto 25px;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .qr-info {
        text-align: left;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #363636;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item .label {
        color: #cccccc;
        font-weight: 500;
    }
    
    .info-item .value {
        color: #ffffff;
        font-weight: 600;
    }
    
    .tips-card, .security-card {
        background: #252525;
        border-radius: 15px;
        padding: 25px;
        border: 1px solid #363636;
        height: 100%;
    }
    
    .tips-card h6, .security-card h6 {
        color: #f36100;
        margin-bottom: 20px;
    }
    
    .tips-list, .security-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li, .security-list li {
        padding: 8px 0;
        color: #ffffff;
        border-bottom: 1px solid #363636;
    }
    
    .tips-list li:last-child, .security-list li:last-child {
        border-bottom: none;
    }
    
    .tips-list li i, .security-list li i {
        color: #f36100;
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .primary-btn {
        background: #f36100;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    
    .primary-btn:hover {
        background: #e55a00;
        color: white;
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
        transition: all 0.3s ease;
        font-weight: 500;
        cursor: pointer;
    }
    
    .secondary-btn:hover {
        background: #f36100;
        color: white;
        transform: translateY(-2px);
    }
</style>