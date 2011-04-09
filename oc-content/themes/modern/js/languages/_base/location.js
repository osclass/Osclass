$(document).ready(function(){
    /**
     * update select lists: country, region, city
     */
    $("#countryId").change(function(){
        var pk_c_code = $(this).val();
        var url = '<?php echo osc_base_url(true)."?page=ajax&action=regions&countryId="; ?>' + pk_c_code;
        var result = '';

        if(pk_c_code != '') {
            $("#regionId").attr('disabled',false);
            $("#cityId").attr('disabled',true);
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function(data){
                    var length = data.length;
                    if(length > 0) {
                        result += '<option value=""><?php echo sprintf(__("Select a %s",'modern'), __("region", 'modern')) . "..."; ?></option>';
                        for(key in data) {
                            result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                        }
                        $("#region").before('<select name="regionId" id="regionId"></select>');
                        $("#region").remove();
                    } else {
                        result += '<option value=""><?php _e('No results','modern') ?></option>';
                        $("#regionId").before('<input type="text" name="region" id="region" />');
                        $("#regionId").remove();
                    }
                    $("#regionId").html(result);
                }
             });
         } else {
            $("#regionId").attr('disabled',true);
            $("#cityId").attr('disabled',true);
         }
    });

    $("#regionId").change(function(){
        var pk_c_code = $(this).val();
        var url = '<?php echo osc_base_url(true)."?page=ajax&action=cities&regionId="; ?>' + pk_c_code;
        var result = '';

        if(pk_c_code != '') {
            $("#cityId").attr('disabled',false);
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                success: function(data){
                    var length = data.length;
                    if(length > 0) {
                        result += '<option value=""><?php echo sprintf(__("Select a %s",'modern'), __("city", 'modern')) . "..."; ?></option>';
                        for(key in data) {
                            result += '<option value="' + data[key].pk_i_id + '">' + data[key].s_name + '</option>';
                        }
                        $("#city").before('<select name="cityId" id="cityId"></select>');
                        $("#city").remove();
                    } else {
                        result += '<option value=""><?php _e('No results','modern') ?></option>';
                        $("#cityId").before('<input type="text" name="city" id="city" />');
                        $("#cityId").remove();
                    }
                    $("#cityId").html(result);
                }
             });
         } else {
            $("#cityId").attr('disabled',true);
         }
    });

    if( $("#regionId").attr('value') == ""){
        $("#cityId").attr('disabled',true);
    }
    
    if( $("#countryId").attr('type').match(/select-one/) ){
        if( $("#countryId").attr('value') == "")  {
            $("#regionId").attr('disabled',true);
        }
    }
});