<script>
    document.addEventListener('DOMContentLoaded', function() {
        const bookingForm = document.getElementById('bookingForm');
        const paymentMethodRadios = document.querySelectorAll('input[name="method"]');
        
        if (bookingForm) {
            bookingForm.addEventListener('submit', function(e) {
                const selectedPaymentMethod = document.querySelector('input[name="method"]:checked');
                
                if (document.querySelector('.payment-options') && !selectedPaymentMethod) {
                    e.preventDefault();
                    toastr.error('Please select a payment method.');
                    return;
                }
            });
        }
        
        const bookingDateInput = document.getElementById('booking_date');
        if (bookingDateInput) {
            const today = new Date().toISOString().split('T')[0];
            bookingDateInput.setAttribute('min', today);
        }
    });
</script>