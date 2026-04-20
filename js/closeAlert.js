function closeAlert() {
    const alertBox = document.getElementById('lockAlert');
    if (alertBox) {
        alertBox.style.display = 'none';
    }
    
    // Optional: Removes "?error=locked..." from the URL bar without refreshing
    // This prevents the alert from popping up again if the user hits F5
    const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.replaceState({path: cleanUrl}, '', cleanUrl);
}