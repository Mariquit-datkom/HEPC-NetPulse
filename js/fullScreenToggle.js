const navPanel = document.querySelector('.nav-panel');
const fullScreenBtn = document.getElementById('fullscreen-btn');
const menuCheckbox = document.querySelector('.menu-checkbox');

// 1. The Toggle Function
fullScreenBtn.addEventListener('click', function(e) {
    e.preventDefault();
    if (!document.fullscreenElement && !document.webkitFullscreenElement) {
        // Request Fullscreen
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen();
        }
    } else {
        // Exit Fullscreen
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
});

// 2. The Universal Listener (This catches Esc, F11, and Button exits)
const changeEvents = ['fullscreenchange', 'webkitfullscreenchange'];

changeEvents.forEach(eventType => {
    document.addEventListener(eventType, () => {
        const isFS = document.fullscreenElement || document.webkitFullscreenElement;
        
        if (isFS) {
            navPanel.classList.add("collapsed");
            fullScreenBtn.innerHTML = '<i class="fas fa-compress"></i> Exit Fullscreen';
            if (menuCheckbox) menuCheckbox.checked = false;
            console.log("Entered Fullscreen");
        } else {
            navPanel.classList.remove("collapsed");
            fullScreenBtn.innerHTML = '<i class="fas fa-expand"></i> Fullscreen';
            if (menuCheckbox) menuCheckbox.checked = false;
            console.log("Exited Fullscreen");
        }
    });
});