const groupToBadgeMap = {
    'Servers': 'badge-servers-switches',
    'Switch': 'badge-servers-switches',
    'Biometrics': 'badge-biometrics',
    'Important Desktops': 'badge-desktops',
    'Other Desktops': 'badge-desktops',
    'Laptops': 'badge-laptops',
    'Compute Sticks': 'badge-compute-sticks'
};

let ipStatusRegistry = {};

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
                console.log("IP:", ip, "Group from PHP:", data.group, "Badge ID:", groupToBadgeMap[data.group]);

                if (icon) icon.className = `${iconBaseClass} status-${data.color}`;
                if (pingDisplay) pingDisplay.textContent = (data.ms !== '--') ? `( ${data.ms}ms )` : '( Timed Out )';

                updateNavRegistry(ip, data.color, data.group);

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

function updateNavRegistry(ip, color, groupName) {
    ipStatusRegistry[ip] = {
        isDown: (color === 'grey'), 
        badgeId: groupToBadgeMap[groupName] || null
    };

    refreshNavBadges();
}

function refreshNavBadges() {
    // 1. Reset all counts
    const counts = {
        'badge-servers-switches': 0,
        'badge-biometrics': 0,
        'badge-desktops': 0,
        'badge-laptops': 0,
        'badge-compute-sticks': 0
    };

    // 2. Count all "isDown" entries in our registry
    Object.values(ipStatusRegistry).forEach(entry => {
        if (entry.isDown && entry.badgeId && counts.hasOwnProperty(entry.badgeId)) {
            counts[entry.badgeId]++;
        }
    });

    // 3. Update the HTML elements
    for (const [id, count] of Object.entries(counts)) {
        const badgeElement = document.getElementById(id);
        if (badgeElement) {
            badgeElement.textContent = count;
            // Show badge if count > 0, hide if 0
            if (count > 0) {
                badgeElement.classList.remove('hide');
            } else {
                badgeElement.classList.add('hide');
            }
        }
    }
}