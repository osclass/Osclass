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
    require_once LIB_PATH . 'osclass/helpers/hErrors.php' ;
    
    if(!defined('AUTO_UPGRADE') && Params::getParam('skipdb')=='') {
        if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
            $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
            $conn = getConnection();
            $error_queries = $conn->osc_updateDB(str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql));
        }
        if(!$error_queries[0]) {
            $skip_db_link = osc_base_url() . "oc-includes/osclass/upgrade-funcs.php?skipdb=true";
            $title = __('OSClass &raquo; Has some errors') ;
            $message = __('We encountered some problems updating the database structure. The following queries failed:');
            $message .= "<br/><br/>" . implode("<br>", $error_queries[2]);
            $message .= "<br/><br/>" . sprintf(__('These errors could be false-positive errors. If you\'re sure that is the case, you could <a href="%s">continue with the upgrade</a>, or <a href="http://forums.osclass.org/">ask in our forums</a>.'), $skip_db_link);
            osc_die($title, $message) ;
        }
    }

    $version = osc_version() ;
    Preference::newInstance()->update(array('s_value' => time()), array( 's_section' => 'osclass', 's_name' => 'last_version_check'));

    $conn = getConnection();

    if($version<202) {
        $conn->osc_dbExec(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'save_latest_searches', '1', 'BOOLEAN'),('osclass', 'purge_latest_searches', '1000', 'STRING')", DB_TABLE_PREFIX));
        osc_changeVersionTo(202) ;
    }
    

    // UNCOMMENT THEESE LINES IF YOU'RE A DEVELOPER
    //if(file_exists(osc_lib_path() . 'osclass/installer/struct.sql')) {
    //    $sql = file_get_contents(osc_lib_path() . 'osclass/installer/struct.sql');
    //    $conn = getConnection();
    //    $error_queries = $conn->osc_updateDB(str_replace('/*TABLE_PREFIX*/', DB_TABLE_PREFIX, $sql));
    //}
    //print_r($error_queries[0]);

    if($version<203) {
        $conn->osc_dbExec(sprintf("INSERT INTO %st_preference VALUES ('osclass', 'moderate_items', '0', 'INTEGER'),('osclass', 'items_wait_time', '90', 'INTEGER')", DB_TABLE_PREFIX));
        $users = User::newInstance()->listAll();
        foreach($users as $user) {
            $comments = count(ItemComment::newInstance()->findByAuthorID($user['pk_i_id']));
            $items = count(Item::newInstance()->findByUserIDEnabled($user['pk_i_id']));
            User::newInstance()->update(array( 'i_items' => $items, 'i_comments' => $comments )
                                        ,array( 'pk_i_id' => $user['pk_i_id'] )
                                        ) ;
        }
        osc_changeVersionTo(203) ;
    }


    // UNCOMMENT THEESE LINES IF YOU'RE A DEVELOPER
    //osc_changeVersionTo(202) ;

    
    if(Params::getParam('action') == '') {

        $title = 'OSClass &raquo; Updated correctly' ;
        $message = 'OSClass has been updated successfully. <a href="http://forums.osclass.org/">Need more help?</a>';

        osc_die($title, $message) ;
    }

?>
