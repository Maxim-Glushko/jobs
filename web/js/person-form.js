$(document).ready(function() {
    new Choices($('#company-ids')[0], {
        removeItemButton: true,
        placeholderValue: 'Выберите компании',
        searchPlaceholderValue: 'Поиск...',
        noResultsText: 'Нет результатов',
        noChoicesText: 'Нет доступных компаний',
    });
});