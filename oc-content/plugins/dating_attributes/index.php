<?php
/*
Plugin Name: Dating attributes
Plugin URI: http://www.osclass.org/
Description: This plugin extends a category of items to store dating attributes such as model, year, brand, color, accessories, and so on.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: dating_plugin
Plugin update URI: http://www.osclass.org/files/plugins/dating_attributes/update.php
*/

// Adds some plugin-specific search conditions
function dating_search_conditions($params) {
    // we need conditions and search tables (only if we're using our custom tables)
    global $conditions;
    global $search_tables;
    if(isset($params[0])) {
        $_param = $params[0];
        $has_conditions = false;
        foreach($_param as $key => $value) {
            // We may want to  have param-specific searches
            switch($key) {
                case 'genderFrom':
                    $conditions[] = sprintf("%st_item_dating_attr.e_gender_from = '%s'", DB_TABLE_PREFIX, $value);
                    $has_conditions = true;
                    break;
                case 'genderTo':
                    $conditions[] = sprintf("%st_item_dating_attr.e_gender_to = '%s'", DB_TABLE_PREFIX, $value);
                    $has_conditions = true;
                    break;
                case 'relation':
                    $conditions[] = sprintf("%st_item_dating_attr.e_relation = '%s'", DB_TABLE_PREFIX, $value);
                    $has_conditions = true;
                    break;
                default:
                    break;
            }
        }
        
        // Only if we have some values at the params we add our table and link with the ID of the item.
        if($has_conditions) {
            $conditions[] = sprintf("%st_item.pk_i_id = %st_item_dating_attr.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            $search_tables[] = sprintf("%st_item_dating_attr", DB_TABLE_PREFIX);
        }
    }
}

function dating_call_after_install() {
    // Insert here the code you want to execute after the plugin's install
    // for example you might want to create a table or modify some values

    // In this case we'll create a table to store the Example attributes
    $conn = getConnection();

    $con->autocommit(false);
    try {
        $path = osc_pluginResource('dating_attributes/struct.sql');
        $sql = file_get_contents($path);
        $conn->osc_dbImportSQL($sql);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function dating_call_after_uninstall() {
    // Insert here the code you want to execute after the plugin's uninstall
    // for example you might want to drop/remove a table or modify some values
	
    // In this case we'll remove the table we created to store Example attributes
    $conn = getConnection();
    $conn->autocommit(false);
    try {
        $conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE s_plugin_name = 'dating_plugin'", DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_item_dating_attr', DB_TABLE_PREFIX);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function dating_form($catId = null) {
    // We received the categoryID
    if(isset($catId[0]) && $catId[0]!="") {
        // We check if the category is the same as our plugin
        if(osc_isThisCategory('dating_plugin', $catId[0])) {
            require_once 'form.php';
        }
    }
}

function dating_search_form($catId = null) {
    // We received the categoryID
    if(isset($catId[0]) && $catId[0]!="") {
        // We check if the category is the same as our plugin
        if(osc_isThisCategory('dating_plugin', $catId[0])) {
            include_once 'search_form.php';
        }
    }
}

function dating_form_post($data = null) {
    // We received the categoryID and the Item ID
    if(isset($data[0]) && $data[0]!="") {
        // We check if the category is the same as our plugin
        if(osc_isThisCategory('dating_plugin', $data[0])) {
            if(isset($data[1])) {
                $item = $data[1];
                // Insert the data in our plugin's table
                $conn = getConnection();
                $conn->osc_dbExec("INSERT INTO %st_item_dating_attr (fk_i_item_id, e_gender_from, e_gender_to, e_relation) VALUES (%d, '%s', '%s', '%s')", DB_TABLE_PREFIX, $item['id'], $_POST['genderFrom'], $_POST['genderTo'], $_POST['relation']);
            }
        }
    }
}

// Self-explanatory
function dating_item_detail($_item) {
    $item = $_item[0];
    if(osc_isThisCategory('dating_plugin', $item['fk_i_category_id'])) {
        $conn = getConnection();
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_dating_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
        require_once 'item_detail.php';
    }
}

// Self-explanatory
function dating_item_edit($_item) {
    $item = $_item[0];
    if(osc_isThisCategory('dating_plugin', $item['fk_i_category_id'])) {
        $conn = getConnection();
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_dating_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
        if( isset($detail['fk_i_item_id']) ) {
            include_once 'item_edit.php';
        }
    }
}

function dating_item_edit_post() {
    // We received the categoryID and the Item ID
    if(isset($_POST['catId']) && $_POST['catId']!="") {
        // We check if the category is the same as our plugin
        if(osc_isThisCategory('dating_plugin', $_POST['catId'])) {
            $conn = getConnection();
            $conn->osc_dbExec("UPDATE %st_item_dating_attr SET `e_gender_from` = '%s', `e_gender_to` = '%s', `e_relation` = '%s' WHERE `fk_i_item_id` = %d", DB_TABLE_PREFIX, $_POST['genderFrom'], $_POST['genderTo'], $_POST['relation'], $_POST['pk_i_id'] );
        }
    }
}

function dating_admin_configuration() {
    // Standard configuration page for plugin which extend item's attributes
    osc_configurePlugin(__FILE__);
}

// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'dating_call_after_install');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_configure", 'dating_admin_configuration');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", 'dating_call_after_uninstall');

// When publishing an item we show an extra form with more attributes
osc_addHook('item_form', 'dating_form');
// To add that new information to our custom table
osc_addHook('item_form_post', 'dating_form_post');

// When searching, display an extra form with our plugin's fields
osc_addHook('search_form', 'dating_search_form');
// When searching, add some conditions
osc_addHook('search_conditions', 'dating_search_conditions');

// Show an item special attributes
osc_addHook('item_detail', 'dating_item_detail');

// Edit an item special attributes
osc_addHook('item_edit', 'dating_item_edit');
// Edit an item special attributes POST
osc_addHook('item_edit_post', 'dating_item_edit_post');
?>
