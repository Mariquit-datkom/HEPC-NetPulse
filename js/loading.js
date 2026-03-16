// Function for loading screen
document.addEventListener("DOMContentLoaded", function() {
    const loadingScreen = document.getElementById('loading-screen');
    if (!sessionStorage.getItem('hasLoaded')) {

        // Set a timeout to hide it
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
            setTimeout(() => { 
                loadingScreen.classList.add('hidden');; 
            }, 500); // Wait for fade-out transition
            
            sessionStorage.setItem('hasLoaded', 'true');
        }, 2000); // Adjust duration as needed
        
    } else {
        // Not the first load; hide the loadingScreen immediately
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');;
        }
    }

    loadingScreen.addEventListener('transitionend', () => {
        loadingScreen.style.display = 'none';
    });
});
