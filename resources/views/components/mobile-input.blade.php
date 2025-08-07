<script>
    window.addEventListener("load", function() {
    // Your code that uses the intlTelInput function
    const mobileInputField = document.querySelector("#mobile");
    const mobileInput = window.intlTelInput(mobileInputField, {
      utilsScript:
        "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js",
      separateDialCode: true,
      hiddenInput: "full_mobile",
      nationalMode: false,
      initialCountry: "auto",
      formatOnDisplay: false,
      geoIpLookup: callback => {
        fetch("https://ipapi.co/json")
          .then(res => res.json())
          .then(data => callback(data.country_code))
          .catch(() => callback("us"));
      },
    });
  
    function process(event) {
      event.preventDefault();
  
      const mobileNumber = mobileInput.getNumber();
  
      info.style.display = "";
      info.innerHTML = `Mobile number in E.164 format: <strong>${mobileNumber}</strong>`;
      }
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>