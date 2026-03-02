async function checkHeartbeat() {
    const allItems = document.querySelectorAll('.shelf-item.content-container');
    
    allItems.forEach(async (item) => {
        const ipText = item.innerText.trim();
        const icon = item.querySelector('i');
        
        try {
            const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ipText)}`);
            const statusColor = await response.text();
            
            icon.classList.remove('status-green', 'status-yellow', 'status-red', 'status-grey');
            icon.classList.add(`status-${statusColor.trim()}`);
        } catch (error) {
            icon.classList.add('status-grey');
        }
    });
}

checkHeartbeat();
setInterval(checkHeartbeat, 3000);