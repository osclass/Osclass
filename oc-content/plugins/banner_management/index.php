<?php
/*
Plugin Name: Banner management
Plugin URI: http://www.osclass.org/
Description: Banner management system.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: banner_management
*/


function bm_call_after_install() {
    // Insert here the code you want to execute after the plugin's install
    // for example you might want to create a table or modify some values

    // In this case we'll create a table to store the Example attributes
    $conn = getConnection();

    $conn->autocommit(false);
    try {
        $path = osc_pluginResource('banner_management/struct.sql');
        $sql = file_get_contents($path);
        $conn->osc_dbImportSQL($sql);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function bm_call_after_uninstall() {
    // Insert here the code you want to execute after the plugin's uninstall
    // for example you might want to drop/remove a table or modify some values
    
    // In this case we'll remove the table we created to store Example attributes
    $conn = getConnection();
    $conn->autocommit(false);
    try {
        $conn->osc_dbExec('DROP TABLE %st_bm_banner_campaign', DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_bm_banner', DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_bm_campaign', DB_TABLE_PREFIX);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function bm_show_campaign($campaignName) {

    $conn = getConnection();
    $data = $conn->osc_dbFetchResults('SELECT b.s_name as b_name, b.s_code as b_code, b.i_weight as weight, b.i_views as views, c.s_code as c_code FROM %st_bm_banner_campaign as bc, %st_bm_banner as b, %st_bm_campaign as c WHERE c.s_campaign_name LIKE \'%s\' AND b.pk_i_id = bc.fk_i_banner_id AND bc.fk_i_campaign_id = c.pk_i_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $campaignName);
    $b_total = count($data);
    if($b_total>0) {
        $code = $data[0]['c_code'];
        if(preg_match_all('|\[banner#([^\]]+)\]|', $code, $banners)) {
            $total = count($banners[1]);
            for($var_b = 0;$var_b<$total;$var_b++) {
                for($var_c = 0; $var_c<$b_total;$var_c++) {
                    if($data[$var_c]['b_name']==$banners[1][$var_b]) {
                        $code = str_replace('[banner#'.$banners[1][$var_b].']', $data[$var_c]['b_code'], $code);
                        break;
                    }
                }
                $code = str_replace('[banner#'.$banners[1][$var_b].']', '[banner]', $code);
            }
        }
        $tmp = explode('[banner]', $code);
        $total = count($tmp);
        $code = $tmp[0];
        $repeat = ($b_total<$total)?false:true;
        for($var_a=1;$var_a<$total;$var_a++) {
            $r = rand(0,($b_total-1));
            $code .= $data[$r]['b_code'].$tmp[$var_a];
            if(!$repeat) {
                array_splice($data, $r, 1);
                $b_total--;
            }
        }
        echo $code;
    }
}






function bm_admin_menu() {
    echo '<h3><a href="#">Banner Management</a></h3>
    <ul> 
        <li><a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=banner">&raquo; '.__('Manage banners').'</a></li>
        <li><a href="plugins.php?action=renderplugin&file=banner_management/conf.php?section=campaign">&raquo; '.__('Manage Campaigns').'</a></li>
    </ul>';
}

// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'bm_call_after_install');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
//osc_addHook(__FILE__."_configure", 'dating_admin_configuration');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", 'bm_call_after_uninstall');


osc_addHook('admin_menu', 'bm_admin_menu');

?>
