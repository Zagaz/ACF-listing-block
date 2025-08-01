(function($) {
    'use strict';

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function initPropertyListings() {
        $('.blacksmith-property-listing').each(function() {
            const $block = $(this);
            const $searchInput = $block.find('.property-search');
            const $typeFilter = $block.find('.property-type-filter');
            const $grid = $block.find('.property-grid');
            const $spinner = $block.find('.loading-spinner');
            const displayMode = $block.find('.display-mode').val();
            const selectedProperties = $block.find('.selected-properties').val().split(',').filter(Boolean);
            const postsPerPage = $block.find('.posts-per-page').val();

            const filterProperties = debounce(function() {
                $grid.addClass('filtering');
                $spinner.show();

                $.ajax({
                    url: blacksmith_listing_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'filter_properties',
                        nonce: blacksmith_listing_ajax.nonce,
                        search: $searchInput.val(),
                        property_type: $typeFilter.val(),
                        display_mode: displayMode,
                        posts_per_page: postsPerPage,
                        selected_properties: selectedProperties
                    },
                    success: function(response) {
                        if (response.success) {
                            $grid.html(response.data.html);
                        }
                    },
                    complete: function() {
                        $grid.removeClass('filtering');
                        $spinner.hide();
                    }
                });
            }, 300);

            $searchInput.on('input', filterProperties);
            $typeFilter.on('change', filterProperties);
        });
    }

    $(document).ready(initPropertyListings);
})(jQuery);