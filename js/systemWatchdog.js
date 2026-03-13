// js/systemWatchdog.js
(function() {
    let soundLoop = null;
    let isCurrentlyDown = false;
    const CHECK_INTERVAL = 1500;

    async function sendToLog(type, reason) {
        try {
            await fetch('logSystemEvent.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ event_type: type, reason: reason })
            });
        } catch (e) {
            console.warn("Logging failed (Server likely unreachable)");
        }
    }

    function triggerAlarm(reason) {
        const overlay = document.getElementById('systemOfflineOverlay');
        const reasonText = document.getElementById('offlineReason');
        const audio = document.getElementById('systemDownSound');
        
        if (overlay && overlay.style.display !== 'flex') {
            overlay.style.display = 'flex';
            reasonText.innerText = reason;
            
            if (!isCurrentlyDown) {
                sendToLog("SYSTEM_DOWN", reason);
                isCurrentlyDown = true;
            }

            audio.play().catch(() => console.warn("Audio blocked. Click page."));

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
        const audio = document.getElementById('systemDownSound');

        if (overlay) overlay.style.display = 'none';
        if (audio) { audio.pause(); audio.currentTime = 0; }

        if (isCurrentlyDown) {
            sendToLog("SYSTEM_UP", "Connection Restored");
            isCurrentlyDown = false;
            location.reload();
        }

        if (soundLoop) {
            clearInterval(soundLoop);
            soundLoop = null;
        }
    }

    async function verifySystem() {
        if (!navigator.onLine) {
            triggerAlarm("NETWORK HARDWARE DISCONNECTED");
            return;
        }

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
            triggerAlarm("SYSTEM UNREACHABLE (CHECK HOST IP)");
        }
    }

    window.addEventListener('offline', () => triggerAlarm("NETWORK HARDWARE DISCONNECTED"));
    window.addEventListener('online', verifySystem);
    setInterval(verifySystem, CHECK_INTERVAL);
})();