<?php

/*
  Plugin Name: Real state attributes
  Plugin URI: http://www.osclass.org/
  Description: This plugin extends a category of items to store real estate attributes such as square feets, number of bathrooms, garage, and so on.
  Version: 1.0
  Author: OSClass
  Author URI: http://www.osclass.org/
  Short Name: realstate_plugin
  Plugin update URI: http://www.osclass.org/files/plugins/realstate_attributes/update.php
 */

// Adds some plugin-specific search conditions
function realstate_search_conditions($params) {

    // we need conditions and search tables (only if we're using our custom tables)
    global $conditions;
    global $search_tables;
    if (isset($params[0])) {
        $_param = $params[0];
        $has_conditions = false;
        foreach ($_param as $key => $value) {
            if ($value != "") {
                // We may want to  have param-specific searches
                switch ($key) {
                    case 'numFloor':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.i_num_floors >= %d AND %st_item_house_attr.i_num_floors <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'rooms':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.i_num_rooms >= %d AND %st_item_house_attr.i_num_rooms <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'rooms_min':
                        $conditions[] = sprintf("%st_item_house_attr.i_num_rooms >= %d", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'bathrooms':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.i_num_bathrooms >= %d AND %st_item_house_attr.i_num_bathrooms <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'garages':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.i_num_garages >= %d AND %st_item_house_attr.i_num_garages <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'year':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.i_year >= %d AND %st_item_house_attr.i_year <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'sq':
                        if (preg_match('|([0-9]+) - ([0-9]+)|', $value, $match)) {
                            $conditions[] = sprintf("%st_item_house_attr.s_square_meters >= %d AND %st_item_house_attr.s_square_meters <= %d", DB_TABLE_PREFIX, $match[1], DB_TABLE_PREFIX, $match[2]);
                            $has_conditions = true;
                        }
                        break;
                    case 'heating':
                        $conditions[] = sprintf("%st_item_house_attr.b_heating = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'airCondition':
                        $conditions[] = sprintf("%st_item_house_attr.b_air_condition = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'elevator':
                        $conditions[] = sprintf("%st_item_house_attr.b_elevator = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'terrace':
                        $conditions[] = sprintf("%st_item_house_attr.b_terrace = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'parking':
                        $conditions[] = sprintf("%st_item_house_attr.b_parking = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'furnished':
                        if ($value != "") {
                            $conditions[] = sprintf("%st_item_house_attr.b_furnished = %d ", DB_TABLE_PREFIX, $value);
                            $has_conditions = true;
                        }
                        break;
                    case 'new':
                        $conditions[] = sprintf("%st_item_house_attr.b_new = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'by_owner':
                        $conditions[] = sprintf("%st_item_house_attr.b_by_owner = %d ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'property_type':
                        $conditions[] = sprintf("%st_item_house_attr.e_type = '%s' ", DB_TABLE_PREFIX, $value);
                        $has_conditions = true;
                        break;
                    case 'p_type':
                        if(is_int($value)) {
                            $conditions[] = sprintf("%st_item_house_property_type_attr.pk_i_id = %d ", DB_TABLE_PREFIX, $value);
                        } else {
                            $conditions[] = sprintf("%st_item_house_property_type_attr.s_name LIKE '%%%s%%'  ", DB_TABLE_PREFIX, $value);
                        }
                        $has_conditions = true;
                        break;
                    default:
                        break;
                }
            }
        }

        // Only if we have some values at the params we add our table and link with the ID of the item.
        if ($has_conditions) {
            $conditions[] = sprintf("%st_item.pk_i_id = %st_item_house_attr.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            $conditions[] = sprintf("%st_item.pk_i_id = %st_item_house_description_attr.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            $conditions[] = sprintf("%st_item_house_property_type_attr.pk_i_id = %st_item_house_attr.fk_i_property_type_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            $search_tables[] = sprintf("%st_item_house_attr", DB_TABLE_PREFIX);
            $search_tables[] = sprintf("%st_item_house_description_attr", DB_TABLE_PREFIX);
            $search_tables[] = sprintf("%st_item_house_property_type_attr", DB_TABLE_PREFIX);
        }
    }
}

function realstate_call_after_install() {
    // Insert here the code you want to execute after the plugin's install
    // for example you might want to create a table or modify some values
    // In this case we'll create a table to store the Example attributes
    $conn = getConnection() ;
    $conn->autocommit(false) ;
    try {
        $path = osc_pluginResource('realstate_attributes/struct.sql');
        $sql = file_get_contents($path);
        $conn->osc_dbImportSQL($sql);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function realstate_call_after_uninstall() {
    // Insert here the code you want to execute after the plugin's uninstall
    // for example you might want to drop/remove a table or modify some values
    // In this case we'll remove the table we created to store Example attributes
    $conn = getConnection() ;
    $conn->autocommit(false);
    try {
        $conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE s_plugin_name = 'realstate_plugin'", DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_item_house_attr', DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_item_house_description_attr', DB_TABLE_PREFIX);
        $conn->osc_dbExec('DROP TABLE %st_item_house_property_type_attr', DB_TABLE_PREFIX);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
    $conn->autocommit(true);
}

function realstate_form($catId = null) {
    // We received the categoryID
    if (isset($catId[0]) && $catId[0] != "") {
        // We check if the category is the same as our plugin
        if ($catId[0] == 'realstate_plugin' || osc_isThisCategory('realstate_plugin', $catId[0])) {
            $conn = getConnection() ;
            $data = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr', DB_TABLE_PREFIX);
            $p_type = array();
            foreach ($data as $d) {
                $p_type[$d['fk_c_locale_code']][$d['pk_i_id']] = $d['s_name'];
            }
            unset($data);
            include_once 'form.php';
        }
    }
}

function realstate_search_form($catId = null) {
    // We received the categoryID
    if (isset($catId[0]) && $catId[0] != "") {
        // We check if the category is the same as our plugin
        if ($catId[0] == 'realstate_plugin' || osc_isThisCategory('realstate_plugin', $catId[0])) {
            $conn = getConnection() ;
            $data = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr', DB_TABLE_PREFIX);
            $p_type = array();
            foreach ($data as $d) {
                $p_type[$d['fk_c_locale_code']][$d['pk_i_id']] = $d['s_name'];
            }
            unset($data);
            include_once 'search_form.php';
        }
    }
}

function realstate_form_post($data = null) {
    // We received the categoryID and the Item ID
    $conn = getConnection() ;
    if (isset($data[0]) && $data[0] != "") {
        // We check if the category is the same as our plugin
        if ($data[0] == 'realstate_plugin' || osc_isThisCategory('realstate_plugin', $data[0])) {
            if (isset($data[1])) {
                $item = $data[1];
                // Insert the data in our plugin's table
                $conn->osc_dbExec("REPLACE INTO %st_item_house_attr (fk_i_item_id, s_square_meters, i_num_rooms, i_num_bathrooms, e_type, fk_i_property_type_id, e_status, i_num_floors, i_num_garages, b_heating, b_air_condition, b_elevator, b_terrace, b_parking, b_furnished, b_new, b_by_owner, s_condition, i_year, s_agency, i_floor_number, i_plot_area ) VALUES (%d, %d, %d, %d, '%s', %d, '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, '%s', %d, '%s', %d, %d)",
                        DB_TABLE_PREFIX,
                        $item['id'],
                        $_POST['squareMeters'],
                        $_POST['numRooms'],
                        $_POST['numBathrooms'],
                        $_POST['property_type'],
                        $_POST['p_type'],
                        $_POST['status'],
                        $_POST['numFloors'],
                        $_POST['numGarages'],
                        isset($_POST['heating']) ? 1 : 0,
                        isset($_POST['airCondition']) ? 1 : 0,
                        isset($_POST['elevator']) ? 1 : 0,
                        isset($_POST['terrace']) ? 1 : 0,
                        isset($_POST['parking']) ? 1 : 0,
                        isset($_POST['furnished']) ? 1 : 0,
                        isset($_POST['new']) ? 1 : 0,
                        isset($_POST['by_owner']) ? 1 : 0,
                        $_POST['condition'],
                        $_POST['year'],
                        $_POST['agency'],
                        $_POST['floorNumber'],
                        $_POST['squareMetersTotal']
                );
                // prepare locales
                $dataItem = array();
                foreach ($_REQUEST as $k => $v) {
                    if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                        $dataItem[$m[1]][$m[2]] = $v;
                    }
                }

                // insert locales
                foreach ($dataItem as $k => $_data) {
                    $conn->osc_dbExec("REPLACE INTO %st_item_house_description_attr (fk_i_item_id, fk_c_locale_code, s_transport, s_zone) VALUES (%d, '%s', '%s', '%s')",
                            DB_TABLE_PREFIX,
                            $item['id'],
                            $k,
                            $_data['transport'],
                            $_data['zone']
                    );
                }
            }
        }
    }
}

// Self-explanatory
function realstate_item_detail($_item) {
    $item = $_item[0];
    if (osc_isThisCategory('realstate_plugin', $item['fk_i_category_id'])) {
        $conn = getConnection() ;
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_house_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);

        $descriptions = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_description_attr WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
        $detail['locale'] = array();
        foreach ($descriptions as $desc) {
            $detail['locale'][$desc['fk_c_locale_code']] = $desc;
        }
        $types = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $detail['fk_i_property_type_id']);
        foreach ($types as $type) {
            $detail['locale'][$type['fk_c_locale_code']]['s_name'] = $type['s_name'];
        }
        require_once 'item_detail.php';
    }
}

// Self-explanatory
function realstate_item_edit($_item) {
    $item = $_item[0];
    if (osc_isThisCategory('realstate_plugin', $item['fk_i_category_id'])) {
        $conn = getConnection() ;
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_house_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);

        $descriptions = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_description_attr WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
        $detail['locale'] = array();
        foreach ($descriptions as $desc) {
            $detail['locale'][$desc['fk_c_locale_code']] = $desc;
        }
        $data = $conn->osc_dbFetchResults('SELECT * FROM %st_item_house_property_type_attr', DB_TABLE_PREFIX);
        $p_type = array();
        foreach ($data as $d) {
            $p_type[$d['fk_c_locale_code']][$d['pk_i_id']] = $d['s_name'];
        }
        unset($data);
        require_once 'item_edit.php';
    }
}

function realstate_item_edit_post() {
    // We received the categoryID and the Item ID
    if (isset($_POST['catId']) && $_POST['catId'] != "") {
        // We check if the category is the same as our plugin
        if ($_POST['catId'] == 'realstate_plugin' || osc_isThisCategory('realstate_plugin', $_POST['catId'])) {
            $conn = getConnection() ;
            $conn->osc_dbExec("REPLACE INTO %st_item_house_attr (fk_i_item_id, s_square_meters, i_num_rooms, i_num_bathrooms, e_type, fk_i_property_type_id, e_status, i_num_floors, i_num_garages, b_heating, b_air_condition, b_elevator, b_terrace, b_parking, b_furnished, b_new, b_by_owner, s_condition, i_year, s_agency, i_floor_number, i_plot_area ) VALUES (%d, '%s', %d, %d, '%s', %d, '%s', %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, '%s', '%s', '%s', %d, %d)",
                    DB_TABLE_PREFIX,
                    $_POST['pk_i_id'],
                    $_POST['squareMeters'],
                    $_POST['numRooms'],
                    $_POST['numBathrooms'],
                    $_POST['property_type'],
                    $_POST['p_type'],
                    $_POST['status'],
                    $_POST['numFloors'],
                    $_POST['numGarages'],
                    isset($_POST['heating']) ? 1 : 0,
                    isset($_POST['airCondition']) ? 1 : 0,
                    isset($_POST['elevator']) ? 1 : 0,
                    isset($_POST['terrace']) ? 1 : 0,
                    isset($_POST['parking']) ? 1 : 0,
                    isset($_POST['furnished']) ? 1 : 0,
                    isset($_POST['new']) ? 1 : 0,
                    isset($_POST['by_owner']) ? 1 : 0,
                    $_POST['condition'],
                    $_POST['year'],
                    $_POST['agency'],
                    $_POST['floorNumber'],
                    $_POST['squareMetersTotal']
            );

            // prepare locales
            $dataItem = array();
            foreach ($_REQUEST as $k => $v) {
                if (preg_match('|(.+?)#(.+)|', $k, $m)) {
                    $dataItem[$m[1]][$m[2]] = $v;
                }
            }

            // insert locales
            foreach ($dataItem as $k => $_data) {
                $conn->osc_dbExec("REPLACE INTO %st_item_house_description_attr (fk_i_item_id, fk_c_locale_code, s_transport, s_zone) VALUES (%d, '%s', '%s', '%s')",
                        DB_TABLE_PREFIX,
                        $_POST['pk_i_id'],
                        $k,
                        $_data['transport'],
                        $_data['zone']
                );
            }
        }
    }
}

function realstate_admin_menu() {
    echo '<h3><a href="#">Realstate plugin</a></h3>
    <ul> 
        <li><a href="plugins.php?action=configure&plugin=realstate_attributes/index.php">&raquo; ' . __('Configure plugin') . '</a></li>
        <li><a href="plugins.php?action=renderplugin&file=realstate_attributes/conf.php?section=types">&raquo; ' . __('Property types') . '</a></li>
    </ul>';
}

function realstate_admin_configuration() {
    // Standard configuration page for plugin which extend item's attributes
    osc_configurePlugin(__FILE__);
}

// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'realstate_call_after_install');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__ . "_configure", 'realstate_admin_configuration');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__ . "_uninstall", 'realstate_call_after_uninstall');

// When publishing an item we show an extra form with more attributes
osc_addHook('item_form', 'realstate_form');
// To add that new information to our custom table
osc_addHook('item_form_post', 'realstate_form_post');

// When searching, display an extra form with our plugin's fields
osc_addHook('search_form', 'realstate_search_form');
// When searching, add some conditions
osc_addHook('search_conditions', 'realstate_search_conditions');

// Show an item special attributes
osc_addHook('item_detail', 'realstate_item_detail');

// Edit an item special attributes
osc_addHook('item_edit', 'realstate_item_edit');
// Edit an item special attributes POST
osc_addHook('item_edit_post', 'realstate_item_edit_post');

osc_addHook('admin_menu', 'realstate_admin_menu')
?>
