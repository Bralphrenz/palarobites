function sendMessageWithAlert(message, receiverId = null) {
    $.post('send_message.php', { message: message, receiver_id: receiverId }, function(response) {
        let res = typeof response === 'string' ? JSON.parse(response) : response;
        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'Message sent!',
                showConfirmButton: false,
                timer: 1500,
                confirmButtonColor: '#000' // Set button color to black
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: res.error || 'Failed to send message.',
                confirmButtonColor: '#000' // Set button color to black
            });
        }
    });
}
