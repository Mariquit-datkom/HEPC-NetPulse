window.addEventListener('load', function() {
    const loadingScreen = document.getElementById('loading-screen');
    setTimeout(function() {
        loadingScreen.classList.add('hidden');
    }, 1500);

    loadingScreen.addEventListener('transitionend', () => {
        loadingScreen.style.display = 'none';
    });
});
