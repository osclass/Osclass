/**
 * photo: click
 */
$(document).ready(function(){
    $('#add-photo').click(function(event){
        event.preventDefault();
        add_NewPhoto();
    });
});


/**
 * photo: add new file field
 */
function add_NewPhoto(){
    var new_File = $('#photos').append('<div>' +
        '<input type="file" name="photos[]" />' + 
        '<a href="#"><?php _e('Remove', 'modern'); ?></a>' +
        '</div>');
    new_File.find('a').click(function(event){
        event.preventDefault();
        $(this).parent().fadeOut(125, function(){ $(this).remove(); });
    });
}


/**
 * photo: timed check & auto add when fields full
 */
function auto_AddField(){
    var $photo_List = $('#photos');
    var count = 0;
    $photo_List.find('input').each(function(){
        if ($(this).val() == ''){ count++; }
    });
    if (count == 0){ add_NewPhoto(); }
}
setInterval("auto_AddField()",250);