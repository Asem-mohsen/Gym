<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentForm = document.getElementById('payment-form');
        if (!paymentForm) return;

        const enrollBtn = document.getElementById('enroll-btn');
        const buttonText = document.getElementById('button-text');
        const buttonSpinner = document.getElementById('button-spinner');

        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            enrollBtn.disabled = true;
            buttonText.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Initializing Payment...';
            buttonSpinner.classList.remove('d-none');
            
            const formData = new FormData(paymentForm);
            
            fetch(paymentForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.has_branches) {
                        showBranchSelectionPopup(data.branches, formData);
                    } else {
                        window.location.href = data.data.payment_url;
                    }
                } else {
                    throw new Error(data.message || 'Failed to initialize payment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to initialize payment. Please try again.');
            })
            .finally(() => {
                enrollBtn.disabled = false;
                buttonText.innerHTML = '<i class="fa fa-credit-card me-2"></i>Enroll Now';
                buttonSpinner.classList.add('d-none');
            });
        });

        function showBranchSelectionPopup(branches, formData) {
            const formDataObj = {};
            for (const [key, value] of formData.entries()) {
                formDataObj[key] = value;
            }
            
            showBranchSelectionModal(branches, formDataObj, '{{ route("user.payments.paymob.process-with-branch", ["siteSetting" => $siteSetting->slug]) }}');
        }
    });
</script>