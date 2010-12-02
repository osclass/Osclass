<?php
/*
Plugin Name: Cars attributes
Plugin URI: http://www.osclass.org/
Description: This plugin extends a category of items to store cars attributes such as model, year, brand, color, accessories, and so on.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: cars_plugin
Plugin update URI: http://www.osclass.org/files/plugins/cars_attributes/update.php
*/



// Adds some plugin-specific search conditions
function cars_search_conditions($params) {

	// we need conditions and search tables (only if we're using our custom tables)
	global $conditions;
	global $search_tables;
	if(isset($params[0])) {

		$_param = $params[0];
		$has_conditions = false;

		foreach($_param as $key => $value) {

			// We may want to  have param-specific searches 
			switch($key) {

				default:
				break;

			}
		}

		// Only if we have some values at the params we add our table and link with the ID of the item.
		if($has_conditions) {
			$conditions[] = sprintf("%st_item.pk_i_id = %st_item_car_attr.fk_i_item_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
			$search_tables[] = sprintf("%st_item_car_attr", DB_TABLE_PREFIX);
		}

	}	
}



function cars_call_after_install() {

	// Insert here the code you want to execute after the plugin's install
	// for example you might want to create a table or modify some values
	
	// In this case we'll create a table to store the Example attributes	
	$conn = getConnection() ;
	$conn->autocommit(false) ;
	try {
		$path = osc_pluginResource('cars_attributes/struct.sql');
		$sql = file_get_contents($path);
		$conn->osc_dbImportSQL($sql);		
		$conn->commit();
	} catch (Exception $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	$conn->autocommit(true);

}

function cars_call_after_uninstall() {

	// Insert here the code you want to execute after the plugin's uninstall
	// for example you might want to drop/remove a table or modify some values
	
	// In this case we'll remove the table we created to store Example attributes	
	$conn = getConnection() ;
	$conn->autocommit(false);
	try {
		$conn->osc_dbExec("DELETE FROM %st_plugin_category WHERE s_plugin_name = 'cars_plugin'", DB_TABLE_PREFIX);
		$conn->osc_dbExec('DROP TABLE %st_item_car_attr', DB_TABLE_PREFIX);
		$conn->osc_dbExec('DROP TABLE %st_item_car_model_attr', DB_TABLE_PREFIX);
		$conn->osc_dbExec('DROP TABLE %st_item_car_make_attr', DB_TABLE_PREFIX);
		$conn->osc_dbExec('DROP TABLE %st_item_car_vehicle_type_attr', DB_TABLE_PREFIX);
		$conn->commit();
	} catch (Exception $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	$conn->autocommit(true);
}


function cars_form($catId = null) {
    $conn = getConnection() ;
    // We received the categoryID
	if(isset($catId[0]) && $catId[0]!="") {
		// We check if the category is the same as our plugin
		if(osc_isThisCategory('cars_plugin', $catId[0])) {
            $make = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_make_attr ORDER BY s_name ASC', DB_TABLE_PREFIX);
            $data = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_vehicle_type_attr', DB_TABLE_PREFIX);
            $car_type = array();
            foreach($data as $d) {
                $car_type[$d['fk_c_locale_code']][$d['pk_i_id']] = $d['s_name'];
            }
            unset($data);
			require_once 'form.php';
		}
	}
}

function cars_search_form($catId = null) {

	// We received the categoryID
	if(isset($catId[0]) && $catId[0]!="") {
		// We check if the category is the same as our plugin
		if(osc_isThisCategory('realstate_plugin', $catId[0])) {
			include_once 'search_form.php';
		}
	}
}


function cars_form_post($data = null) 
{
    $conn = getConnection() ;
	// We received the categoryID and the Item ID
	if(isset($data[0]) && $data[0]!="") {
		// We check if the category is the same as our plugin
		if(osc_isThisCategory('cars_plugin', $data[0])) {
			if(isset($data[1])) {
				$item = $data[1];
				// Insert the data in our plugin's table
                    $conn->osc_dbExec("INSERT INTO %st_item_car_attr (fk_i_item_id, i_year, i_doors, i_seats, i_mileage, i_engine_size, i_num_airbags, e_transmission, e_fuel, e_seller, b_warranty, b_new, i_power, e_power_unit, i_gears, fk_i_make_id, fk_i_model_id, fk_vehicle_type_id) VALUES (%d, %d, %d, %d, %d, %d, %d, '%s', '%s', '%s', %d, %d, %d, '%s', %d, %d, %d, %d)",
						DB_TABLE_PREFIX,
						$item['id'],
						$_POST['year'],
						$_POST['doors'],
						$_POST['seats'],
						$_POST['mileage'],
						$_POST['engine_size'],
						$_POST['num_airbags'],
						$_POST['transmission'],
						$_POST['fuel'],
						$_POST['seller'],
						isset($_POST['warranty']) ? 1 : 0,
						isset($_POST['new']) ? 1 : 0,
						$_POST['power'],
						$_POST['power_unit'],
						$_POST['gears'],
						$_POST['make'],
						$_POST['model'],
						$_POST['car_type']
					);
			}
		}
	}
}

// Self-explanatory
function cars_item_detail($_item) {
    $conn = getConnection() ;
	$item = $_item[0];
    if(osc_isThisCategory('cars_plugin', $item['fk_i_category_id'])) {
	    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_car_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
        $make = $conn->osc_dbFetchResult('SELECT * FROM %st_item_car_make_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $detail['fk_i_make_id']);
        $model = $conn->osc_dbFetchResult('SELECT * FROM %st_item_car_model_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $detail['fk_i_model_id']);
        $car_type = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_vehicle_type_attr WHERE pk_i_id = %d', DB_TABLE_PREFIX, $detail['fk_vehicle_type_id']);
        $detail['s_make'] = $make['s_name'];
        $detail['s_model'] = $make['s_name'];
        $detail['locale'] = array();
        foreach ($car_type as $c) {
            $detail['locale'][$c['fk_c_locale_code']]['s_car_type'] = $c['s_name'];
        }
	    require_once 'item_detail.php';
    }
}


// Self-explanatory
function cars_item_edit($_item) {

    $conn = getConnection() ;
    $item = $_item[0];
    if(osc_isThisCategory('cars_plugin', $item['fk_i_category_id'])) {
	    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_car_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);

        $make = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_make_attr ORDER BY s_name ASC', DB_TABLE_PREFIX);
        $model = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_model_attr WHERE `fk_i_make_id` = %d ORDER BY s_name ASC', DB_TABLE_PREFIX, $detail['fk_i_make_id']);
        $data = $conn->osc_dbFetchResults('SELECT * FROM %st_item_car_vehicle_type_attr', DB_TABLE_PREFIX);
        $car_type = array();
        foreach($data as $d) {
            $car_type[$d['fk_c_locale_code']][$d['pk_i_id']] = $d['s_name'];
        }
        unset($data);
	    require_once 'item_edit.php';
    }
}

function cars_item_edit_post() {
	// We received the categoryID and the Item ID
	if(isset($_POST['catId']) && $_POST['catId']!="") 
	{
		// We check if the category is the same as our plugin
		if(osc_isThisCategory('cars_plugin', $_POST['catId']))
		{
			$conn = getConnection() ;
			// Insert the data in our plugin's table
            $conn->osc_dbExec("REPLACE INTO %st_item_car_attr (fk_i_item_id, i_year, i_doors, i_seats, i_mileage, i_engine_size, i_num_airbags, e_transmission, e_fuel, e_seller, b_warranty, b_new, i_power, e_power_unit, i_gears, fk_i_make_id, fk_i_model_id, fk_vehicle_type_id) VALUES (%d, %d, %d, %d, %d, %d, %d, '%s', '%s', '%s', %d, %d, %d, '%s', %d, %d, %d, %d)",
				DB_TABLE_PREFIX,
				$_POST['pk_i_id'],
				$_POST['year'],
				$_POST['doors'],
				$_POST['seats'],
				$_POST['mileage'],
				$_POST['engine_size'],
				$_POST['num_airbags'],
				$_POST['transmission'],
				$_POST['fuel'],
				$_POST['seller'],
				isset($_POST['warranty']) ? 1 : 0,
				isset($_POST['new']) ? 1 : 0,
				$_POST['power'],
				$_POST['power_unit'],
				$_POST['gears'],
				$_POST['make'],
				$_POST['model'],
				$_POST['car_type']
			);
		}
	}
}


function cars_admin_menu() {
    echo '<h3><a href="#">Cars plugin</a></h3>
    <ul> 
        <li><a href="plugins.php?action=configure&plugin=cars_attributes/index.php">&raquo; '.__('Configure plugin').'</a></li>
        <li><a href="plugins.php?action=renderplugin&file=cars_attributes/conf.php?section=makes">&raquo; '.__('Manage makes').'</a></li>
        <li><a href="plugins.php?action=renderplugin&file=cars_attributes/conf.php?section=models">&raquo; '.__('Manage models').'</a></li>
        <li><a href="plugins.php?action=renderplugin&file=cars_attributes/conf.php?section=types">&raquo; '.__('Manage vehicle types').'</a></li>
    </ul>';
}



function cars_admin_configuration() {

	// Standard configuration page for plugin which extend item's attributes
	osc_configurePlugin(__FILE__);

}




// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, 'cars_call_after_install');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_configure", 'cars_admin_configuration');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", 'cars_call_after_uninstall');

// When publishing an item we show an extra form with more attributes
osc_addHook('item_form', 'cars_form');
// To add that new information to our custom table
osc_addHook('item_form_post', 'cars_form_post');

// When searching, display an extra form with our plugin's fields
osc_addHook('search_form', 'cars_search_form');
// When searching, add some conditions
osc_addHook('search_conditions', 'cars_search_conditions');

// Show an item special attributes
osc_addHook('item_detail', 'cars_item_detail');

// Edit an item special attributes
osc_addHook('item_edit', 'cars_item_edit');
// Edit an item special attributes POST
osc_addHook('item_edit_post', 'cars_item_edit_post');

//
osc_addHook('admin_menu', 'cars_admin_menu')

?>
