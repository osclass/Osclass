/**
 * 
 * @author Max <max.podumal@gmail.com>
 * Apply some tweaks after datatables initialization
 * 
 */

 $(function() {

    var ts = parseInt((jQuery(window).height() - 270) / 22.9);

    $(".dataTables_filter > input").attr("value", sSearchName);
    $(".dataTables_filter > input").css("color", "#777");
 
    $(".dataTables_filter > input").focus(function(){
	    //test to see if the search field value is empty or "search" on focus, then remove our holding text
	    if($(this).attr("value") == ""|| $(this).attr("value") == sSearchName){
	    	$(this).attr("value", "");
	    }
	        $(this).css("color", "#000");
	});

        $('#datatables_list').before($("#TableToolsLinks"));

	$("#TableToolsToolbar").appendTo("div #datatables_list_length");
	
	$('#check_all').click(function() {
		$('input', oTable.fnGetFilteredNodes()).attr('checked',this.checked);
	});

	// Remove border-bottom from last tr :)
	$('#datatables_list tbody tr:last td').each(function() {
		$(this).css('border-bottom', '0');
	});		
	
	// hide paginate if we have less that selected records.
	/* if($('#datatables_list_paginate span:eq(2)').children().size() == 0) {
		$('#datatables_list_paginate').hide();
	}	
	*/
	
	// apply some styles 
	var border = '1px solid #AAAAAA';
	$('#datatables_list thead th:first').css('border-left', border);
	$('#datatables_list thead th:first').css('-moz-border-radius-topleft', '4px');

	$('#datatables_list thead th:last').css('border-right', border);
	$('#datatables_list thead th:last').css('-moz-border-radius-topright', '4px');
 
	// bulk actions 
	$('#bulk_apply').click(function() {
            if($('#bulk_actions option:selected').val() == "delete_all") {
                    var to_delete = [];
                    $('#datatables_list input:checked').each(function() {
                            if($(this).val() != "on")
                                    to_delete.push($(this).val());
                    });

                    if(to_delete.length >= 1) {
                            $('#datatablesForm').submit();
                    } else {
                            return false;
                    }

            }

            if($('#bulk_actions option:selected').val() == "enable_selected") {
                    var to_enable = [];
                    $('#datatables_list input:checked').each(function() {
                            if($(this).val() != "on")
                                    to_enable.push($(this).val());
                    });

                    if(to_enable.length >= 1) {
                            $('#form_action').val('enable_selected');
                            $('#datatablesForm').submit();
                    } else {
                            return false;
                    }
            }

            if($('#bulk_actions option:selected').val() == "disable_selected") {
                    var to_disable = [];
                    $('#datatables_list input:checked').each(function() {
                            if($(this).val() != "on")
                                    to_disable.push($(this).val());
                    });

                    if(to_disable.length >= 1) {
                            $('#form_action').val('disable_selected');
                            $('#datatablesForm').submit();
                    } else {
                            return false;
                    }
            }

            if ($('#bulk_actions option:selected').val() == "") {
                    return false;
            }
	});
 });

 