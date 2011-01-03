<?php
/*
Plugin Name: Bread crumbs
Plugin URI: http://www.osclass.org/
Description: Breadcrumbs navigation system.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: breadcrumbs
*/




function breadcrumbs() {

global $osc_request, $preferences;


    // You could modify the separator
    $separator = " / ";


    // You DO NOT have to modify anything else
    if($osc_request['location']=='search') {
        if(isset($_REQUEST['catId'])) {
            $category = $_REQUEST['catId'];
        } else if(isset($_REQUEST['category'])) {
            $category = urldecode($_REQUEST['category']);
            $category = preg_replace('|/$|','',$category);
            $slug_categories = explode('/', $category);
            $category = $slug_categories[count($slug_categories) - 1];
        }
    } else if($osc_request['location']=='item' && isset($osc_request['item'])) {
        $category = $osc_request['item']['fk_i_category_id'];
    }
    
    $bc_text = "<a href='".ABS_WEB_URL."' ><span class='bc_root'>".$preferences['pageTitle']."</span></a>";
    $deep_c = -1;
    if(isset($category)) {
        $cats = Category::newInstance()->toRootTree($category);
        foreach($cats as $cat) {
            $deep_c++;
            $bc_text .= $separator."<a href='".osc_createCategoryURL($cat)."' ><span class='bc_level_".$deep_c."'>".$cat['s_name']."</span></a>";
        }
    } else if($osc_request['location']!='index' && $osc_request['location']!='') {
        $bc_text .= $separator."<span class='bc_location'>".$osc_request['location']."</span>";
    }

    if(isset($osc_request['section']) && $osc_request['section']!='') {
        if($osc_request['location']=='item' && isset($osc_request['item'])) {
            $bc_text .= $separator."<a href='".osc_createItemURL($osc_request['item'])."' ><span class='bc_last'>".$osc_request['section']."</span></a>";
        } else {
            $bc_text .= $separator."<span class='bc_last'>".$osc_request['section']."</span>";
        }
    } else {
        $bc_text = str_replace('bc_level_'.$deep_c, 'bc_last', str_replace('bc_location', 'bc_last', $bc_text));
    }

    echo $bc_text;

}


function breadcrumbs_help() {
    osc_renderPluginView(dirname(__FILE__) . '/help.php') ;
}




// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, '');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_configure", 'breadcrumbs_help');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", '');


?>
