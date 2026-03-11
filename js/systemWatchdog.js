// js/systemWatchdog.js
(function() {
    let soundLoop = null;
    const CHECK_INTERVAL = 5000;

    function triggerAlarm(reason) {
        const overlay = document.getElementById('systemOfflineOverlay');
        const reasonText = document.getElementById('offlineReason');
        const audio = document.getElementById('systemDownSound');
        
        if (overlay && overlay.style.display !== 'flex') {
            overlay.style.display = 'flex';
            reasonText.innerText = reason;
            
            // Critical: Play sound (requires one manual click on the page first)
            audio.play().catch(() => console.warn("Audio blocked. Click the page once."));

            if (!soundLoop) {
                soundLoop = setInterval(() => {
                    audio.currentTime = 0;
                    audio.play();
                }, 300000); 
            }
        }
    }

    function clearAlarm() {
        const overlay = document.getElementById('systemOfflineOverlay');
        if (overlay) overlay.style.display = 'none';
        if (soundLoop) {
            clearInterval(soundLoop);
            soundLoop = null;
        }
    }

    async function verifySystem() {
        // 1. HARDWARE CHECK: Is the laptop's Wi-Fi/Ethernet even on?
        if (!navigator.onLine) {
            triggerAlarm("NETWORK HARDWARE DISCONNECTED (WI-FI/LAN OFF)");
            return; // Stop here, no point in fetching
        }

        // 2. SERVER CHECK: If hardware is on, is the IP reachable?
        try {
            const controller = new AbortController();
            const id = setTimeout(() => controller.abort(), 3000);

            const response = await fetch(`userPing.php?v=${Date.now()}`, { 
                signal: controller.signal,
                cache: 'no-store'
            });

            if (response.ok) {
                clearAlarm();
            } else {
                triggerAlarm("WEB SERVER ERROR: STATUS " + response.status);
            }
            clearTimeout(id);
        } catch (error) {
            // This catches ERR_CONNECTION_REFUSED or timeouts
            triggerAlarm("SYSTEM UNREACHABLE (CHECK HOST IP)");
        }
    }

    // Immediate check when Wi-Fi is toggled manually
    window.addEventListener('offline', () => triggerAlarm("NETWORK HARDWARE DISCONNECTED"));
    window.addEventListener('online', verifySystem);

    // Regular interval check
    setInterval(verifySystem, CHECK_INTERVAL);
})();