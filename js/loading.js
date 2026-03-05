// Function for loading screen
window.addEventListener('load', function() {
    const loadingScreen = document.getElementById('loading-screen');
    setTimeout(function() {
        loadingScreen.classList.add('hidden');
    }, 2000);

    loadingScreen.addEventListener('transitionend', () => {
        loadingScreen.style.display = 'none';
    });
});
