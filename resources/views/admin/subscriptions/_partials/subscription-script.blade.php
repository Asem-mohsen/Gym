<script>
    $(document).ready(function () {
        let selectedOfferId = "{{ $selectedOfferId ?? '' }}";

        // Membership periods mapping
        const membershipPeriods = {
            'One Day': 1,
            'Month': 30,
            '3 Month': 90,
            '6 Month': 180,
            'Year': 365,
            '2 Years': 730,
            '3 Years': 1095,
            '4 Years': 1460,
            '6 Years': 2190
        };

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

        function calculateEndDate() {
            const membershipId = $("#membership_id").val();
            const startDate = $("#start_date").val();
            
            if (membershipId && startDate) {
                // Get membership period from the selected option
                const selectedOption = $("#membership_id option:selected");
                const membershipText = selectedOption.text();
                
                // Extract period from membership text (assuming format like "Basic - One Day")
                let period = null;
                for (const [key, days] of Object.entries(membershipPeriods)) {
                    if (membershipText.includes(key)) {
                        period = key;
                        break;
                    }
                }
                
                if (period) {
                    const start = new Date(startDate);
                    const end = new Date(start);
                    
                    if (period === 'One Day') {
                        end.setDate(start.getDate() + 1);
                    } else {
                        end.setDate(start.getDate() + membershipPeriods[period]);
                    }
                    
                    const endDateString = end.toISOString().split('T')[0];
                    $("#end_date").val(endDateString);
                }
            }
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

        // Auto-calculate end date when membership or start date changes
        $("#membership_id, #start_date").change(function () {
            calculateEndDate();
        });

        // Calculate end date on page load if both fields have values
        if ($("#membership_id").val() && $("#start_date").val()) {
            calculateEndDate();
        }
    });
</script>