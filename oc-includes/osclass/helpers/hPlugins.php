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

    function osc_run_hook($hook) {
        $args = func_get_args();
        call_user_func_array("Plugins::runHook", $args);
    }
    
    function osc_apply_filter($hook, $content) {
        return Plugins::applyFilter($hook, $content);
    }
    
    function osc_add_hook($hook, $function, $priority = 5) {
        Plugins::addHook($hook, $function, $priority);
    }

    function osc_add_filter($hook, $function, $priority = 5) {
        Plugins::addHook($hook, $function, $priority);
    }
    
    function osc_remove_hook($hook, $function) {
        Plugins::removeHook($hook, $function);
    }

    function osc_remove_filter($hook, $function) {
        Plugins::removeHook($hook, $function);
    }
    
    function osc_is_this_category($name, $id) {
        return Plugins::isThisCategory($name, $id);
    }
    
    function osc_plugin_get_info($plugin) {
        return Plugins::getInfo($plugin);
    }
    
    function osc_plugin_check_update($plugin) {
        return Plugins::checkUpdate($plugin);
    }
    
    function osc_register_plugin($path, $function) {
        Plugins::register($path, $function);
    }

    function osc_get_plugins() {
        return Plugins::getActive();
    }
    
    function osc_plugin_is_installed($plugin) {
        return Plugins::isInstalled($plugin);
    }
    
    function osc_plugin_configure_view($plugin) {
        return Plugins::configureView($plugin);
    }
    
    function osc_plugin_resource($file) {
        return Plugins::resource($file);
    }
    
    function osc_plugin_configure_url($plugin) {
        return osc_admin_base_url(true).'?page=plugins&action=configure&plugin='.$plugin;
    }
    
    
    function osc_ajax_plugin_url($file = '') {
        $file = str_replace(osc_plugins_path(), '', $file);
        return (osc_base_url(true). "?page=ajax&action=custom&ajaxfile=" . $file);
    }

    function osc_admin_configure_plugin_url($file = '') {
        $file = str_replace(osc_plugins_path(), '', $file);
        return osc_admin_base_url(true).'?page=plugins&action=configure&plugin=' . $file;
    }

    function osc_admin_render_plugin_url($file = '') {
        $file = str_replace(osc_plugins_path(), '', $file);
        return osc_admin_base_url(true).'?page=plugins&action=renderplugin&file=' . $file;
    }

    function osc_admin_render_plugin($file = '') {
        header('Location: ' . osc_admin_render_plugin_url($file) ) ;
        exit;
    }


    
?>
