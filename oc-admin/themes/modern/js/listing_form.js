$(document).ready(function(){
    $("#parentCategory").bind('change', function(){
        var categoryID = $(this).val();
        if( categoryID == 0 ) {
            return false;
        }
        categories = window['categories_' + categoryID];
        if( !$.isArray(categories) ) {
            var options = '<option value="' + categoryID + '" >' + osc.langs.no_subcategory + '</option>';
            $('#childCategory').html(options);
            $("#childCategory").next("a").find(".select-box-label").text(osc.langs.no_subcategory);
            return false;
        }
        var options = '<option value="' + categoryID + '" >' + osc.langs.select_subcategory + '</option>';
        $.each(categories, function(index, value){
            options += '<option value="' + value[0] + '">' + value[1] + '</option>';
        });
        $('#childCategory').html(options);
        $("#childCategory").next("a").find(".select-box-label").text(osc.langs.select_subcategory);
    });

    if( osc.item_post.category_id !== '' ) {
        $("#parentCategory").val(osc.item_post.category_id);
        $("#parentCategory").change();
        if( osc.item_post.subcategory_id !== '' ) {
            $("#childCategory").val(osc.item_post.subcategory_id);
            $("#childCategory").change();
        }
    }
});