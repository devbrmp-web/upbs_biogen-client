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

    // Handle Refresh Button
    const refreshBtn = document.getElementById('refreshDataBtn');
    const refreshIcon = document.getElementById('refreshIcon');
    if (refreshBtn && refreshIcon) {
        refreshBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add spin animation
            if (!document.getElementById('spin-style')) {
                const style = document.createElement('style');
                style.id = 'spin-style';
                style.innerHTML = `
                    @keyframes spin {
                        from { transform: rotate(0deg); }
                        to { transform: rotate(360deg); }
                    }
                `;
                document.head.appendChild(style);
            }
            refreshIcon.style.animation = 'spin 0.8s linear infinite';

            fetchCatalog(this.href).then(() => {
                setTimeout(() => {
                    refreshIcon.style.animation = '';
                }, 500);
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
