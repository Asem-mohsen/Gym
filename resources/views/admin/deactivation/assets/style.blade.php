<style>
    .preview-modal .modal-body {
        max-height: 400px;
        overflow-y: auto;
    }
    
    .data-summary {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .data-summary h6 {
        color: #495057;
        margin-bottom: 10px;
    }
    
    .data-summary .badge {
        font-size: 0.8em;
    }

    /* Enhanced Sidebar Styling */
    .policy-doc-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
        font-weight: 500;
    }

    .policy-doc-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .accordion-button {
        border-radius: 8px !important;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e3f2fd !important;
        color: #1976d2 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .accordion-button:hover {
        background-color: #f5f5f5 !important;
    }

    .accordion-body {
        border-radius: 0 0 8px 8px;
    }

    .card.shadow-sm {
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header.bg-primary {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%) !important;
    }

    .text-primary.fw-bold {
        color: #1976d2 !important;
    }

    .alert-info {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-left: 4px solid #1976d2;
    }

    .list-unstyled li {
        transition: all 0.2s ease;
    }

    .list-unstyled li:hover {
        transform: translateX(5px);
    }

    /* Custom scrollbar for sidebar */
    .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .card-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .card-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>