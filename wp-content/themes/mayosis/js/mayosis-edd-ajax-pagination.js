// mayosis-edd-ajax-pagination.js
jQuery(document).ready(function($) {
    console.log("EDD AJAX Pagination script loaded");

    $(document).on('click', '.mayosis-edd-p-history-pagination a', function(e) {
        e.preventDefault();
        
        var page = $(this).data('page');
        var type = $(this).data('type');
        var data = {
            action: 'mayosis_edd_ajax_pagination',
            page: page,
            type: type,
            nonce: mayosis_edd_ajax_pagination_vars.nonce
        };

        console.log("Clicked page: ", page, "Type: ", type); // Add this line for debugging

        // Show loading icon
        $('.msps_loading-icon').show();

        $.post(mayosis_edd_ajax_pagination_vars.ajax_url, data, function(response) {
            if(response.success) {
                if (type === 'purchase_history') {
                    $('.edd-purchase-history').html(response.data);
                } else if (type === 'download_history') {
                    $('.edd-download-history').html(response.data);
                }
                // Update active class
                $('.mayosis-edd-p-history-pagination a').removeClass('active');
                $('.mayosis-edd-p-history-pagination a[data-page="' + page + '"]').addClass('active');
            } else {
                alert('An error occurred');
            }

            // Hide loading icon
            $('.msps_loading-icon').hide();
        });
    });
});
