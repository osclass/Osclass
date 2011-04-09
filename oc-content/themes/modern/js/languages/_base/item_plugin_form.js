$(document).ready(function(){
    $("#catId").change(function(){
        updateCategoryPlugins();
    });
    updateCategoryPlugins();
});

function updateCategoryPlugins() {
    var cat_id = $('#catId').val();
    var result = '';

    if(cat_id != '') {
        $.ajax({
            type: "POST",
            url: '<?php echo osc_base_url(true); ?>',
            data: 'page=ajax&action=runhook&hook=item_form&catId=' + cat_id,
            dataType: 'text/html',
            success: function(data){
                $("#plugin-hook").html(data);
            }
        });
    }
}