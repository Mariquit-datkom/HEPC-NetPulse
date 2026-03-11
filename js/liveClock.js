//Live clock for dashboard
function updateClock() {
    const now = new Date();

    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('current-date').textContent = now.toLocaleDateString(undefined, dateOptions);

    const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
    document.getElementById('current-time').textContent = now.toLocaleTimeString(undefined, timeOptions);
}

// Update immediately, then every second
setInterval(updateClock, 1000);
updateClock();