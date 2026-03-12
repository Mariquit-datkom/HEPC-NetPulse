const body  = document.body;
const navPanel = document.querySelector('.nav-panel');
const fullScreenBtn = document.getElementById('fullscreen-btn');
const menuCheckbox = document.querySelector('.menu-checkbox');

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

const changeEvents = ['fullscreenchange', 'webkitfullscreenchange'];

changeEvents.forEach(eventType => {
    document.addEventListener(eventType, () => {
        const isFS = document.fullscreenElement || document.webkitFullscreenElement;
        
        if (isFS) {
            body.classList.add('fullscreen-active');
            fullScreenBtn.innerHTML = '<i class="fas fa-compress"></i> Exit Fullscreen';
            if (menuCheckbox) menuCheckbox.checked = false;
            console.log("Entered Fullscreen");
        } else {
            body.classList.remove('fullscreen-active');
            fullScreenBtn.innerHTML = '<i class="fas fa-expand"></i> Fullscreen';
            if (menuCheckbox) menuCheckbox.checked = false;
            console.log("Exited Fullscreen");
        }
    });
});