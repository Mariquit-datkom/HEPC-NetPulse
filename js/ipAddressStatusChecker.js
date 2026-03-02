async function checkHeartbeat() {
    const items = document.querySelectorAll('.shelf-item.content-container');
    
    items.forEach(async (item) => {
        const ipText = item.innerText.trim();
        const icon = item.querySelector('i');
        
        try {
            const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ipText)}`);
            const statusColor = await response.text();
            
            // Remove old status classes
            icon.classList.remove('status-green', 'status-yellow', 'status-red', 'status-grey');
            // Add new status class
            icon.classList.add(`status-${statusColor.trim()}`);
        } catch (error) {
            icon.classList.add('status-grey');
        }
    });
}

// Initial check on load
checkHeartbeat();
// Refresh every 10 seconds
setInterval(checkHeartbeat, 2000);