jQuery(function ($) {
    let field_row = $('#formfield-container');
    field_row.each(function (e) {
        $(this).on('change', 'select', function (e) {
            let select = $($(this).find('select').context).val();
            let option = $('#_form_fields_options_' + $($(this).find('select').context).attr('data-iterator')).attr('id');
            if (select === 'select'||select === 'radio'||select === 'checkbox') {
                $('#' + option).parent().removeClass('hide')
            } else{
                $('#' + option).parent().addClass('hide')
            }
        })
    });
});

jQuery(function ($) {
    $(".form_field_required input[type='checkbox']").each(function () {
        if ($(this).val() !== 'on') {
            $(this).removeAttr('checked');
        }
    });
});

jQuery(function ($) {
    $('#form_field_id input').each(function () {
        $(this).on('keyup', function () {
            $(this).val(form_replace_val($(this).val()));
        })
    })
});

function form_replace_val(text) {
    return text.replace(/[_\s]/g, '-')
        .replace('á', 'a')
        .replace('ã', 'a')
        .replace('à', 'a')
        .replace('â', 'a')
        .replace('â', 'a')
        .replace('é', 'e')
        .replace('è', 'e')
        .replace('í', 'i')
        .replace('ì', 'i')
        .replace('ó', 'o')
        .replace('ò', 'o')
        .replace('õ', 'o')
        .replace('ô', 'o')
        .replace('ú', 'u')
        .replace('ù', 'u')
        .replace('ç', 'c')
}