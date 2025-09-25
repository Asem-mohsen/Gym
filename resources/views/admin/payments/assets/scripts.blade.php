<!-- Highcharts Scripts -->
<script src="{{ asset('assets/admin/plugins/custom/highcharts/highcharts.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/highcharts-more.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/exporting.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/export-data.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/custom/highcharts/modules/accessibility.min.js') }}"></script>
<script>
    // Monthly Revenue Chart
    Highcharts.chart('monthlyRevenueChart', {
        chart: {
            type: 'area',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            labels: {
                style: {
                    color: '#6c757d'
                }
            }
        },
        yAxis: {
            title: {
                text: null
            },
            labels: {
                formatter: function() {
                    return this.value.toLocaleString() + ' EGP';
                },
                style: {
                    color: '#6c757d'
                }
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillOpacity: 0.1,
                marker: {
                    radius: 3
                },
                lineWidth: 2
            }
        },
        series: [{
            name: 'Revenue',
            data: [
                {{ $payments['monthly_revenue']->get(1, 0) }},
                {{ $payments['monthly_revenue']->get(2, 0) }},
                {{ $payments['monthly_revenue']->get(3, 0) }},
                {{ $payments['monthly_revenue']->get(4, 0) }},
                {{ $payments['monthly_revenue']->get(5, 0) }},
                {{ $payments['monthly_revenue']->get(6, 0) }},
                {{ $payments['monthly_revenue']->get(7, 0) }},
                {{ $payments['monthly_revenue']->get(8, 0) }},
                {{ $payments['monthly_revenue']->get(9, 0) }},
                {{ $payments['monthly_revenue']->get(10, 0) }},
                {{ $payments['monthly_revenue']->get(11, 0) }},
                {{ $payments['monthly_revenue']->get(12, 0) }}
            ],
            color: '#198754'
        }],
        tooltip: {
            formatter: function() {
                return '<b>' + this.x + '</b><br/>' +
                       '<span style="color:' + this.color + '">●</span> Revenue: <b>' + 
                       this.y.toLocaleString() + ' EGP</b>';
            }
        },
        credits: {
            enabled: false
        }
    });
    
    // Revenue Sources Pie Chart
    Highcharts.chart('revenueSourcesChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f}%',
                    style: {
                        color: '#6c757d'
                    }
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Revenue',
            colorByPoint: true,
            data: [
                @foreach($payments['revenue_by_type'] as $type => $amount)
                {
                    name: '{{ $type }}',
                    y: {{ $amount }},
                    color: Highcharts.getOptions().colors[{{ $loop->index }}]
                },
                @endforeach
            ]
        }],
        tooltip: {
            formatter: function() {
                return '<b>' + this.point.name + '</b><br/>' +
                       'Revenue: <b>' + this.y.toLocaleString() + ' EGP</b> (' + this.percentage.toFixed(1) + '%)';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            itemStyle: {
                color: '#6c757d'
            }
        },
        credits: {
            enabled: false
        }
    });
    
    // Payment Methods Chart
    Highcharts.chart('paymentMethodsChart', {
        chart: {
            type: 'pie',
            backgroundColor: 'transparent'
        },
        title: {
            text: null
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
                    style: {
                        color: '#6c757d'
                    }
                },
                showInLegend: true
            }
        },
        series: [{
            name: 'Payment Methods',
            colorByPoint: true,
            data: [
                @foreach($payments['payment_methods'] as $method)
                {
                    name: '{{ $method->payment_method }}',
                    y: {{ $method->total_amount }},
                    color: '{{ $method->payment_method == "Card" ? "#198754" : "#0d6efd" }}'
                },
                @endforeach
            ]
        }],
        tooltip: {
            formatter: function() {
                return '<b>' + this.point.name + '</b><br/>' +
                       'Amount: <b>' + this.y.toLocaleString() + ' EGP</b> (' + this.percentage.toFixed(1) + '%)';
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            itemStyle: {
                color: '#6c757d'
            }
        },
        credits: {
            enabled: false
        }
    });

    // Payment Gateway Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const gatewayCards = document.querySelectorAll('.gateway-card');
        const gatewayRadios = document.querySelectorAll('input[name="gateway"]');
        const saveBtn = document.getElementById('saveGatewayBtn');
        const currentGatewaySpan = document.getElementById('currentGateway');

        gatewayCards.forEach(card => {
            card.addEventListener('click', function() {
                if (this.classList.contains('coming-soon')) {
                    return;
                }
                
                const gateway = this.dataset.gateway;
                const radio = document.getElementById(gateway);
                
                gatewayCards.forEach(c => c.classList.remove('border-primary', 'bg-light'));
                gatewayRadios.forEach(r => r.checked = false);
                
                this.classList.add('border-primary', 'bg-light');
                radio.checked = true;
            });
        });

        gatewayRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    gatewayCards.forEach(card => {
                        card.classList.remove('border-primary', 'bg-light');
                        if (card.dataset.gateway === this.value) {
                            card.classList.add('border-primary', 'bg-light');
                        }
                    });
                }
            });
        });

        saveBtn.addEventListener('click', function() {
            const selectedGateway = document.querySelector('input[name="gateway"]:checked');
            
            if (!selectedGateway) {
                alert('Please select a payment gateway');
                return;
            }

            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            this.disabled = true;

            fetch('{{ route("admin.payment-gateway.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    gateway: selectedGateway.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentGatewaySpan.textContent = selectedGateway.value.charAt(0).toUpperCase() + selectedGateway.value.slice(1);
                    
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert recommendation-alert alert-dismissible fade show gateway-success-alert';
                    alertDiv.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3 fs-5"></i>
                            <div>
                                <div class="fw-semibold">Success!</div>
                                <small>Payment gateway updated to ${selectedGateway.value.charAt(0).toUpperCase() + selectedGateway.value.slice(1)}.</small>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    
                    const modalBody = document.querySelector('#paymentGatewayModal .modal-body');
                    modalBody.insertBefore(alertDiv, modalBody.firstChild);
                    
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.remove();
                        }
                    }, 3000);
                } else {
                    toastr.error('Error saving gateway preference: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                toastr.error('Error saving gateway preference. Please try again.');
            })
            .finally(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });

        const defaultRadio = document.querySelector('input[name="gateway"]:checked');
        if (defaultRadio) {
            const defaultCard = document.querySelector(`[data-gateway="${defaultRadio.value}"]`);
            if (defaultCard) {
                defaultCard.classList.add('border-primary', 'bg-light');
            }
        }
    });
</script>