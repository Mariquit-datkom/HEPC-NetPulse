<div id="loading-screen">
    <div class="loader-wrapper">
        <div class="wifi-pulse-container">
            <i class="fa-solid fa-wifi"></i>
        </div>
        <p id="loading-message">Initializing...</p>
    </div>
</div>

<script>
    (function() {
        const messages = [
            "Pinging the network...",
            "Trying to explain to the server that it's Monday...",
            "Checking heartbeats...",
            "Feeding the hamsters powering the server...",
            "Squashing high latency...",
            "Stabilizing NetPulse...",
            "Teaching the router manners...",
            "Fetching data from the depths of the database...",
            "Checking logs so you don't have to...",
            "Downloading more RAM... please wait...",
            "Bribing the firewall with cookies..."
        ];
        
        for (let i = messages.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [messages[i], messages[j]] = [messages[j], messages[i]];
        }
        
        const randomMessage = messages[0];
        const msgElement = document.getElementById('loading-message');
        
        if (msgElement) {
            msgElement.innerText = randomMessage;
        }
    })();
</script>