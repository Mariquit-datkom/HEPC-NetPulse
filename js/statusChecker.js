async function checkHeartbeat(iconBaseClass) {
    const ipItems = Array.from(document.querySelectorAll('.shelf-item'));
    const queue = [...ipItems];
    const activeRequests = [];
    const limit = 3;

    async function processNext() {
        if (queue.length === 0) return;

        const item = queue.shift();
        const ip = item.getAttribute('data-ip');

        await new Promise(resolve => setTimeout(resolve, 800));

        const icon = item.querySelector('i');
        const pingDisplay = item.querySelector('.display-ping');

        if (!ip) return processNext();

        const task = (async () => {
            try {
                const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ip)}`);
                const data = await response.json();

                icon.className = `${iconBaseClass} status-${data.color}`;
                pingDisplay.textContent = (data.ms !== '--') ? `( ${data.ms}ms )` : '( Timed Out )';
            } catch (err) {
                console.error('Ping Error:', err);
                icon.className = `${iconBaseClass} status-grey`;
                pingDisplay.textContent = '( Error )';
            }
        })();

        activeRequests.push(task);
        await task;
        activeRequests.splice(activeRequests.indexOf(task), 1);
        
        await processNext();
    }

    const workers = [];
    for (let i = 0; i < Math.min(limit, queue.length); i++) {
        workers.push(processNext());
    }

    await Promise.all(workers);
}

function initStatusChecker() {
    let iconClass = 'fa fa-signal';

    if (typeof currentPage !== 'undefined') {
        if (currentPage === 'biometrics.php') {
            iconClass = 'fa fa-fingerprint';
        } else if (currentPage === 'desktops.php') {
            iconClass = 'fa fa-desktop';
        } else if (currentPage === 'laptops.php') {
            iconClass = 'fa fa-laptop';
        } else if (currentPage === 'computeSticks.php') {
            iconClass = 'fab fa-usb';
        }
    }

    let isPageVisible = true;

    document.addEventListener("visibilitychange", () => {
        if (document.hidden) {
            isPageVisible = false;
            console.log("Tab hidden: Pinging paused to save CPU.");
        } else {
            isPageVisible = true;
            console.log("Tab focused: Pinging resumed.");
        }
    });

    const run = () => {
        if (isPageVisible) {
            checkHeartbeat(iconClass).then(() => {
                setTimeout(run, 5000);
            });
        } else {
            setTimeout(run, 1000);
        }
    };

    run();
}

initStatusChecker();