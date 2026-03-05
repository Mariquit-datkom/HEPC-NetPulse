// Global status checker function for each and every ip address
const CONCURRENCY_LIMIT = 5;

async function checkHeartbeat(iconBaseClass) {
    const ipItems = Array.from(document.querySelectorAll('.shelf-item'));
    
    for (let i = 0; i < ipItems.length; i += CONCURRENCY_LIMIT) {
        const chunk = ipItems.slice(i, i + CONCURRENCY_LIMIT);
        
        await Promise.all(chunk.map(async (item) => {
            const ip = item.getAttribute('data-ip');
            const icon = item.querySelector('i');
            const pingDisplay = item.querySelector('.display-ping');
            
            if (!ip) return;

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
        }));
    }
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
        }
    }

    checkHeartbeat(iconClass);
    
    const run = () => {
        checkHeartbeat(iconClass).then(() => setTimeout(run, 5000));
    };
    setTimeout(run, 5000);
}

initStatusChecker();