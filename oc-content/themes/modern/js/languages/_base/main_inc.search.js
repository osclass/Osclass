var sQuery = '<?php _e("ie. PHP Programmer", 'modern') ; ?>' ;

$(document).ready(function(){
    if($('input[name=sPattern]').val() == sQuery) {
        $('input[name=sPattern]').css('color', 'gray');
    }
    $('input[name=sPattern]').click(function(){
        if($('input[name=sPattern]').val() == sQuery) {
            $('input[name=sPattern]').val('');
            $('input[name=sPattern]').css('color', '');
        }
    });
    $('input[name=sPattern]').blur(function(){
        if($('input[name=sPattern]').val() == '') {
            $('input[name=sPattern]').val(sQuery);
            $('input[name=sPattern]').css('color', 'gray');
        }
    });
    $('input[name=sPattern]').keypress(function(){
        $('input[name=sPattern]').css('background','');
    })
});
function doSearch() {
    if($('input[name=sPattern]').val() == sQuery){
        return false;
    }
    if($('input[name=sPattern]').val().length < 3) {
        $('input[name=sPattern]').css('background', '#FFC6C6');
        return false;
    }
    return true;
}