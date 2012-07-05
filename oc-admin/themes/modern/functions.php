<?php
osc_add_filter('admin_body_class', 'admin_modeCompact_class');
function admin_modeCompact_class($args){
    $compactMode = osc_get_preference('compact_mode','modern_admin_theme');
    if($compactMode == true){
        $args[] = 'compact';
    }
    return $args;
}
osc_add_hook('ajax_admin_compactmode','modern_compactmode_actions');
function modern_compactmode_actions(){
    $compactMode = osc_get_preference('compact_mode','modern_admin_theme');
    $modeStatus  = array('compact_mode'=>true);
    if($compactMode == true){
        $modeStatus['compact_mode'] = false;
    }
    osc_set_preference('compact_mode', $modeStatus['compact_mode'], 'modern_admin_theme');
    echo json_encode($modeStatus);
}
function printLocaleTabs($locales = null)
{
    if($locales==null) { $locales = osc_get_locales(); }
    $num_locales = count($locales);
    if($num_locales>1) {
    echo '<div id="language-tab" class="ui-osc-tabs ui-tabs ui-widget ui-widget-content ui-corner-all"><ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">';
        foreach($locales as $locale) {
            echo '<li class="ui-state-default ui-corner-top"><a href="#'.$locale['pk_c_code'].'">'.$locale['s_name'].'</a></li>';
        }
    echo '</ul></div>';
    };
}

function printLocaleTitle($locales = null,$item = null)
{
    if($locales==null) { $locales = osc_get_locales(); }
    if($item==null) { $item = osc_item(); }
    $num_locales = count($locales);
    foreach($locales as $locale) {
    	echo '<div class="input-has-placeholder input-title-wide"><label for="title">' . __('Enter title here') . ' *</label>';
    	$title = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '' ;
        if( Session::newInstance()->_getForm('title') != "" ) {
            $title_ = Session::newInstance()->_getForm('title');
            if( $title_[$locale['pk_c_code']] != "" ){
                $title = $title_[$locale['pk_c_code']];
            }
        }
        $name = 'title'. '[' . $locale['pk_c_code'] . ']';
        echo '<input id="' . $name . '" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '"  />' ;
        echo '</div>';
    }
}
function printLocaleTitlePage($locales = null,$page = null)
{
    if($locales==null) { $locales = osc_get_locales(); }
    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
    $num_locales = count($locales);

    foreach($locales as $locale) {
        $title = '';
        if(isset($page['locale'][$locale['pk_c_code']])) {
            $title = $page['locale'][$locale['pk_c_code']]['s_title'];
        }
        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_title']) &&$aFieldsDescription[$locale['pk_c_code']]['s_title'] != '' ) {
            $title = $aFieldsDescription[$locale['pk_c_code']]['s_title'];
        }
        $name = $locale['pk_c_code'] . '#s_title';

        echo '<div><label for="title">' . __('Title') . ' *</label>';
        echo '<div class="input-has-placeholder input-title-wide"><label for="title">' . __('Enter title here') . ' *</label>';
        echo '<input id="' . $name . '" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '"  />' ;
        echo '</div>';
    }
}

function printLocaleDescription($locales = null,$item = null)
{
    if($locales==null) { $locales = osc_get_locales(); }
    if($item==null) { $item = osc_item(); }
    $num_locales = count($locales);
    foreach($locales as $locale) {
	   	$name = 'description'. '[' . $locale['pk_c_code'] . ']';

        echo '<div><label for="description">' . __('Description') . ' *</label>';
        $description = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '';
        if( Session::newInstance()->_getForm('description') != "" ) {
            $description_ = Session::newInstance()->_getForm('description');
            if( $description_[$locale['pk_c_code']] != "" ){
                $description = $description_[$locale['pk_c_code']];
            }
        }
        echo '<textarea id="' . $name . '" name="' . $name . '" rows="10">' . $description . '</textarea></div>' ;
    }
}
function printLocaleDescriptionPage($locales = null,$page = null)
{
    if($locales==null) { $locales = osc_get_locales(); }
    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
    $num_locales = count($locales);

    foreach($locales as $locale) {
        $description = '';
        if(isset($page['locale'][$locale['pk_c_code']])) {
            $description = $page['locale'][$locale['pk_c_code']]['s_text'];
        }
        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_text']) &&$aFieldsDescription[$locale['pk_c_code']]['s_text'] != '' ) {
            $description = $aFieldsDescription[$locale['pk_c_code']]['s_text'];
        }
        $name = $locale['pk_c_code'] . '#s_text';
        echo '<div><label for="description">' . __('Description') . ' *</label>';
        echo '<textarea id="' . $name . '" name="' . $name . '" rows="10">' . $description . '</textarea></div>' ;
    }
}

function jsLoacaleSelector()
{
    $locales = osc_get_locales();
    $codes = array();
    foreach($locales as $locale) {
        $codes[] = '\''.osc_esc_js($locale['pk_c_code']).'\'';
    }
	?>
	<script type="text/javascript">
		var locales = new Object;
		locales.current = '<?php echo osc_esc_js($locales[0]['pk_c_code']); ?>';
		locales.codes = new Array(<?php echo join(',',$codes); ?>);

		locales.string = '[name*="'+locales.codes.join('"],[name*="')+'"],.'+locales.codes.join(',.');
		$(function(){
			$('#language-tab li a').click(function(){
				$('#language-tab li').removeClass('ui-state-active').removeClass('ui-tabs-selected');
				$(this).parent().addClass('ui-tabs-selected ui-state-active');
				var currentLocale = $(this).attr('href').replace('#','');
			    $(locales.string).parent().hide();
			    $('[name*="'+currentLocale+'"], .'+currentLocale).parent().show();
			    locales.current = currentLocale;
			    return false;
			}).triggerHandler('click');
		});
		function tabberAutomatic(){
			$('.tabber:hidden').show();
			$('.tabber h2').remove();
			$(locales.string).parent().hide();
			$('[name*="'+locales.current+'"],.'+locales.current).parent().show();
		}
	</script>
	<?php
}

osc_add_hook('admin_header','jsLoacaleSelector');