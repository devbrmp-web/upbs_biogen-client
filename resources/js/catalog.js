document.addEventListener('DOMContentLoaded', () => {
    const seedSelect = document.getElementById('seed-class-select');
    if (seedSelect) {
        seedSelect.addEventListener('change', function() {
            const seedClass = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            const commodity = urlParams.get('commodity') || '';
            
            let newUrl = '/katalog?seed_class=' + encodeURIComponent(seedClass);
            if (commodity) {
                newUrl += '&commodity=' + encodeURIComponent(commodity);
            }
            
            document.body.classList.add('cursor-wait');
            
            window.location.href = newUrl;
        });
    }

    const refreshBtn = document.getElementById('refreshDataBtn');
    const refreshIcon = document.getElementById('refreshIcon');
    if (refreshBtn && refreshIcon) {
        refreshBtn.addEventListener('click', function() {
            refreshIcon.style.animation = 'spin 0.8s linear infinite';
            setTimeout(() => {
                refreshIcon.style.animation = '';
            }, 1200);
        });
    }
});
