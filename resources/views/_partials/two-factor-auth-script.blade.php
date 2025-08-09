<script>
    $('#send_code').click(function(){
        // startLoading();
        let button = $(this);
        startButtonLoading(button);

        authMethod = getCheckedValue();
        $.ajax({
            url: "{{ $route }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                auth_method: authMethod
            },
            success: function(response) {
                if (response.success) {
                    $('#codeInput').removeAttr('disabled');
                    $('#save_button').removeClass('disabled');
                    $('#send_code').addClass('disabled');
                    $('[name="code"]').focus();
                } else {
                    toastr.error("Unexpected error occurred");
                }
                // endLoading();
                endButtonLoading(button);
            },
            error: function(jqXHR) {
                jqXHR.status !== 401 ? toastr.error('Unexpected error occurred') : '';
                // endLoading();
                endButtonLoading(button);
            },
        });
    });

    $('#submit_data').click(function(){
        let button = $(this);
        startButtonLoading(button);
        authMethod = 'email';

        $.ajax({
            url: "{{ $route }}",
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                auth_method: authMethod
            },
            success: function(response) {
                if (response.success) {
                    $('#codeInput').removeAttr('disabled');
                    $('#save_button').removeClass('disabled');
                    // show modal
                    $('#two_factor_auth_modal').modal('show');
                    // make cursor focus on code input
                    $('#two_factor_auth_modal').on('shown.bs.modal', function () {
                        $('[name="code"]').focus();
                    });
                } else {
                    toastr.error("Unexpected error occurred");
                }
                // endLoading();
                endButtonLoading(button);
            },
            error: function() {
                // endLoading();
                endButtonLoading(button);
            },
        });
    });

    function getCheckedValue() {
        let radios = document.getElementsByName('auth_method');
        for (let i = 0, length = radios.length; i < length; i++) {
            if (radios[i].checked) {
                // Return the value of the checked radio
                return radios[i].value;
            }
        }
    }
</script>