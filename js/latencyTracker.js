const ctx = document.getElementById('latency-chart').getContext('2d');
const MAX_POINTS = 6; 

// Generate a unique color for each IP line
const colors = ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f'];

const latencyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [], 
        datasets: importantIPs.map((ip, index) => ({
            label: ip,
            data: [],
            borderColor: colors[index % colors.length],
            borderWidth: 2,
            tension: 0.3,
            fill: false
        }))
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 200,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: { grid: { color: '#2c323d' }, ticks: { stepSize: 20, color: '#d0d0d0', callback: function(value) { return value + ' ms'; }} },
            x: { ticks: { color: '#d0d0d0' } }
        },
        plugins: {
            legend: { labels: { color: '#d0d0d0' }, position: 'top' },
            tooltip: {
                usePointStyle: true,
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y + ' ms';
                        }
                        return label;
                    },
                    labelColor: function(context) {
                        return {
                            borderColor: context.dataset.borderColor,
                            backgroundColor: context.dataset.borderColor, // Match background to border
                            borderWidth: 3,
                            borderRadius: 2,
                        };
                    }
                }
            }
        }
    }
});

async function updateLatencyData() {
    const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    // Add new timestamp to labels
    latencyChart.data.labels.push(now);
    if (latencyChart.data.labels.length > MAX_POINTS) latencyChart.data.labels.shift();

    // Fetch status for each important IP
    for (let i = 0; i < importantIPs.length; i++) {
        const ip = importantIPs[i];
        try {
            const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ip)}`);
            const data = await response.json();
            
            // Push actual ms or 0 if timed out 
            const val = (data.ms === '--') ? 0 : data.ms;
            latencyChart.data.datasets[i].data.push(val);
            
            if (latencyChart.data.datasets[i].data.length > MAX_POINTS) {
                latencyChart.data.datasets[i].data.shift();
            }
        } catch (err) {
            console.error(`Error tracking ${ip}:`, err);
        }
    }
    latencyChart.update('none');
}

// Initial call and set interval
updateLatencyData();
setInterval(updateLatencyData, 3500);