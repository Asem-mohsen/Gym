<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id');
    const trainerSection = document.getElementById('trainer-info-section');
    
    function toggleTrainerSection() {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const isTrainer = selectedOption && selectedOption.text.toLowerCase().includes('trainer');
        
        if (isTrainer) {
            trainerSection.style.display = 'block';
        } else {
            trainerSection.style.display = 'none';
        }
    }
    
    // Initial check
    toggleTrainerSection();
    
    // Listen for changes
    roleSelect.addEventListener('change', toggleTrainerSection);
});
</script>