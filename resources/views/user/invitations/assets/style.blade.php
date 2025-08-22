<style>
    .btn-primary {
        background: #f36100;
        border: none;
        font-size: 16px;
        font-weight: 600;
        padding: .375rem .75rem;
        border-radius: .25rem;
        transition: all 0.3s ease;
    }
    .btn-primary.focus, .btn-primary:focus
    {
        background-color: #f36100;
        border-color: #4f4945;
        box-shadow: 0 0 0 .2rem rgba(42, 44, 46, 0.5);
    }
    .form-control{
        height: 50px;
    }
    .form-control:focus{
        border-color: #4f4945;
        box-shadow: 0 0 0 .2rem rgb(255 229 212);
    }
    .pricing-section .btn-primary {
        padding: 15px 30px;
    }
    .btn-close{
        border: 1px solid gray;
        width: 18px;
    }
    .btn-primary:hover {
        background: #d54e00;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(243, 97, 0, 0.3);
    }
    
    /* Tab styling */
    .nav-tabs .nav-link {
        color: white !important;
        background-color: transparent !important;
        border: 1px solid #dee2e6;
        border-bottom: none;
    }
    
    .nav-tabs .nav-link.active {
        color: #f36100 !important;
        background-color: #fff !important;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    
    .nav-tabs .nav-link:hover {
        color: #f36100 !important;
        border-color: #e9ecef #e9ecef #dee2e6;
    }
    
    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal.show {
        display: block;
    }
    
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }
    
    .modal-backdrop.fade {
        opacity: 0;
    }
    
    .modal-backdrop.show {
        opacity: 0.5;
    }
    .table thead th, .table tbody td{
        color:white
    }

</style>