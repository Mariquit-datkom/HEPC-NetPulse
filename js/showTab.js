function showTab(type) {
    document.getElementById('daily').style.display = type === 'daily' ? 'block' : 'none';
    document.getElementById('weekly').style.display = type === 'weekly' ? 'block' : 'none';
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.currentTarget.classList.add('active');
}