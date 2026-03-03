async function checkHeartbeat() {
    const ipItems = document.querySelectorAll('.shelf-item');
    
    ipItems.forEach(item => {
        const ip = item.getAttribute('data-ip');
        const icon = item.querySelector('i');
        const pingDisplay = item.querySelector('.display-ping');
        
        if (ip) {
            fetch(`checkIpStatus.php?ip=${encodeURIComponent(ip)}`)
                .then(response => response.json())
                .then(data => {
                    icon.className = `fa fa-desktop status-${data.color}`;

                    if (data.ms !== '--') {
                        pingDisplay.textContent = `( ${data.ms}ms )`;
                    } else {
                        pingDisplay.textContent = '( Timed Out )';
                    }
                })
                .catch(err => {
                    console.error('Error fetching IP status:', err);
                    icon.className = 'fa fa-signal status-grey';
                    pingDisplay.textContent = '( Error )';
                });
        }
    });
}

checkHeartbeat();
setInterval(checkHeartbeat, 3000);