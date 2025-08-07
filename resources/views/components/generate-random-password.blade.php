<script>
    $(document).ready(function() {

        $('[name*=genrate-random-password]').click(function(){
            let newPassword = generateRandomPassword(8);
            $('input[name=password]').val(newPassword);
            $('input[name=password_confirmation]').val(newPassword);
        });

        function generateRandomPassword(length) {
            const lowercase = "abcdefghijklmnopqrstuvwxyz";
            const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            const numbers = "0123456789";
            const symbols = "!@#$%^&*()_+~`|}{[]:;?><,./-=";

            // Ensure at least one character from each category
            let password = [
                lowercase.charAt(Math.floor(Math.random() * lowercase.length)),
                uppercase.charAt(Math.floor(Math.random() * uppercase.length)),
                numbers.charAt(Math.floor(Math.random() * numbers.length)),
                symbols.charAt(Math.floor(Math.random() * symbols.length))
            ];

            // Fill the rest of the password with random characters from all categories
            const allCharacters = lowercase + uppercase + numbers + symbols;
            for (let i = password.length; i < length; i++) {
                password.push(allCharacters.charAt(Math.floor(Math.random() * allCharacters.length)));
            }

            // Shuffle the password to ensure randomness
            password = password.sort(() => Math.random() - 0.5);

            return password.join('');
        }
    });
</script>