const groupToBadgeMap = {
    'Servers': 'badge-servers',
    'Switches': 'badge-switches',
    'Access Points': 'badge-access-points',
    'Biometrics': 'badge-biometrics'
};

let ipStatusRegistry = JSON.parse(sessionStorage.getItem('ipStatusRegistry')) || {};

async function checkHeartbeat(iconBaseClass) {
    const visibleItems = Array.from(document.querySelectorAll('.shelf-item'));
    const visibleIps = visibleItems.map(item => item.getAttribute('data-ip'));

    const allAddr = (typeof allAddresses !== 'undefined') ? allAddresses : [];
    const priority = (typeof priorityAddresses !== 'undefined') ? priorityAddresses : [];
    const others = (typeof ipFromOtherCategories !== 'undefined') ? ipFromOtherCategories : [];
    const priorityQueue = priority.filter(ip => !visibleIps.includes(ip));
    const backgroundIps = allAddr.filter(ip => 
        !visibleIps.includes(ip) && !priorityQueue.includes(ip)
    );

    const queue = [...visibleIps, ...priorityQueue, ...others ,...backgroundIps];
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
        if (currentPage === 'servers.php') {
            iconClass = 'far fa-server';
        } else if (currentPage === 'switches.php') {
            iconClass = 'fas fa-sliders';
        } else if (currentPage === 'accessPoints.php') {
            iconClass = 'far fa-circle-nodes';
        } else if (currentPage === 'biometrics.php') {
            iconClass = 'fa fa-fingerprint';
        } else if (currentPage === 'categoryView.php') {
            iconClass = 'far fa-wireless';
        } 
    }

    const run = () => {
        checkHeartbeat(iconClass).then(() => {
            setTimeout(run, 10000);
        });
    };

    run();
}

initStatusChecker();

function updateNavRegistry(ip, color, groupName) {

    const isCurrentlyDown = (color === 'grey');

    if (isCurrentlyDown) playAlarm();

    ipStatusRegistry[ip] = {
        isDown: isCurrentlyDown, 
        badgeId: groupToBadgeMap[groupName] || null,
        groupName: groupName
    };

    sessionStorage.setItem('ipStatusRegistry', JSON.stringify(ipStatusRegistry));

    refreshNavBadges();
}

refreshNavBadges();

function refreshNavBadges() {
    // 1. Reset all counts
    const counts = {
        'badge-servers': 0,
        'badge-switches': 0,
        'badge-access-points': 0,
        'badge-biometrics': 0
    };

    const otherSubCategoryCounts = {};
    let totalOtherDown = 0;

    // 2. Count all "isDown" entries in our registry
    Object.values(ipStatusRegistry).forEach(entry => {
        if (entry.isDown) {
            if(entry.badgeId && counts.hasOwnProperty(entry.badgeId)) {
                counts[entry.badgeId]++;
            } else if (entry.groupName) {
                totalOtherDown++;
                const formattedGroupName = entry.groupName.split(' ')
                .map(w => w.charAt(0).toUpperCase() + w.slice(1).toLowerCase())
                .join(' ');
                otherSubCategoryCounts[formattedGroupName] = (otherSubCategoryCounts[formattedGroupName] || 0) + 1;
            }
        }
    });

    // 3. Update the HTML elements
    for (const [id, count] of Object.entries(counts)) {
        updateBadgeUI(id, count);
    }

    // 4. Update the "Others" Nav Panel Badge Total
    updateBadgeUI('badge-other-categories', totalOtherDown);

    // 5. Update individual folder badges (only if on otherCategories.php)
    if (typeof currentPage !== 'undefined' && currentPage === 'otherCategories.php') {
        document.querySelectorAll('.shelf-item').forEach(folder => {
            nameEl = folder.querySelector('.name-text strong');
            if (!nameEl) return;
            
            const categoryName = nameEl.innerText.trim();
            const count = otherSubCategoryCounts[categoryName] || 0;
            
            let badge = folder.querySelector('.folder-badge');
            if (badge) {
                badge.textContent = count;
                count > 0 ? badge.classList.remove('hide') : badge.classList.add('hide');
            }
        });
    }
}

// Helper to keep code clean
function updateBadgeUI(id, count) {
    const el = document.getElementById(id);
    if (el) {
        el.textContent = count;
        count > 0 ? el.classList.remove('hide') : el.classList.add('hide');
    }
}

function playAlarm() {
    const sound = document.getElementById('alarm-sound');
    if (sound) {
        sound.currentTime = 0;
        sound.play().catch(error => {
            console.warn("Autoplay blocked: Interaction required before sound can play.", error);
        });
    }
}