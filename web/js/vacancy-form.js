$(document).ready(function() {
    new Choices($('#company-id')[0], {
        searchEnabled: true,
        removeItemButton: true,
        placeholderValue: 'Выберите компанию'
    });
});