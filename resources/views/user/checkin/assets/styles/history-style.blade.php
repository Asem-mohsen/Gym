<style>
    .history-section {
        background: #151515;
        padding: 80px 0;
    }
    
    .user-info-card {
        text-align: center;
        padding: 30px;
        border-radius: 15px;
        background: linear-gradient(135deg, #f36100, #e55a00);
        color: white;
    }
    
    .user-avatar {
        font-size: 3rem;
        margin-bottom: 20px;
    }
    
    .stat-card {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        background: #252525;
        border: 1px solid #363636;
        margin-bottom: 15px;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #f36100;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #ffffff;
        font-size: 0.9rem;
    }
    
    .history-table-wrapper {
        background: #252525;
        border-radius: 15px;
        padding: 30px;
        border: 1px solid #363636;
    }
    
    .history-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .history-table th {
        background: #363636;
        color: #f36100;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #363636;
    }
    
    .history-table td {
        padding: 15px;
        border-bottom: 1px solid #363636;
    }
    
    .history-table tr:hover {
        background: #363636;
    }
    
    .checkin-time .date {
        font-weight: 600;
        color: #ffffff;
    }
    
    .checkin-time .time {
        font-size: 0.9rem;
        color: #cccccc;
    }
    
    .checkin-type {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .checkin-type.self-scan {
        background: rgba(243, 97, 0, 0.1);
        color: #f36100;
    }
    
    .checkin-type.gate-scan {
        background: rgba(243, 97, 0, 0.1);
        color: #f36100;
    }
    
    .branch-name {
        color: #cccccc;
        font-size: 0.9rem;
    }
    
    .ip-address {
        color: #cccccc;
        font-size: 0.85rem;
        font-family: monospace;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-badge.success {
        background: rgba(243, 97, 0, 0.1);
        color: #f36100;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #cccccc;
        margin-bottom: 20px;
    }
    
    .empty-state h5 {
        color: #ffffff;
        margin-bottom: 10px;
    }
    
    .empty-state p {
        color: #cccccc;
        margin-bottom: 30px;
    }
    
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    
    .pagination-wrapper .pagination {
        background: #252525;
        border-radius: 10px;
        padding: 10px;
        border: 1px solid #363636;
    }
    
    .pagination-wrapper .page-link {
        background: transparent;
        border: none;
        color: #ffffff;
        margin: 0 5px;
        border-radius: 8px;
        padding: 8px 12px;
    }
    
    .pagination-wrapper .page-link:hover {
        background: #f36100;
        color: white;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background: #f36100;
        color: white;
    }
    
    .primary-btn {
        background: #f36100;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
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
    }
    
    .secondary-btn:hover {
        background: #f36100;
        color: white;
        transform: translateY(-2px);
    }
</style>