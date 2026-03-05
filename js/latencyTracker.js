//Tracks ip latency for dashboard graph
const colors = ['#3498db', '#e74c3c', '#2ecc71', '#f1c40f', '#e92264'];
const MAX_POINTS = 8;

function createChart(canvasId, ipList) {
    const ctx = document.getElementById(canvasId).getContext('2d');
    const initialLabels = new Array(MAX_POINTS).fill('');
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: initialLabels, 
            datasets: ipList.map((ip, index) => ({
                label: ip,
                data: new Array(MAX_POINTS).fill(0),
                borderColor: colors[index % colors.length],
                borderWidth: 2,
                tension: 0.2,
                fill: false
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            resizeDelay: 100,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            animation: {
                duration: 1000
            },
            scales: {
                y: { 
                    suggestedMin: 0,
                    suggestedMax: 100,
                    grid: { color: '#2c323d' }, 
                    ticks: { 
                        color: '#d0d0d0',
                        callback: function(value, index, ticks) {
                            const maxVal = this.chart.scales.y.max;
                            
                            const step = (maxVal > 150) ? 20 : 10;
                            
                            if (value % step === 0) {
                                return value + ' ms';
                            }
                        }
                    }
                },
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
                                backgroundColor: context.dataset.borderColor,
                                borderWidth: 3,
                                borderRadius: 2,
                            };
                        }
                    }
                }
            }
        }
    });
}

const biometricChart = createChart('biometric-latency-chart', biometrics);
const switchChart = createChart('switch-latency-chart', switches);
const serverChart = createChart('server-latency-chart', servers);

async function updateChartData(chart, ipList) {
    const now = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    
    chart.data.labels.shift();
    chart.data.labels.push(now);

    const requests = ipList.map(async (ip, i) => {
        try {
            const response = await fetch(`checkIpStatus.php?ip=${encodeURIComponent(ip)}`);
            const data = await response.json();
            const val = (data.ms === '--') ? 0 : data.ms;
            
            chart.data.datasets[i].data.shift();
            chart.data.datasets[i].data.push(val);

            const statusColor = (val === 0) ? '#808080' : colors[i % colors.length];
            chart.data.datasets[i].borderColor = statusColor;
            chart.data.datasets[i].pointBackgroundColor = statusColor;
            
        } catch (err) {
            console.error(`Error fetching ${ip}:`, err);
        }
    });

    await Promise.all(requests);
    chart.update();
}

async function refreshAll() {
    await Promise.all([
        updateChartData(biometricChart, biometrics),
        updateChartData(switchChart, switches),
        updateChartData(serverChart, servers)
    ]);
}

refreshAll();
setTimeout(function run() {
    refreshAll().then(() => setTimeout(run, 1500)); 
}, 1500);