$(document).ready(function() {
    new Choices($('#person-ids')[0], {
        removeItemButton: true,
        placeholderValue: 'Выберите людей',
        searchPlaceholderValue: 'Поиск...',
        noResultsText: 'Нет результатов',
        noChoicesText: 'Нет доступных людей',
    });
    new Choices($('#vacancy-id')[0], {
        searchEnabled: true,
        removeItemButton: true,
    });
    $('.insert-template').click(function () {
        const textToInsert = $(this).data('text');
        const $textarea = $('#interaction-result');
        const cursorPos = $textarea.prop('selectionStart');
        const text = $textarea.val();
        const newText = text.slice(0, cursorPos) + textToInsert + text.slice(cursorPos);
            $textarea.val(newText);
            $textarea.focus();
            $textarea[0].selectionStart = $textarea[0].selectionEnd = cursorPos + textToInsert.length;
    });
});