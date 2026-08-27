jQuery(function ($) {
    function epLoad() {
        var cat = $('.ep-cat.active').data('cat');
        var s = $('#ep-search').val();
        $.post(EP.ajax, { action: 'eproduct_filter', cat: cat, search: s }, function (html) {
            $('#ep-grid').html(html);
        });
    }
    $('#ep-search').on('input', function () {
        clearTimeout(window._epT);
        window._epT = setTimeout(epLoad, 300);
    });
    $('.ep-cat').on('click', function () {
        $('.ep-cat').removeClass('active');
        $(this).addClass('active');
        epLoad();
    });
});
