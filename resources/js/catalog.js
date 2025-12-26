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
            
            // Show loading state (optional visual improvement)
            document.body.classList.add('cursor-wait');
            
            window.location.href = newUrl;
        });
    }
});
