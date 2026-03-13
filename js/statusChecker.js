async function checkHeartbeat(iconBaseClass) {
    const visibleItems = Array.from(document.querySelectorAll('.shelf-item'));
    const visibleIps = visibleItems.map(item => item.getAttribute('data-ip'));

    const allAddr = (typeof allAddresses !== 'undefined') ? allAddresses : [];
    const priority = (typeof priorityAddresses !== 'undefined') ? priorityAddresses : [];
    const priorityQueue = priority.filter(ip => !visibleIps.includes(ip));
    const backgroundIps = allAddr.filter(ip => 
        !visibleIps.includes(ip) && !priorityQueue.includes(ip)
    );

    const queue = [...visibleIps, ...priorityQueue, ...backgroundIps];
    const activeRequests = [];
    const limit = 2;

    async function processNext() {
        if (queue.length === 0) return;

        const ip = queue.shift();
        if (!ip) return processNext();

        const itemElement = document.querySelector(`.shelf-item[data-ip="${ip}"]`);;
        let icon = null;
        let pingDisplay = null;

        if (itemElement) {
            icon = itemElement.querySelector('i');
            pingDisplay = itemElement.querySelector('.display-ping');
        }

        await new Promise(resolve => setTimeout(resolve, 800));

        const task = (async () => {
            try {
                const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ip)}`);
                const data = await response.json();

                if (icon) icon.className = `${iconBaseClass} status-${data.color}`;
                if (pingDisplay) pingDisplay.textContent = (data.ms !== '--') ? `( ${data.ms}ms )` : '( Timed Out )';

                window.dispatchEvent(new CustomEvent('ipStatusUpdated', { 
                    detail: { ip: ip, ms: data.ms, color: data.color } 
                }));

            } catch (err) {
                console.error('Ping Error:', err);
                if (icon) icon.className = `${iconBaseClass} status-grey`;
                if (pingDisplay) pingDisplay.textContent = '( Error )';
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
    let iconClass = '';

    if (typeof currentPage !== 'undefined') {
        if (currentPage === 'ipAddresses.php') {
            iconClass = 'fa fa-signal';
        } else if (currentPage === 'biometrics.php') {
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
                setTimeout(run, 15000);
            });
        } else {
            setTimeout(run, 2000);
        }
    };

    run();
}

initStatusChecker();