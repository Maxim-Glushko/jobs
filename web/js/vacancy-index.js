$(document).on('click', '.toggle-link', function (e) {
    e.preventDefault();

    const $container = $(this).closest('.text-toggle');
    const $short = $container.find('.short-text');
    const $full = $container.find('.full-text');

    if ($full.hasClass('d-none')) {
        $short.hide();
        $full.removeClass('d-none');
        $(this).text('скрыть');
    } else {
        $full.addClass('d-none');
        $short.show();
        $(this).text('далее');
    }
});