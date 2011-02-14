<?php

    function osc_run_hook($hook) {
        call_user_func_array("Plugins::runHook", func_get_args());
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
    
?>
