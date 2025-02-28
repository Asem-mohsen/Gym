<script>
    $(document).ready(function () {

        function fetchOffers() {
            $.ajax({
                url: "/admin/get-offers", 
                method: "GET",
                success: function (response) {
                    let offersDropdown = $("#offer_id");
                    offersDropdown.empty(); 
                    offersDropdown.append('<option value="">Select an Offer</option>');

                    $.each(response.offers, function (index, offer) {
                        offersDropdown.append(`<option value="${offer.id}">${offer.name}</option>`);
                    });
                },
                error: function () {
                    $("#offer_id").html('<option value="">Failed to load offers</option>');
                }
            });
        }

        $("#status").change(function () {
            let selectedStatus = $(this).val();

            if (selectedStatus === "active") {
                $("#amountPaidContainer").removeClass("d-none"); 
                $("#offerContainer").removeClass("d-none"); 
            } else {
                $("#amountPaidContainer, #offerContainer, #offersListContainer").addClass("d-none");
            }
        });

        $("#apply_offer").change(function () {
            let applyOffer = $(this).val();
            if (applyOffer === "yes") {
                $("#offersListContainer").removeClass("d-none");
                fetchOffers();
            } else {
                $("#offersListContainer").addClass("d-none");
            }
        });
    });
</script>