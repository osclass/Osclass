jQuery(document).ready(function() {
	$('#check_all').click(function() {
		$('input', oTable.fnGetFilteredNodes()).attr('checked',this.checked);
	});
 }) ;