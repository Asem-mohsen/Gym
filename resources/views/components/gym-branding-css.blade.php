@if(!empty($gymCssVariables))
<style>
    :root {
        {!! $gymCssVariables !!}
    }
    
    /* Apply font family globally */
    body {
        font-family: var(--color-font-family, 'Inter, system-ui, -apple-system, sans-serif');
    }
    
    /* Apply border radius to common elements */
    .btn, .form-control, .card, .modal-content {
        border-radius: var(--color-border-radius, 0.375rem);
    }
    
    /* Apply box shadow to cards and modals */
    .card, .modal-content {
        box-shadow: var(--color-box-shadow, 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075));
    }
</style>
@endif


