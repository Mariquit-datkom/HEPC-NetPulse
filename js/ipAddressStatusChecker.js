const CONCURRENCY_LIMIT = 5;

async function checkHeartbeat() {
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

                icon.className = `fa fa-signal status-${data.color}`;
                pingDisplay.textContent = (data.ms !== '--') ? `( ${data.ms}ms )` : '( Timed Out )';
            } catch (err) {
                console.error('Ping Error:', err);
                icon.className = 'fa fa-signal status-grey';
                pingDisplay.textContent = '( Error )';
            }
        }));
    }
}

checkHeartbeat();
setTimeout(function run() {
    checkHeartbeat().then(() => setTimeout(run, 3000)); 
}, 3000);