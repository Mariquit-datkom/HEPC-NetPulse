function showTab(type) {
    document.getElementById('daily').style.display = type === 'daily' ? 'flex' : 'none';
    document.getElementById('weekly').style.display = type === 'weekly' ? 'flex' : 'none';
    document.getElementById('system').style.display = type === 'system' ? 'flex' : 'none';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
}