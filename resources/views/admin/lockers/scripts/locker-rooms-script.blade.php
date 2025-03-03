<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    function toggleLocker(lockerId) {
        let password = prompt("Enter password:");
        if (password === null) return;

        fetch(`/admin/lockers/${lockerId}/toggle`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ password })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert(data.message);
            } else {
                location.reload();
            }
        })
        .catch(error => console.error("Error:", error));
    }

    var pusher = new Pusher('07c9c7e6344c9267bf13', {
      cluster: 'eu'
    });

    var channel = pusher.subscribe('lockers');
    channel.bind('locker-rooms', function(data) {
        location.reload(); 
    });
</script>