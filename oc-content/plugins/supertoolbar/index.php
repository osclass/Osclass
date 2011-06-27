<?php
/*
Plugin Name: Super ToolBar
Plugin URI: http://www.osclass.org/
Description: Add a toolbar to ads from user
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: supertoolbar
*/

require_once "SuperToolBar.php";

function supertoolbar_show() {

    $toolbar = SuperToolBar::newInstance();
    if(osc_is_web_user_logged_in()) {
        $toolbar->addOption('<a href="'.osc_user_dashboard_url().'" />'.__("Dashboard", "superuser").'</a>');
        $toolbar->addOption('<a href="'.osc_user_list_items_url().'" />'.__("Your items", "superuser").'</a>');
        $toolbar->addOption('<a href="'.osc_user_alerts_url().'" />'.__("Your alerts", "superuser").'</a>');
        $toolbar->addOption('<a href="'.osc_user_profile_url().'" />'.__("Your profile", "superuser").'</a>');

        if(Rewrite::newInstance()->get_location()=='item') {
            if(osc_item_user_id()==  osc_logged_user_id()) {
                $toolbar->addOption('<a href="'.osc_item_edit_url().'" />'.__("Edit this item", "superuser").'</a>');
                $toolbar->addOption('<a onclick="javascript:return confirm(\''.__('This action can not be undone. Are you sure you want to continue?', 'superuser').'\')" href="'.osc_item_delete_url().'" />'.__("Delete this item", "superuser").'</a>');

                if(osc_item_is_inactive()) {
                    $toolbar->addOption('<a href="'.osc_item_activate_url().'" />'.__("Activate this item", "superuser").'</a>');
                }
            }
        }
    }
   
    osc_run_hook("supertoolbar_hook");
    if(osc_is_web_user_logged_in()) {
        $toolbar->addOption('<a href="'.osc_user_logout_url().'" />'.__("Logout", "superuser").'</a>');
    }
    
    $toolbar_opts = $toolbar->getOptions();
    if(!empty($toolbar_opts)) {
?>
    
    <link href="<?php echo osc_base_url()."oc-content/plugins/".osc_plugin_folder(__FILE__)."style.css"; ?>" rel="stylesheet" type="text/css" />
    <div id="supertoolbar_toolbar" name="supertoolbar_toolbar">
        <?php echo implode(" | ", $toolbar_opts);?>
    </div>
<?php
    }
}

// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), '');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__)."_configure", '');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", '');

osc_add_hook("footer", "supertoolbar_show");
?>
