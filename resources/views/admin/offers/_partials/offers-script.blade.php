<script>
    $(function () {
        //Initialize Multible Select Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    });

    $(document).ready(function() {
        $('#assign_to').change(function() {
            let selectedOptions = $(this).val();
    
            // Show or hide membership selection
            if (selectedOptions.includes("App\\Models\\Membership")) {
                $('#membership_container').removeClass('d-none');
                fetchMemberships();
            } else {
                $('#membership_container').addClass('d-none');
                $('#memberships').html('');
            }
    
            // Show or hide service selection
            if (selectedOptions.includes("App\\Models\\Service")) {
                $('#service_container').removeClass('d-none');
                fetchServices();
            } else {
                $('#service_container').addClass('d-none');
                $('#services').html('');
            }
        });
    
        function fetchMemberships() {
            $.ajax({
                url: "{{ route('offers.memberships') }}",
                type: "GET",
                success: function(data) {
                    let options = '<option value="all">All Memberships</option>'; 
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#memberships').html(options);
                }
            });
        }

        function fetchServices() {
            $.ajax({
                url: "{{ route('offers.services') }}",
                type: "GET",
                success: function(data) {
                    let options = '<option value="all">All Services</option>';
                    data.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#services').html(options);
                }
            });
        }

        function handleSelection(selectId) {
            $(document).on('change', selectId, function () {
                let selectedValues = $(this).val() || [];
                if (selectedValues.includes('all')) {
                    $(this).find('option').prop('selected', false);
                    $(this).val(['all']).trigger('change');
                } else {
                    $(this).find('option[value="all"]').prop('selected', false);
                }
            });
        }

        // Apply selection logic
        handleSelection('#memberships');
        handleSelection('#services');
    });
</script>