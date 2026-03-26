const navPanel = document.querySelector('.nav-panel');
const menuCheckbox = document.querySelector('.menu-checkbox');

const checkFullScreenStatus = () => {
    
    if (window.screen.width <= 360) {
        console.log("Mobile view detected - Fullscreen disabled");
        sessionStorage.setItem('isFullscreen', 'false');
        document.documentElement.classList.remove('fullscreen-active');
        return;
    }

    setTimeout(() => {
        const windowWidth = window.innerWidth * window.devicePixelRatio;
        const windowHeight = window.innerHeight * window.devicePixelRatio;
        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;
        if (((windowWidth/screenWidth)>=0.95) && ((windowHeight/screenHeight)>=0.95)) {
            console.log("Fullscreen");          
            sessionStorage.setItem('isFullscreen', 'true');
            document.documentElement.classList.add('fullscreen-active');
            if (menuCheckbox) menuCheckbox.checked = false;
        }
        else {
            console.log("Not Fullscreen");
            sessionStorage.setItem('isFullscreen', 'false');
            document.documentElement.classList.remove('fullscreen-active');
            if (menuCheckbox) menuCheckbox.checked = false;
        }
    }, 100);
}

window.addEventListener("resize", checkFullScreenStatus);
window.addEventListener("DOMContentLoaded", checkFullScreenStatus);