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

    define('ABS_PATH', dirname(dirname($_SERVER['SCRIPT_FILENAME'])) . '/');
    define('OC_ADMIN', true) ;

    require_once ABS_PATH . 'oc-load.php' ;

    
    
    function process_error_backtrace($errno, $errstr, $errfile, $errline, $errcontext) {
    if(!(error_reporting() & $errno))
        return;
    switch($errno) {
    case E_WARNING      :
    case E_USER_WARNING :
    case E_STRICT       :
    case E_NOTICE       :
    case E_USER_NOTICE  :
        $type = 'warning';
        $fatal = false;
        break;
    default             :
        $type = 'fatal error';
        $fatal = true;
        break;
    }
    $trace = array_reverse(debug_backtrace());
    array_pop($trace);
    if(php_sapi_name() == 'cli') {
        echo 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
        foreach($trace as $item)
            echo '  ' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()' . "\n";
    } else {
        echo '<p class="error_backtrace">' . "\n";
        echo '  Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ':' . "\n";
        echo '  <ol>' . "\n";
        foreach($trace as $item)
            echo '    <li>' . (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()</li>' . "\n";
        echo '  </ol>' . "\n";
        echo '</p>' . "\n";
    }
    if(ini_get('log_errors')) {
        $items = array();
        foreach($trace as $item)
            $items[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . ' calling ' . $item['function'] . '()';
        $message = 'Backtrace from ' . $type . ' \'' . $errstr . '\' at ' . $errfile . ' ' . $errline . ': ' . join(' | ', $items);
        error_log($message);
    }
    if($fatal)
        exit(1);
}

set_error_handler('process_error_backtrace', E_ALL);
    
    
    
    
    $adminThemes = new AdminThemes() ;
    $functions_path = $adminThemes->getCurrentThemePath() . 'functions.php';
    if( file_exists($functions_path) ) {
        require_once $functions_path ;
    }

    if( file_exists(ABS_PATH . '.maintenance') ) {
        define('__OSC_MAINTENANCE__', true);
    }

    switch( Params::getParam('page') )
    {
        case('items'):      require_once(osc_admin_base_path() . 'items.php') ;
                            $do = new CAdminItems() ;
                            $do->doModel() ;
        break;
        case('comments'):   require_once(osc_admin_base_path() . 'comments.php') ;
                            $do = new CAdminItemComments() ;
                            $do->doModel() ;
        break;
        case('media'):      require_once(osc_admin_base_path() . 'media.php') ;
                            $do = new CAdminMedia() ;
                            $do->doModel() ;
        break;
        case ('login'):     require_once(osc_admin_base_path() . 'login.php') ;
                            $do = new CAdminLogin() ;
                            $do->doModel() ;
        break;
        case('categories'): require_once(osc_admin_base_path() . 'categories.php') ;
                            $do = new CAdminCategories() ;
                            $do->doModel() ;
        break;
        case('emails'):     require_once(osc_admin_base_path() . 'emails.php') ;
                            $do = new CAdminEmails() ;
                            $do->doModel() ;
        break;
        case('pages'):      require_once(osc_admin_base_path() . 'pages.php') ;
                            $do = new CAdminPages() ;
                            $do->doModel() ;
        break;
        case('settings'):   require_once(osc_admin_base_path() . 'settings.php') ;
                            $do = new CAdminSettings() ;
                            $do->doModel();
        break;
        case('plugins'):    require_once(osc_admin_base_path() . 'plugins.php') ;
                            $do = new CAdminPlugins() ;
                            $do->doModel() ;
        break;
        case('languages'):  require_once(osc_admin_base_path() . 'languages.php') ;
                            $do = new CAdminLanguages() ;
                            $do->doModel() ;
        break;
        case('admins'):     require_once(osc_admin_base_path() . 'admins.php') ;
                            $do = new CAdminAdmins() ;
                            $do->doModel() ;
        break;
        case('users'):      require_once(osc_admin_base_path() . 'users.php') ;
                            $do = new CAdminUsers() ;
                            $do->doModel() ;
        break;
        case('ajax'):       require_once(osc_admin_base_path() . 'ajax/ajax.php') ;
                            $do = new CAdminAjax() ;
                            $do->doModel() ;
        break;
        case('appearance'): require_once(osc_admin_base_path() . 'appearance.php') ;
                            $do = new CAdminAppearance() ;
                            $do->doModel() ;
        break;
        case('tools'):      require_once(osc_admin_base_path() . 'tools.php') ;
                            $do = new CAdminTools() ;
                            $do->doModel() ;
        break;
        case('stats'):      require_once(osc_admin_base_path() . 'stats.php') ;
                            $do = new CAdminStats() ;
                            $do->doModel() ;
        break;
        case('cfields'):    require_once(osc_admin_base_path() . 'custom_fields.php') ;
                            $do = new CAdminCFields() ;
                            $do->doModel() ;
        break;
        case('upgrade'):    require_once(osc_admin_base_path() . 'upgrade.php') ;
                            $do = new CAdminUpgrade() ;
                            $do->doModel() ;
        break;
        case('market'):   require_once(osc_admin_base_path() . 'market.php') ;
                            $do = new CAdminMarket() ;
                            $do->doModel() ;
        break;
        default:            //login of oc-admin
                            require_once(osc_admin_base_path() . 'main.php') ;
                            $do = new CAdminMain() ;
                            $do->doModel() ;
    }

    /* file end: ./oc-admin/index.php */
?>