jQuery(function ($) {
    let options = $('#formfield_options');
    let formselect = $('#form_field_select select');

    formselect.each(function () {
        $(this).on('change', function () {
            if ($(this).val() === 'select' || $(this).val() === 'radio' || $(this).val() === 'checkbox') {
                $(this).parent().next().removeClass('hide');
            } else {
                $(this).parent().next().addClass('hide');
            }
        })
    });
});

jQuery(function ($){
   $(".form_field_required input[type='checkbox']").each(function (){
      if($(this).val() !== 'on'){
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