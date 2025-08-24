<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_ids');
    const trainerSection = document.getElementById('trainer-info-section');
    
    function toggleTrainerSection() {
        if (!roleSelect || !trainerSection) {
            return;
        }
        
        let hasTrainerRole = false;
        
        // Check if it's a multiple select
        if (roleSelect.multiple) {
            // Multiple select - check all selected options
            for (let option of roleSelect.options) {
                if (option.selected && option.text.toLowerCase().includes('trainer')) {
                    hasTrainerRole = true;
                    break;
                }
            }
        } else {
            // Single select
            const selectedOption = roleSelect.options[roleSelect.selectedIndex];
            hasTrainerRole = selectedOption && selectedOption.text.toLowerCase().includes('trainer');
        }
        
        if (hasTrainerRole) {
            trainerSection.style.display = 'block';
        } else {
            trainerSection.style.display = 'none';
        }
    }
    
    // Initial check
    toggleTrainerSection();
    
    // Listen for changes
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleTrainerSection);
    }
    
    // Make function globally available for inline calls
    window.toggleTrainerSection = toggleTrainerSection;
});
</script>