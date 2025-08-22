<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Profile Dropdown Toggle
        const userAvatar = document.getElementById('userProfileDropdown');
        const dropdownMenu = userAvatar?.nextElementSibling;
        
        if (userAvatar && dropdownMenu) {
            userAvatar.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                
                if (isExpanded) {
                    dropdownMenu.classList.remove('show');
                } else {
                    dropdownMenu.classList.add('show');
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userAvatar.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    userAvatar.setAttribute('aria-expanded', 'false');
                    dropdownMenu.classList.remove('show');
                }
            });
        }
    });
</script>