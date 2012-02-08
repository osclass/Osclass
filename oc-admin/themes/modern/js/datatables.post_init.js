jQuery(document).ready(function() {
	$('#check_all').click(function() {
		$('input', oTable.fnGetFilteredNodes()).attr('checked',this.checked);
	});

	// bulk actions 
	$('#bulk_apply').bind('click', function() {
        if( $('#bulk_actions option:selected').val() == "delete_all" ) {
            var to_delete = [] ;
            $('#datatables_list input:checked').each(function() {
                if( $(this).val() != "on" ) {
                    to_delete.push( $(this).val() ) ;
                }
            }) ;

            if( to_delete.length >= 1 ) {
                $('#datatablesForm').submit() ;
            } else {
                return ;
            }
        }

        if( $('#bulk_actions option:selected').val() == "enable_selected" ) {
            var to_enable = [] ;
            $('#datatables_list input:checked').each(function() {
                if( $(this).val() != "on" ) {
                    to_enable.push( $(this).val() ) ;
                }
            });

            if( to_enable.length >= 1 ) {
                $('#form_action').val('enable_selected') ;
                $('#datatablesForm').submit() ;
            } else {
                return ;
            }
        }

        if($('#bulk_actions option:selected').val() == "disable_selected") {
            var to_disable = [] ;
            $('#datatables_list input:checked').each(function() {
                if($(this).val() != "on") {
                    to_disable.push( $(this).val() ) ;
                }
            });

            if( to_disable.length >= 1 ) {
                $('#form_action').val('disable_selected') ;
                $('#datatablesForm').submit() ;
            } else {
                return ;
            }
        }

        if( $('#bulk_actions option:selected').val() == "" ) {
            return ;
        }
	}) ;
 }) ;