<script>
    function showBranchSelectionModal(branches, formData, processWithBranchUrl) {
        // Remove any existing popup first
        const existingPopup = document.getElementById('branch-selection-popup');
        if (existingPopup) {
            existingPopup.remove();
        }

        // Create modal HTML
        let modalHTML = `
            <div id="branch-selection-popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="branchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="branchModalLabel">
                                <i class="fa fa-map-marker-alt me-2"></i>
                                Select Your Preferred Branch
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="text-center mb-4">
                                <h6 class="text-muted">Choose the branch where you'd like to train</h6>
                                <p class="small text-muted">You can change your branch later from your account settings</p>
                            </div>
                            <div class="branch-list">
        `;

        branches.forEach((branch, index) => {
            modalHTML += `
                <div class="branch-item">
                    <div class="form-check branch-card">
                        <input class="form-check-input" type="radio" name="branch_id" id="branch_${branch.id}" value="${branch.id}" required ${index === 0 ? 'checked' : ''}>
                        <label class="form-check-label w-100" for="branch_${branch.id}">
                            <div class="branch-card-content p-3 border rounded ${index === 0 ? 'selected' : ''}" data-branch-id="${branch.id}">
                                <div class="d-flex align-items-center">
                                    <div class="branch-icon me-3">
                                        <i class="fa fa-building text-warning fa-2x"></i>
                                    </div>
                                    <div class="branch-details flex-grow-1">
                                        <h6 class="mb-1 fw-bold">${branch.name}</h6>
                                        <p class="mb-0 text-muted">
                                            <i class="fa fa-map-marker-alt me-1"></i>
                                            ${branch.location}
                                        </p>
                                    </div>
                                    <div class="branch-check">
                                        <i class="fa fa-check-circle" style="display: ${index === 0 ? 'block' : 'none'};"></i>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            `;
        });

        modalHTML += `
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-outline-secondary" id="cancel-branch-btn">
                                <i class="fa fa-times me-1"></i>
                                Cancel
                            </button>
                            <button type="button" class="btn btn-primary" id="confirm-branch-btn">
                                <i class="fa fa-credit-card me-1"></i>
                                Continue to Payment
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalHTML);

        // Show modal
        const modalElement = document.getElementById('branch-selection-popup');
        if (typeof $ !== 'undefined' && $.fn.modal) {
            // Use jQuery Bootstrap
            $('#branch-selection-popup').modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Use Bootstrap 5
            const popup = new bootstrap.Modal(modalElement);
            popup.show();
        } else {
            // Fallback: show manually
            modalElement.classList.add('show');
            modalElement.style.display = 'block';
            document.body.classList.add('modal-open');
            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }

        // Initialize modal functionality
        initializeBranchModal(formData, processWithBranchUrl);
    }

    function initializeBranchModal(formData, processWithBranchUrl) {
        const popupElement = document.getElementById('branch-selection-popup');
        if (!popupElement) return;

        // Handle branch selection with visual feedback
        const branchRadios = popupElement.querySelectorAll('input[name="branch_id"]');
        const confirmBtn = popupElement.querySelector('#confirm-branch-btn');
        const cancelBtn = popupElement.querySelector('#cancel-branch-btn');

        branchRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateBranchSelection(this.value);
            });
        });

        // Add click handlers for branch cards
        popupElement.querySelectorAll('.branch-card-content').forEach(card => {
            card.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const branchId = this.getAttribute('data-branch-id');
                const radio = document.getElementById('branch_' + branchId);
                if (radio) {
                    radio.checked = true;
                    updateBranchSelection(branchId);
                }
            });
        });

        function updateBranchSelection(selectedBranchId) {
            // Remove selected class from all branch cards
            popupElement.querySelectorAll('.branch-card-content').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Remove check icons
            popupElement.querySelectorAll('.branch-check .fa-check-circle').forEach(icon => {
                icon.style.display = 'none';
            });

            // Add selected class to selected branch card
            const selectedCard = popupElement.querySelector(`[data-branch-id="${selectedBranchId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
                
                // Show check icon
                const checkIcon = selectedCard.querySelector('.branch-check .fa-check-circle');
                if (checkIcon) {
                    checkIcon.style.display = 'block';
                }
            }
        }

        // Handle cancel button
        cancelBtn.addEventListener('click', function() {
            // Use jQuery if available, otherwise use Bootstrap 4/5 compatible method
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#branch-selection-popup').modal('hide');
            } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(popupElement);
                if (modal) {
                    modal.hide();
                } else {
                    // Fallback: hide manually
                    popupElement.classList.remove('show');
                    popupElement.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            }
        });

        // Handle confirm button
        confirmBtn.addEventListener('click', function() {
            const selectedBranch = popupElement.querySelector('input[name="branch_id"]:checked');
            if (!selectedBranch) {
                alert('Please select a branch');
                return;
            }

            // Create form data with branch_id
            const requestFormData = new FormData();
            for (const [key, value] of Object.entries(formData)) {
                requestFormData.append(key, value);
            }
            requestFormData.append('branch_id', selectedBranch.value);

            // Show loading state
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>';

            // Process payment with branch
            // Get CSRF token from meta tag or input
            let csrfToken = '';
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfToken = csrfMeta.getAttribute('content');
            } else {
                const csrfInput = document.querySelector('input[name="_token"]');
                if (csrfInput) {
                    csrfToken = csrfInput.value;
                }
            }

            fetch(processWithBranchUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: requestFormData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close popup and redirect to payment
                    if (typeof $ !== 'undefined' && $.fn.modal) {
                        $('#branch-selection-popup').modal('hide');
                    } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        const modal = bootstrap.Modal.getInstance(popupElement);
                        if (modal) {
                            modal.hide();
                        } else {
                            // Fallback: hide manually
                            popupElement.classList.remove('show');
                            popupElement.style.display = 'none';
                            document.body.classList.remove('modal-open');
                            const backdrop = document.querySelector('.modal-backdrop');
                            if (backdrop) backdrop.remove();
                        }
                    }
                    window.location.href = data.data.payment_url;
                } else {
                    throw new Error(data.message || 'Failed to process payment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment processing failed: ' + error.message);
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = '<i class="fa fa-credit-card me-1"></i>Continue to Payment';
            });
        });

        // Clean up popup when closed
        popupElement.addEventListener('hidden.bs.modal', function() {
            setTimeout(() => {
                if (this.parentNode) {
                    this.remove();
                }
            }, 150);
        });

        // Handle modal backdrop click
        popupElement.addEventListener('click', function(e) {
            if (e.target === this) {
                if (typeof $ !== 'undefined' && $.fn.modal) {
                    $('#branch-selection-popup').modal('hide');
                } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    const modal = bootstrap.Modal.getInstance(this);
                    if (modal) {
                        modal.hide();
                    } else {
                        // Fallback: hide manually
                        this.classList.remove('show');
                        this.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) backdrop.remove();
                    }
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-backdrop')) {
                if (typeof $ !== 'undefined' && $.fn.modal) {
                    $('#branch-selection-popup').modal('hide');
                } else {
                    const modalElement = document.getElementById('branch-selection-popup');
                    if (modalElement) {
                        modalElement.classList.remove('show');
                        modalElement.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        e.target.remove();
                    }
                }
            }
        });
    }
</script>

<style>
    .branch-card-content {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid #e9ecef;
        position: relative;
    }
    
    .branch-card-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        border-color: #f39c12;
    }
    
    .branch-card-content.selected {
        border-color: #f39c12;
        background-color: rgba(243, 156, 18, 0.05);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.2);
    }
    
    .branch-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(243, 156, 18, 0.1);
        border-radius: 50%;
    }
    
    .branch-check .fa-check-circle {
        font-size: 1.5rem;
        color: #f39c12;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
        color: white !important;
    }
    
    .btn-primary {
        background-color: #f39c12;
        border-color: #f39c12;
    }
    
    .btn-primary:hover {
        background-color: #e67e22;
        border-color: #e67e22;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    
    .branch-list {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .branch-item {
        flex: 1;
        min-width: 250px;
    }
    
    .branch-card {
        position: relative;
    }
    
    .branch-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .branch-card label {
        cursor: pointer;
        margin: 0;
    }
    
    .branch-card-content {
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .modal-backdrop {
        z-index: 1040;
    }

    .modal {
        z-index: 1050;
    }

    .modal-dialog {
        z-index: 1055;
    }
</style>
