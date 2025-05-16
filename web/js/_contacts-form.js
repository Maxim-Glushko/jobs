$(document).ready(function() {
    const $contactsContainer = $('.dynamic-contacts-container');
    const $contactFields = $contactsContainer.find('.contact-fields');
    const $typeSelect = $contactsContainer.find('.contact-type-select');
    const $template = $('#contact-field-template');
    const $jsonInput = $('.contacts-json-input');

    const contacts = JSON.parse($jsonInput.val());
    const initialContacts = contacts || {};

    function addContactField(type, value = '') {
        const $newField = $($(document.importNode($template[0].content, true)));
        const $field = $newField.find('.contact-field');
        const $label = $field.find('.contact-label');
        const $input = $field.find('.contact-input');

        $label.text(type);
        $input.val(value);

        $input.on('change', updateJsonData);
        $contactFields.append($newField);
    }

    function updateJsonData() {
        const data = {};
        $contactFields.find('.contact-field').each(function () {
            const type = $(this).find('.contact-label').text();
            const value = $(this).find('.contact-input').val().trim();
            if (value) {
                if (!data[type]) data[type] = [];
                data[type].push(value);
            }
        });
        $jsonInput.val(JSON.stringify(data));
    }

    $typeSelect.on('change', function () {
        const value = $(this).val();
        if (value) {
            addContactField(value);
            $contactFields.find('.contact-input').last().focus();
            $(this).val('');
            updateJsonData();
        }
    });

    $(document).on('click', '.remove-contact', function () {
        $(this).closest('.contact-field').remove();
        updateJsonData();
    });

    $.each(initialContacts, function (type, values) {
        if (Array.isArray(values)) {
            $.each(values, function (i, value) {
                addContactField(type, value);
            });
        } else {
            addContactField(type, values);
        }
    });
    updateJsonData();
});