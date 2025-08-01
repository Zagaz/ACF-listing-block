document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.blacksmith-property-listing').forEach(function (block) {
        const blockId = block.dataset.blockId;
        const grid = block.querySelector('.property-grid');
        const paginator = block.querySelector('.property-paginator');
        const postsPerPage = block.querySelector('.posts-per-page').value;
        const displayMode = block.querySelector('.display-mode').value;
        const selectedProperties = block.querySelector('.selected-properties').value;

        let currentPage = 1;
        let maxPages = 1;

        function fetchPage(page) {
            block.querySelector('.loading-spinner').style.display = 'block';
            const data = new FormData();
            data.append('action', 'blacksmith_property_pagination');
            data.append('paged', page);
            data.append('posts_per_page', postsPerPage);
            data.append('display_mode', displayMode);
            data.append('selected_properties', selectedProperties);

            fetch(blacksmithListingAjax.ajax_url, {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    grid.innerHTML = result.data.html;
                    maxPages = result.data.max_num_pages;
                    renderPaginator();
                }
                block.querySelector('.loading-spinner').style.display = 'none';
            });
        }

        function renderPaginator() {
            let html = '';
            if (maxPages <= 1) {
                paginator.innerHTML = '';
                return;
            }
            html += '<ul class="pagination-list">';
            const nextArrowHTML = `<span class="dashicons dashicons-arrow-right-alt"></span>`;
            const prevArrowHTML = `<span class="dashicons dashicons-arrow-left-alt"></span>`;

            // Prev arrow
            html += `<li><button class="page-btn" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>${prevArrowHTML}</button></li>`;

            const isMobile = window.matchMedia('(max-width: 600px)').matches;

            if (isMobile) {
                // Always show: prev | current | ... | last | next
                html += `<li><button class="page-btn active" data-page="${currentPage}">${currentPage.toString().padStart(2, '0')}</button></li>`;

                if (currentPage < maxPages - 1) {
                    html += `<li><span>...</span></li>`;
                }

                if (currentPage !== maxPages) {
                    html += `<li><button class="page-btn" data-page="${maxPages}">${maxPages.toString().padStart(2, '0')}</button></li>`;
                }
            } else {
                // Desktop: show neighbors as before
                let start = Math.max(1, currentPage - 2);
                let end = Math.min(maxPages, currentPage + 2);

                if (start > 1) {
                    html += `<li><button class="page-btn" data-page="1">01</button></li>`;
                    if (start > 2) html += `<li><span>...</span></li>`;
                }

                for (let i = start; i <= end; i++) {
                    html += `<li><button class="page-btn${i === currentPage ? ' active' : ''}" data-page="${i}">${i.toString().padStart(2, '0')}</button></li>`;
                }

                if (end < maxPages) {
                    if (end < maxPages - 1) html += `<li><span>...</span></li>`;
                    html += `<li><button class="page-btn" data-page="${maxPages}">${maxPages.toString().padStart(2, '0')}</button></li>`;
                }
            }

            // Next arrow
            html += `<li><button class="page-btn" data-page="${currentPage + 1}" ${currentPage === maxPages ? 'disabled' : ''}>${nextArrowHTML}</button></li>`;
            html += '</ul>';
            paginator.innerHTML = html;

            paginator.querySelectorAll('.page-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const page = parseInt(this.dataset.page);
                    if (page >= 1 && page <= maxPages && page !== currentPage) {
                        currentPage = page;
                        fetchPage(currentPage);
                    }
                });
            });
        }

        // Initial fetch to get maxPages
        fetchPage(currentPage);
    });
});