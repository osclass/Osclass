<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    if(!defined('ABS_PATH')) {
        define('ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/');
    }

    require_once ABS_PATH . 'oc-load.php';

    $version = osc_version() ;
    Preference::newInstance()->update(array('s_value' => time()), array( 's_section' => 'osclass', 's_name' => 'last_version_check'));
    osc_changeVersionTo(201) ;

    if(Params::getParam('action') == '') {
        require_once LIB_PATH . 'osclass/helpers/hErrors.php' ;

        $title = 'OSClass &raquo; Updated correctly' ;
        $message = 'OSClass has been updated successfully. <a href="http://forums.osclass.org/">Need more help?</a>';

        osc_die($title, $message) ;
    }

?>