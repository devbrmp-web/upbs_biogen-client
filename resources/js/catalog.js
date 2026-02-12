document.addEventListener('DOMContentLoaded', () => {
    // Initial references
    let gridContainer = document.getElementById('product-grid-container');
    const seedSelect = document.getElementById('seed-class-select');
    const commodityContainer = document.getElementById('tutorial-commodity-filter');
    
    // Function to fetch and update grid
    async function fetchCatalog(url, updateUrl = true) {
        // Re-query container to ensure we have the live one
        gridContainer = document.getElementById('product-grid-container');
        if (!gridContainer) return;

        // Show loading state
        gridContainer.style.opacity = '0.5';
        document.body.classList.add('cursor-wait');

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const html = await response.text();
                // Replace the element
                gridContainer.outerHTML = html;
                
                // Re-query immediately after replacement to update reference
                gridContainer = document.getElementById('product-grid-container');
            } else {
                console.error('Failed to load catalog data');
            }
            
            if (updateUrl) {
                window.history.pushState({ path: url }, '', url);
            }

        } catch (error) {
            console.error('Error fetching catalog:', error);
        } finally {
            // Restore state
            // Re-query one last time to be safe
            gridContainer = document.getElementById('product-grid-container');
            if (gridContainer) {
                gridContainer.style.opacity = '1';
            }
            document.body.classList.remove('cursor-wait');
        }
    }

    // 1. Force Refresh on Browser Reload
    try {
        const navEntries = performance.getEntriesByType("navigation");
        if (navEntries.length > 0 && navEntries[0].type === 'reload') {
            console.log('Page reloaded, forcing data refresh...');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('refresh', '1');
            fetchCatalog(currentUrl.toString(), false);
        }
    } catch (e) {
        console.warn('Navigation Timing API not supported');
    }

    // 2. Auto Refresh every 5 minutes
    const AUTO_REFRESH_INTERVAL = 5 * 60 * 1000; // 5 minutes
    
    // Function to update indicator text
    function updateIndicator(text, pulse = false) {
        const indicator = document.getElementById('autoRefreshIndicator');
        if (indicator) {
            indicator.textContent = text;
            if (pulse) indicator.classList.add('animate-pulse', 'text-blue-600');
            else indicator.classList.remove('animate-pulse', 'text-blue-600');
        }
    }

    // Countdown Timer Logic
    let nextRefreshTime = Date.now() + AUTO_REFRESH_INTERVAL;
    
    setInterval(() => {
        const now = Date.now();
        const diff = nextRefreshTime - now;
        
        if (diff <= 0) {
            // Time to refresh
            nextRefreshTime = now + AUTO_REFRESH_INTERVAL;
            
            updateIndicator('Updating...', true);
            
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('refresh', '1');
            
            fetchCatalog(currentUrl.toString(), false).then(() => {
                updateIndicator('Updated just now');
                setTimeout(() => {
                    // Reset visual timer
                }, 2000);
            });
        } else {
            // Update countdown text if needed, e.g. "Auto refresh in 4m"
            // For now we just keep static text or simple status
            const minutes = Math.ceil(diff / 60000);
            const indicator = document.getElementById('autoRefreshIndicator');
            if (indicator && !indicator.textContent.includes('Updating') && !indicator.textContent.includes('just now')) {
                 indicator.textContent = `Auto refresh: ${minutes}m`;
            }
        }
    }, 1000); // Check every second for countdown update


    // Handle Seed Class Change
    if (seedSelect) {
        seedSelect.addEventListener('change', function() {
            const seedClass = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            const commodity = urlParams.get('commodity') || '';
            const search = urlParams.get('search') || '';
            
            let newParams = new URLSearchParams();
            if (seedClass) newParams.set('seed_class', seedClass);
            if (commodity) newParams.set('commodity', commodity);
            if (search) newParams.set('search', search);

            const newUrl = '/katalog?' + newParams.toString();
            fetchCatalog(newUrl);
        });
    }

    // Handle Commodity Filters
    if (commodityContainer) {
        commodityContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.commodity-filter-link');
            if (link && link.href) {
                e.preventDefault();
                
                // Toggle Classes
                const allLinks = commodityContainer.querySelectorAll('.commodity-filter-link');
                const inactiveClass = 'commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md bg-[#B4DEBD]/40 text-gray-800 hover:bg-[#B4DEBD]/60 transition whitespace-nowrap';
                const activeClass = 'commodity-filter-link px-5 py-3 rounded-2xl text-sm font-medium border shadow-md bg-[#B4DEBD] text-black border-[#B4DEBD] cursor-not-allowed opacity-70 whitespace-nowrap pointer-events-none';

                allLinks.forEach(el => {
                    el.className = inactiveClass;
                    el.style.pointerEvents = 'auto'; 
                });

                link.className = activeClass;
                link.style.pointerEvents = 'none';

                fetchCatalog(link.href);
            }
        });
    }

    // Handle Manual Refresh Button (if still exists, or hidden trigger)
    const refreshBtn = document.getElementById('refreshDataBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            updateIndicator('Updating...', true);
            fetchCatalog(this.href).then(() => {
                updateIndicator('Updated just now');
                nextRefreshTime = Date.now() + AUTO_REFRESH_INTERVAL; // Reset timer
            });
        });
    }

    // Handle Reset Filter Link (Delegation)
    document.body.addEventListener('click', function(e) {
        if (e.target.matches('.reset-filter-btn')) {
            e.preventDefault();
            fetchCatalog(e.target.href);
        }
    });

    // Handle Browser Back/Forward
    window.addEventListener('popstate', (event) => {
        fetchCatalog(window.location.href, false);
    });
});
