<?php
/*
 *      OSCLass – software for creating and publishing online classified
 *                           advertising platforms
 *
 *                        Copyright (C) 2010 OSCLASS
 *
 *       This program is free software: you can redistribute it and/or
 *     modify it under the terms of the GNU Affero General Public License
 *     as published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful, but
 *         WITHOUT ANY WARRANTY; without even the implied warranty of
 *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *             GNU Affero General Public License for more details.
 *
 *      You should have received a copy of the GNU Affero General Public
 * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'osclass/classes/DAO.php';
require_once 'osclass/model/Preference.php';
require_once 'osclass/utils.php';



function osc_runHook($hook) {

	$args = func_get_args();
	array_shift($args);
	global $active_plugins;
	if(isset($active_plugins[$hook])) {
        for($priority = 0;$priority<=10;$priority++) {
		    if(isset($active_plugins[$hook][$priority]) && is_array($active_plugins[$hook][$priority])) {
			    foreach($active_plugins[$hook][$priority] as $fxName) {
				    if(function_exists($fxName)) {
					    call_user_func($fxName, $args);
				    }
			    }
		    }
        }
	}
}

function osc_applyFilter($hook, $content) {

	$args = func_get_args();
	array_shift($args);
	global $active_plugins;
	if(isset($active_plugins[$hook])) {
        for($priority = 0;$priority<=10;$priority++) {
		    if(isset($active_plugins[$hook][$priority]) && is_array($active_plugins[$hook][$priority])) {
			    foreach($active_plugins[$hook][$priority] as $fxName) {
				    if(function_exists($fxName)) {
					    $content = call_user_func($fxName, $content, $args);
				    }
			    }
		    }
        }
	}
    return $content;
}

function osc_runHooks($hook) {
	osc_runHook($hook);
}

function osc_isPluginInstalled($plugin) {

	$p_installed = osc_listInstalledPlugins();	
	foreach($p_installed as $p) {
		if($p==$plugin) {
			return true;
		}
	}


	return false;
}

function osc_listAllPlugins() {
	$plugins = array();

	$pluginsPath = APP_PATH . '/oc-content/plugins';
	$dir = opendir($pluginsPath);
	while($file = readdir($dir)) {

		if(preg_match('/^[a-zA-Z0-9_]+$/', $file, $matches)) {
			// This has to change in order to catch any .php file
			$pluginPath = $pluginsPath . "/$file/index.php";
			if(file_exists($pluginPath)) {
				$plugins[] = $file."/index.php";
			} else {
				trigger_error(__('Plugin ') . $file . __(' is missing the index.php file.'));
			}
		}
	}
	closedir($dir);

	return $plugins;
}

//DEPRECATED
function osc_listPlugins() {
	$plugins = array();

	$pluginsPath = APP_PATH . '/oc-content/plugins';
	$dir = opendir($pluginsPath);
	while($file = readdir($dir)) {

		if(preg_match('/^[a-zA-Z0-9_]+$/', $file, $matches)) {
			$pluginPath = $pluginsPath . "/$file/index.php";
			if(file_exists($pluginPath)) {
				include_once $pluginPath;
				$fxName = $file . '_info';
				if(function_exists($fxName)) {
					$plugins[$file] = call_user_func($fxName);
					$plugins[$file]['int_name'] = $file;
				} else
					trigger_error(__('Plugin ') . $file . __(' is missing the function ') . $fxName .'.');
			} else
				trigger_error(__('Plugin ') . $file . __(' is missing the index.php file.'));
		}
	}
	closedir($dir);

	return $plugins;
}


function osc_loadActivePlugins() {

	global $active_plugins;

	try {
		
		$data['s_value'] = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data['s_value']);

		if(is_array($plugins_list)) {
			foreach($plugins_list as $plugin_name) {
				$pluginPath = APP_PATH . '/oc-content/plugins/'.$plugin_name;
				if(file_exists($pluginPath)) {
					//This should include the file and adds the hooks
					include_once $pluginPath;
				} else {
					trigger_error(__('Plugin ') . $plugin_name . __(' is missing the plugin\'s main file.'));
				}
			}
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function osc_listInstalledPlugins() {

	$p_array = array();
	try {
		
		$data['s_value'] = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data['s_value']);

		if(is_array($plugins_list)) {
			foreach($plugins_list as $plugin_name) {
				$p_array[] = $plugin_name;
			}
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

	return $p_array;
}


function osc_pluginResource($path) {
	$fullPath = APP_PATH . '/oc-content/plugins/' . $path;
	return file_exists($fullPath) ? $fullPath : false;
}


function osc_activatePlugin($path) {

	$conn = getConnection() ;
	$conn->autocommit(false);
	try {
		
		$data['s_value'] = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data['s_value']);

		$found_it = false;
		if(is_array($plugins_list)) {
			foreach($plugins_list as $plugin_name) {
				// Check if the plugin is already installed
				if($plugin_name==$path) {
					$found_it = true;
					break;
				}
			}
		}

		if(!$found_it) {
			$plugins_list[] = $path;
			$data['s_value'] = osc_serialize($plugins_list);
			$condition = array( 's_section' => 'osclass', 's_name' => 'active_plugins');		
			Preference::newInstance()->update($data, $condition);
			unset($condition);
			unset($data);
			$conn->commit();
		} else {
			echo "Error: Plugin already installed." ;
		}
	} catch (Exception $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	$conn->autocommit(true);

}


function osc_registerPlugin($path, $function) {

	$path = str_replace(APP_PATH . '/oc-content/plugins/', '', $path);
	osc_addHook('install_'.$path, $function);

}

//DEPRECATED FUNCTION
function osc_activatePluginHook($path) {

	$conn = getConnection() ;
	$conn->autocommit(false) ;
	try {
		
		$data['s_value'] = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data['s_value']);

		$path = str_replace(APP_PATH . '/oc-content/plugins/', '', $path);
		$found_it = false;
		if(is_array($plugins_list)) {
			foreach($plugins_list as $plugin_name) {
				// Check if the plugin is already installed
				if($plugin_name == $path) {
					$found_it = true;
					break;
				}
			}
		}

		if(!$found_it) {
			$plugins_list[] = $path;
			$data['s_value'] = osc_serialize($plugins_list);
			$condition = array( 's_section' => 'osclass', 's_name' => 'active_plugins');		
			Preference::newInstance()->update($data, $condition);
			unset($condition);
			unset($data);
			$conn->commit();
		} else {
			echo "Error: Plugin already installed.";
		}
	} catch (Exception $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	$conn->autocommit(true) ;
}





function osc_deactivatePlugin($path) 
{
	$conn = getConnection() ;
    $conn->autocommit(false);
	try {
		
		$data['s_value'] = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data['s_value']);

		$path = str_replace(APP_PATH . '/oc-content/plugins/', '', $path);
		if(is_array($plugins_list)) {
			foreach($plugins_list as $key=>$value){
				if($value==$path){
					unset($plugins_list[$key]);
				}
			}
		

			$data['s_value'] = osc_serialize($plugins_list);
			$condition = array( 's_section' => 'osclass', 's_name' => 'active_plugins');		
			Preference::newInstance()->update($data, $condition);
			unset($condition);
			unset($data);
			$conn->commit();
		}
	} catch (Exception $e) {
		$conn->rollback();
		echo $e->getMessage();
	}
	$conn->autocommit(true);

}

// Add a hook
function osc_addHook($hook, $function, $priority = 5) {

	//$args = func_get_args();

	global $active_plugins;
	$hook = str_replace(APP_PATH . '/oc-content/plugins/', '', $hook);
	$found_plugin = false;
	if(isset($active_plugins[$hook])) {
		if(is_array($active_plugins[$hook])) {
			foreach($active_plugins[$hook] as $fxName) {
				if($fxName==$function) {
					$found_plugin = true;
					break;
				}
			}
		}
	}

	if(!$found_plugin) {
		$active_plugins[$hook][$priority][] = $function;//$_f;
	}
}

function osc_removeHook($hook, $function) {

	global $active_plugins;
	unset($active_plugins[$hook][$function]);
}

function osc_isThisCategory($name, $id) {
	return PluginCategory::newInstance()->isThisCategory($name, $id);
}

function osc_getPluginInfo($plugin) {
	$s_info = file_get_contents(APP_PATH . '/oc-content/plugins/' . $plugin);
	$info = array();
	if(preg_match('|Plugin Name:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['plugin_name'] = trim($match[1]);
	} else { $info['plugin_name'] = $plugin; };
	
	if(preg_match('|Plugin URI:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['plugin_uri'] = trim($match[1]);
	} else { $info['plugin_uri'] = ""; };

	if(preg_match('|Plugin update URI:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['plugin_update_uri'] = trim($match[1]);
	} else { $info['plugin_update_uri'] = ""; };

	if(preg_match('|Description:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['description'] = trim($match[1]);
	} else { $info['description'] = ""; };

	if(preg_match('|Version:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['version'] = trim($match[1]);
	} else { $info['version'] = ""; };

	if(preg_match('|Author:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['author'] = trim($match[1]);
	} else { $info['author'] = ""; };

	if(preg_match('|Author URI:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['author_uri'] = trim($match[1]);
	} else { $info['author_uri'] = ""; };

	if(preg_match('|Short Name:([^\\r\\t\\n]*)|i', $s_info, $match)) {
		$info['short_name'] = trim($match[1]);
	} else { $info['short_name'] = $info['plugin_name']; };

	$info['filename'] = $plugin;
	
	return $info;

};


function osc_checkUpdate($plugin) {
	$info = osc_getPluginInfo($plugin);
	if($info['plugin_update_uri']!="") {
        if(false===($str=@osc_file_get_contents($info['plugin_update_uri']))) {
            return false;
        } else {
		    if(preg_match('|\?\(([^\)]+)|', preg_replace('/,\s*([\]}])/m', '$1', $str), $data)) {
		    	$json = json_decode($data[1] , true);
		    	if($json['version']>$info['version']) {
		    		return true;
		    	}
		    }
    	}
    }
	return false;
}


function osc_configurePlugin($path) {

	$plugin = str_replace(APP_PATH . '/oc-content/plugins/', '', $path);
	if(stripos($plugin, ".php")===FALSE) {
		$data = Preference::newInstance()->findValueByName('active_plugins');
		$plugins_list = osc_unserialize($data);

		if(is_array($plugins_list)) {
			foreach($plugins_list as $p){
				$data = osc_getPluginInfo($p);
				if($plugin==$data['plugin_name']){
					$plugin = $p;
					break;
				}
			}
		}
	}
	
	osc_redirectTo('plugins.php?action=configure&plugin='.$plugin);

}

function osc_cleanCategoryFromPlugin($plugin) {
    $dao_pluginCategory = new PluginCategory() ;
    $dao_pluginCategory->delete(array('s_plugin_name' => $plugin)) ;
    unset($dao_pluginCategory) ;
}

function osc_addToCategoryPlugin($categories, $plugin) {
	$dao_pluginCategory = new PluginCategory() ;
	$dao_category = new Category() ;
    foreach($categories as $catId)
	{
	    $result = $dao_pluginCategory->listWhere('s_plugin_name LIKE \'' . $plugin . '\' AND fk_i_category_id = ' . $catId) ;
	    if(count($result)==0) {
		    $fields = array() ;
		    $fields['s_plugin_name'] = $plugin ;
		    $fields['fk_i_category_id'] = $catId ;
            $dao_pluginCategory->insert($fields) ;
            
            $subs = $dao_category->findSubcategories($catId);
            if(is_array($subs) && count($subs)>0) {
                $cats = array();
                foreach( $subs as $sub) {
        			$cats[] = $sub['pk_i_id'];
                }
                osc_addToCategoryPlugin($cats, $plugin) ;
            }
		}
	}
	unset($dao_pluginCategory) ;
	unset($dao_category) ;
}


function osc_addFilter($hook, $function, $priority = 5) {

	//$args = func_get_args();

	global $active_plugins;
	$hook = str_replace(APP_PATH . '/oc-content/plugins/', '', $hook);
	$found_plugin = false;
	if(isset($active_plugins[$hook])) {
		if(is_array($active_plugins[$hook])) {
			foreach($active_plugins[$hook] as $fxName) {
				if($fxName==$function) {
					$found_plugin = true;
					break;
				}
			}
		}
	}

	if(!$found_plugin) {
		$active_plugins[$hook][$priority][] = $function;//$_f;
	}
}

function osc_removeFilter($hook, $function) {

	global $active_plugins;
	unset($active_plugins[$hook][$function]);
}



osc_loadActivePlugins() ;

?>
