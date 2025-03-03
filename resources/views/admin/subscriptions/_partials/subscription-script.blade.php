<script>
    $(document).ready(function () {
        let selectedOfferId = "{{ $subscription->payment->offer_id }}";

        function fetchOffers() {
            $.ajax({
                url: "/admin/get-offers", 
                method: "GET",
                success: function (response) {
                    let offersDropdown = $("#offer_id");
                    offersDropdown.empty(); 
                    offersDropdown.append('<option value="">Select an Offer</option>');

                    $.each(response.offers, function (index, offer) {
                        let isSelected = offer.id == selectedOfferId ? "selected" : "";
                        offersDropdown.append(`<option value="${offer.id}" ${isSelected}>${offer.name}</option>`);
                    });
                },
                error: function () {
                    $("#offer_id").html('<option value="">Failed to load offers</option>');
                }
            });
        }

        if (selectedOfferId) {
            $("#offersListContainer").removeClass("d-none");
            fetchOffers();
        }

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