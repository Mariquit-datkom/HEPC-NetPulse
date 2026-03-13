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
                    suggestedMax: 200,
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
                            let value = context.parsed.y
                            if (label) {
                                label += ': ';
                            }
                            if (value === 0) {
                                label += 'Timed Out';
                            } else {
                                label += value + 'ms';
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

const chartSyncTracker = {
    'biometric-latency-chart': new Set(),
    'switch-latency-chart': new Set(),
    'server-latency-chart': new Set()
};

window.addEventListener('ipStatusUpdated', (e) => {
    const { ip, ms } = e.detail;
    const val = (ms === '--') ? 0 : ms;
    const timeLabel = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });

    [biometricChart, switchChart, serverChart].forEach(chart => {
        const chartId = chart.canvas.id;
        const datasetIndex = chart.data.datasets.findIndex(ds => ds.label.trim() === ip.trim());
        
        if (datasetIndex !== -1) {      
            const totalRequired = chart.data.datasets.length;
            chartSyncTracker[chartId].add(ip.trim());
            
            chart.data.datasets[datasetIndex].data.shift();
            chart.data.datasets[datasetIndex].data.push(val);
            
            const statusColor = (val === 0) ? '#808080' : colors[datasetIndex % colors.length];
            chart.data.datasets[datasetIndex].borderColor = statusColor;

            if (chartSyncTracker[chartId].size >= totalRequired) {
                
                chart.data.labels.shift();
                chart.data.labels.push(timeLabel);

                chart.update();

                chartSyncTracker[chartId].clear();
            }
        }
    });
});