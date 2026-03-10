function runUserHeartbeat() {
    fetch('userPing.php')
        .then(response => {
            if (response.status === 401) {
                window.location.href = 'logIn.php?reason=expired';
            }
        })
        .catch(err => console.error('Heartbeat failed:', err));
}

runUserHeartbeat();
setInterval(runUserHeartbeat, 5000);