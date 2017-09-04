<?php
/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


require_once dirname(dirname(__FILE__)) . '/htmlpurifier/HTMLPurifier.auto.php';
function _purify($value, $xss_check)
{
    if( !$xss_check ) {
        return $value;
    }

    $_config = HTMLPurifier_Config::createDefault();
    $_config->set('HTML.Allowed', '');
    $_config->set('Cache.SerializerPath', dirname(dirname(dirname(dirname(__FILE__)))) . '/oc-content/uploads/');

    $_purifier = new HTMLPurifier($_config);


    if( is_array($value) ) {
        foreach($value as $k => &$v) {
            $v = _purify($v, $xss_check); // recursive
        }
    } else {
        $value = $_purifier->purify($value);
    }

    return $value;
}
function getServerParam($param, $htmlencode = false, $xss_check = true, $quotes_encode = true)
{
    if ($param == "") return '';
    if (!isset($_SERVER[$param])) return '';
    $value = _purify($_SERVER[$param], $xss_check);
    if ($htmlencode) {
        if($quotes_encode) {
            return htmlspecialchars(stripslashes($value), ENT_QUOTES);
        } else {
            return htmlspecialchars(stripslashes($value), ENT_NOQUOTES);
        }
    }

    if(get_magic_quotes_gpc()) {
        $value = strip_slashes_extended($value);
    }

    return ($value);
}

/*
 * The url of the site
 *
 * @since 1.2
 *
 * @return string The url of the site
 */
function get_absolute_url( ) {
    $protocol = ( getServerParam('HTTPS') == 'on' || getServerParam('HTTP_X_FORWARDED_PROTO')=='https') ? 'https' : 'http';
    $pos      = strpos(getServerParam('REQUEST_URI'), 'oc-includes');
    $URI      = rtrim( substr( getServerParam('REQUEST_URI'), 0, $pos ), '/' ) . '/';
    return $protocol . '://' . getServerParam('HTTP_HOST') . $URI;
}

/*
 * The relative url on the domain url
 *
 * @since 1.2
 *
 * @return string The relative url on the domain url
 */
function get_relative_url( ) {
    $url = Params::getServerParam('REQUEST_URI', false, false);
    return substr($url, 0, strpos($url, '/oc-includes')) . "/";
}

/*
 * Get the requirements to install Osclass
 *
 * @since 1.2
 *
 * @return array Requirements
 */
function get_requirements( ) {
    $array = array(
        'PHP version >= 5.6.x' => array(
            'requirement' => __('PHP version >= 5.6.x'),
            'fn' => version_compare(PHP_VERSION, '5.6.0', '>='),
            'solution' => __('At least PHP5.6 (PHP 7.0 or higher recommended) is required to run Osclass. You may talk with your hosting to upgrade your PHP version.')),

        'MySQLi extension for PHP' => array(
            'requirement' => __('MySQLi extension for PHP'),
            'fn' => extension_loaded('mysqli'),
            'solution' => __('MySQLi extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/mysqli.setup.php">install/configure</a>.')),

        'GD extension for PHP' => array(
            'requirement' => __('GD extension for PHP'),
            'fn' => extension_loaded('gd'),
            'solution' => __('GD extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/image.setup.php">install/configure</a>.')),

        'Folder <code>oc-content/uploads</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/uploads</code> exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/uploads/' ),
            'solution' => sprintf(__('You have to create <code>uploads</code> folder, i.e.: <code>mkdir %soc-content/uploads/</code>' ), ABS_PATH)),

        'Folder <code>oc-content/uploads</code> is writable' => array(
            'requirement' => __('<code>oc-content/uploads</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/uploads/' ),
            'solution' => sprintf(__('<code>uploads</code> folder has to be writable, i.e.: <code>chmod a+w %soc-content/uploads/</code>'), ABS_PATH)),
        // oc-content/downlods
        'Folder <code>oc-content/downloads</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/downloads</code> exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/downloads/' ),
            'solution' => sprintf(__('You have to create <code>downloads</code> folder, i.e.: <code>mkdir %soc-content/downloads/</code>' ), ABS_PATH)),

        'Folder <code>oc-content/downloads</code> is writable' => array(
            'requirement' => __('<code>oc-content/downloads</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/downloads/' ),
            'solution' => sprintf(__('<code>downloads</code> folder has to be writable, i.e.: <code>chmod a+w %soc-content/downloads/</code>'), ABS_PATH)),
        // oc-content/languages
        'Folder <code>oc-content/languages</code> exists' => array(
            'requirement' => __('Folder <code>oc-content/languages</code> folder exists'),
            'fn' => file_exists( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('You have to create the <code>languages</code> folder, i.e.: <code>mkdir %soc-content/languages/</code>'), ABS_PATH)),

        'Folder <code>oc-content/languages</code> is writable' => array(
            'requirement' => __('<code>oc-content/languages</code> folder is writable'),
            'fn' => is_writable( ABS_PATH . 'oc-content/languages/' ),
            'solution' => sprintf(__('<code>languages</code> folder has to be writable, i.e.: <code>chmod a+w %soc-content/languages/</code>'), ABS_PATH)),
    );

    $config_writable = false;
    $root_writable = false;
    $config_sample = false;
    if( file_exists(ABS_PATH . 'config.php') ) {
        if( is_writable(ABS_PATH . 'config.php') ) {
            $config_writable = true;
        }
        $array['File <code>config.php</code> is writable'] = array(
            'requirement' => __('<code>config.php</code> file is writable'),
            'fn' => $config_writable,
            'solution' => sprintf(__('<code>config.php</code> file has to be writable, i.e.: <code>chmod a+w %sconfig.php</code>'), ABS_PATH));
    } else {
        if (is_writable(ABS_PATH) ) {
            $root_writable = true;
        }
        $array['Root directory is writable'] = array(
            'requirement' => __('Root directory is writable'),
            'fn' => $root_writable,
            'solution' => sprintf(__('Root folder has to be writable, i.e.: <code>chmod a+w %s</code>'), ABS_PATH));

        if( file_exists(ABS_PATH . 'config-sample.php') ) {
            $config_sample = true;
        }
        $array['File <code>config-sample.php</code> exists'] = array(
            'requirement' => __('<code>config-sample.php</code> file exists'),
            'fn' => $config_sample,
            'solution' => __('<code>config-sample.php</code> file is required, you should re-download Osclass.'));
    }

    return $array;
}


/**
 * Check if some of the requirements to install Osclass are correct or not
 *
 * @since 1.2
 *
 * @return boolean Check if all the requirements are correct
 */
function check_requirements($array) {
    foreach($array as $k => $v) {
        if( !$v['fn'] ) return true;
    }
    return false;
}

/**
 * Check if allowed to send stats to Osclass
 *
 * @return boolean Check if allowed to send stats to Osclass
 */
function reportToOsclass() {
    return $_COOKIE['osclass_save_stats'];
}

/**
 * insert/update preference allow_report_osclass
 * @param boolean $bool
 */
function set_allow_report_osclass($value) {
    $values = array(
        's_section' => 'osclass',
        's_name'    => 'allow_report_osclass',
        's_value'   => $value,
        'e_type'    => 'BOOLEAN'
    );

    Preference::newInstance()->insert($values);
}

/*
 * Install Osclass database
 *
 * @since 1.2
 *
 * @return mixed Error messages of the installation
 */
function oc_install( ) {
    $dbhost      = Params::getParam('dbhost');
    $dbname      = Params::getParam('dbname');
    $username    = Params::getParam('username');
    $password    = Params::getParam('password', false, false);
    $tableprefix = Params::getParam('tableprefix');
    $createdb    = false;
    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';

    if( $tableprefix == '' ) {
        $tableprefix = 'oc_';
    }

    if( Params::getParam('createdb') != '' ) {
        $createdb = true;
    }

    if ( $createdb ) {
        $adminuser = Params::getParam('admin_username');
        $adminpwd  = Params::getParam('admin_password', false, false);

        $master_conn = new DBConnectionClass($dbhost, $adminuser, $adminpwd, '');
        $error_num   = $master_conn->getErrorConnectionLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s') , $error_num ), __FILE__."::".__LINE__);
            }

            switch ($error_num) {
                case 1049:  return array('error' => __("The database doesn't exist. You should check the \"Create DB\" checkbox and fill in a username and password with the right privileges"));
                    break;
                case 1045:  return array('error' => __('Cannot connect to the database. Check if the user has privileges.'));
                    break;
                case 1044:  return array('error' => __('Cannot connect to the database. Check if the username and password are correct.'));
                    break;
                case 2005:  return array('error' => __("Can't resolve MySQL host. Check if the host is correct."));
                    break;
                default:    return array('error' => sprintf(__('Cannot connect to the database. Error number: %s')), $error_num);
                break;
            }
        }

        $m_db = $master_conn->getOsclassDb();
        $comm = new DBCommandClass( $m_db );
        $comm->query( sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname) );

        $error_num = $comm->getErrorLevel();

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__("Can't create the database. Error number: %s"), $error_num) , __FILE__."::".__LINE__);
            }

            if( in_array( $error_num, array(1006, 1044, 1045) ) ) {
                return array('error' => __("Can't create the database. Check if the admin username and password are correct."));
            }

            return array('error' => sprintf(__("Can't create the database. Error number: %s"),  $error_num));
        }

        unset($conn);
        unset($comm);
        unset($master_conn);
    }

    $conn      = new DBConnectionClass($dbhost, $username, $password, $dbname);
    $error_num = $conn->getErrorConnectionLevel();

    if( $error_num == 0 ) {
        $error_num = $conn->getErrorLevel();
    }

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__('Cannot connect to the database. Error number: %s'), $error_num) , __FILE__."::".__LINE__);
        }

        switch( $error_num ) {
            case 1049:  return array('error' => __("The database doesn't exist. You should check the \"Create DB\" checkbox and fill in a username and password with the right privileges"));
                break;
            case 1045:  return array('error' => __('Cannot connect to the database. Check if the user has privileges.'));
                break;
            case 1044:  return array('error' => __('Cannot connect to the database. Check if the username and password are correct.'));
                break;
            case 2005:  return array('error' => __("Can't resolve MySQL host. Check if the host is correct."));
                break;
            default:    return array('error' => sprintf(__('Cannot connect to the database. Error number: %s'), $error_num));
            break;
        }
    }

    if( file_exists(ABS_PATH . 'config.php') ) {
        if( !is_writable(ABS_PATH . 'config.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("Can't write in config.php file. Check if the file is writable.") , __FILE__."::".__LINE__);
            }
            return array('error' => __("Can't write in config.php file. Check if the file is writable."));
        }
        create_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    } else {
        if( !file_exists(ABS_PATH . 'config-sample.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__("config-sample.php doesn't exist. Check if everything is decompressed correctly.") , __FILE__."::".__LINE__);
            }

            return array('error' => __("config-sample.php doesn't exist. Check if everything is decompressed correctly."));
        }
        if( !is_writable(ABS_PATH) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(__('Can\'t copy config-sample.php. Check if the root directory is writable.') , __FILE__."::".__LINE__);
            }

            return array('error' => __('Can\'t copy config-sample.php. Check if the root directory is writable.'));
        }
        copy_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    }

    require_once ABS_PATH . 'config.php';

    $sql = file_get_contents( ABS_PATH . 'oc-includes/osclass/installer/struct.sql' );

    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Can't create the database structure. Error number: %s"), $error_num)  , __FILE__."::".__LINE__);
        }

        switch ($error_num) {
            case 1050:  return array('error' => __('There are tables with the same name in the database. Change the table prefix or the database and try again.'));
                break;
            default:    return array('error' => sprintf(__("Can't create the database structure. Error number: %s"), $error_num));
            break;
        }
    }

    require_once LIB_PATH . 'osclass/model/OSCLocale.php';
    $localeManager = OSCLocale::newInstance();

    $locales = osc_listLocales();
    $values = array(
        'pk_c_code'         => $locales[osc_current_admin_locale()]['code'],
        's_name'            => $locales[osc_current_admin_locale()]['name'],
        's_short_name'      => $locales[osc_current_admin_locale()]['short_name'],
        's_description'     => $locales[osc_current_admin_locale()]['description'],
        's_version'         => $locales[osc_current_admin_locale()]['version'],
        's_author_name'     => $locales[osc_current_admin_locale()]['author_name'],
        's_author_url'      => $locales[osc_current_admin_locale()]['author_url'],
        's_currency_format' => $locales[osc_current_admin_locale()]['currency_format'],
        's_date_format'     => $locales[osc_current_admin_locale()]['date_format'],
        'b_enabled'         => 1,
        'b_enabled_bo'      => 1
    );

    if( isset($locales[osc_current_admin_locale()]['stop_words']) ) {
        $values['s_stop_words'] = $locales[osc_current_admin_locale()]['stop_words'];
    }
    $localeManager->insert($values);


    $required_files = array(
        ABS_PATH . 'oc-includes/osclass/installer/basic_data.sql',
        ABS_PATH . 'oc-includes/osclass/installer/pages.sql',
        ABS_PATH . 'oc-content/languages/' . osc_current_admin_locale() . '/mail.sql',
    );

    $sql = '';
    foreach($required_files as $file) {
        if ( !file_exists($file) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error(sprintf(__('The file %s doesn\'t exist'), $file) , __FILE__."::".__LINE__);
            }

            return array('error' => sprintf(__('The file %s doesn\'t exist'), $file) );
        } else {
            $sql .= file_get_contents($file);
        }
    }
    $comm->importSQL($sql);

    $error_num = $comm->getErrorLevel();

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error(sprintf(__("Can't insert basic configuration. Error number: %s"), $error_num)  , __FILE__."::".__LINE__);
        }

        switch ($error_num) {
            case 1471:  return array('error' => __("Can't insert basic configuration. This user has no privileges to 'INSERT' into the database."));
                break;
            default:    return array('error' => sprintf(__("Can't insert basic configuration. Error number: %s"), $error_num));
            break;
        }
    }

    osc_set_preference('language', osc_current_admin_locale());
    osc_set_preference('admin_language', osc_current_admin_locale());
    osc_set_preference('csrf_name', 'CSRF'.mt_rand(0,mt_getrandmax()));

    oc_install_example_data();

    if( reportToOsclass() ) {
        set_allow_report_osclass( true );
    } else {
        set_allow_report_osclass( false );
    }

    return false;
}

/*
 * Insert the example data (categories and emails) on all available locales
 *
 * @since 2.4
 *
 * @return mixed Error messages of the installation
 */
function oc_install_example_data() {
    require_once LIB_PATH . 'osclass/formatting.php';
    require LIB_PATH . 'osclass/installer/basic_data.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    $mCat = Category::newInstance();

    if(!function_exists('osc_apply_filter')) {
        function osc_apply_filter($dummyfilter, $str) {
            return $str;
        }
    }


    foreach($categories as $category) {

        $fields['pk_i_id']              = $category['pk_i_id'];
        $fields['fk_i_parent_id']       = $category['fk_i_parent_id'];
        $fields['i_position']           = $category['i_position'];
        $fields['i_expiration_days']    = 0;
        $fields['b_enabled']            = 1;

        $aFieldsDescription[osc_current_admin_locale()]['s_name'] = $category['s_name'];

        $mCat->insert($fields, $aFieldsDescription);
    }

    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/model/ItemComment.php';
    require_once LIB_PATH . 'osclass/model/ItemLocation.php';
    require_once LIB_PATH . 'osclass/model/ItemResource.php';
    require_once LIB_PATH . 'osclass/model/ItemStats.php';
    require_once LIB_PATH . 'osclass/model/User.php';
    require_once LIB_PATH . 'osclass/model/Country.php';
    require_once LIB_PATH . 'osclass/model/Region.php';
    require_once LIB_PATH . 'osclass/model/City.php';
    require_once LIB_PATH . 'osclass/model/CityArea.php';
    require_once LIB_PATH . 'osclass/model/Field.php';
    require_once LIB_PATH . 'osclass/model/Page.php';
    require_once LIB_PATH . 'osclass/model/Log.php';

    require_once LIB_PATH . 'osclass/model/CategoryStats.php';
    require_once LIB_PATH . 'osclass/model/CountryStats.php';
    require_once LIB_PATH . 'osclass/model/RegionStats.php';
    require_once LIB_PATH . 'osclass/model/CityStats.php';

    require_once LIB_PATH . 'osclass/helpers/hSecurity.php';
    require_once LIB_PATH . 'osclass/helpers/hValidate.php';
    require_once LIB_PATH . 'osclass/helpers/hUsers.php';
    require_once LIB_PATH . 'osclass/ItemActions.php';

    $mItem = new ItemActions(true);

    foreach($item as $k => $v) {
        if($k=='description' || $k=='title') {
            Params::setParam($k, array(osc_current_admin_locale() => $v));
        } else {
            Params::setParam($k, $v);
        }
    }

    $mItem->prepareData(true);
    $successItem = $mItem->add();

    $successPageresult = Page::newInstance()->insert(
        array(
            's_internal_name' => $page['s_internal_name'],
            'b_indelible' => 0,
            's_meta' => json_encode('')
        ),
        array(
            osc_current_admin_locale() => array(
                's_title' => $page['s_title'],
                's_text' => $page['s_text']
            )
        ));

}


/*
 * Create config file from scratch
 *
 * @since 1.2
 *
 * @param string $dbname Database name
 * @param string $username User of the database
 * @param string $password Password for user of the database
 * @param string $dbhost Database host
 * @param string $tableprefix Prefix for table names
 * @return mixed Error messages of the installation
 */
function create_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_text = <<<CONFIG
<?php
/**
 * The base MySQL settings of Osclass
 */
define('MULTISITE', 0);

/** MySQL database name for Osclass */
define('DB_NAME', '$dbname');

/** MySQL database username */
define('DB_USER', '$username');

/** MySQL database password */
define('DB_PASSWORD', '$password');

/** MySQL hostname */
define('DB_HOST', '$dbhost');

/** Database Table prefix */
define('DB_TABLE_PREFIX', '$tableprefix');

define('REL_WEB_URL', '$rel_url');

define('WEB_PATH', '$abs_url');

CONFIG;

    file_put_contents(ABS_PATH . 'config.php', $config_text);
}

/*
 * Create config from config-sample.php file
 *
 * @since 1.2
 */
function copy_config_file($dbname, $username, $password, $dbhost, $tableprefix) {
    $password = addslashes($password);
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_sample = file(ABS_PATH . 'config-sample.php');

    foreach ($config_sample as $line_num => $line) {
        switch (substr($line, 0, 16)) {
            case "define('DB_NAME'":
                $config_sample[$line_num] = str_replace("database_name", $dbname, $line);
                break;
            case "define('DB_USER'":
                $config_sample[$line_num] = str_replace("'username'", "'$username'", $line);
                break;
            case "define('DB_PASSW":
                $config_sample[$line_num] = str_replace("'password'", "'$password'", $line);
                break;
            case "define('DB_HOST'":
                $config_sample[$line_num] = str_replace("localhost", $dbhost, $line);
                break;
            case "define('DB_TABLE":
                $config_sample[$line_num] = str_replace('oc_', $tableprefix, $line);
                break;
            case "define('REL_WEB_":
                $config_sample[$line_num] = str_replace('rel_here', $rel_url, $line);
                break;
            case "define('WEB_PATH":
                $config_sample[$line_num] = str_replace('http://localhost', $abs_url, $line);
                break;
        }
    }

    $handle = fopen(ABS_PATH . 'config.php', 'w');
    foreach( $config_sample as $line ) {
        fwrite($handle, $line);
    }
    fclose($handle);
    chmod(ABS_PATH . 'config.php', 0666);
}


function is_osclass_installed( ) {
    if( !file_exists( ABS_PATH . 'config.php' ) ) {
        return false;
    }

    require_once ABS_PATH . 'config.php';

    $conn = new DBConnectionClass( osc_db_host(), osc_db_user(), osc_db_password(), osc_db_name() );
    $c_db = $conn->getOsclassDb();
    $comm = new DBCommandClass( $c_db );
    $rs   = $comm->query( sprintf( "SELECT * FROM %st_preference WHERE s_name = 'osclass_installed'", DB_TABLE_PREFIX ) );

    if( $rs == false ) {
        return false;
    }

    if( $rs->numRows() != 1 ) {
        return false;
    }

    return true;
}

function finish_installation( $password ) {
    require_once LIB_PATH . 'osclass/model/Admin.php';
    require_once LIB_PATH . 'osclass/model/Category.php';
    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
    require_once LIB_PATH . 'osclass/compatibility.php';
    require_once LIB_PATH . 'osclass/classes/Plugins.php';

    $data = array();

    $mAdmin = new Admin();

    $mPreference = Preference::newInstance();
    $mPreference->insert (
        array(
            's_section' => 'osclass'
        ,'s_name' => 'osclass_installed'
        ,'s_value' => '1'
        ,'e_type' => 'BOOLEAN'
        )
    );

    $admin = $mAdmin->findByPrimaryKey(1);

    $data['s_email'] = $admin['s_email'];
    $data['admin_user'] = $admin['s_username'];
    $data['password'] = $password;

    return $data;
}

/* Menus */
function display_database_config() {
    ?>
    <form action="install.php" method="post">
        <input type="hidden" name="step" value="3" />
        <h2 class="target"><?php _e('Database information'); ?></h2>
        <div class="form-table">
            <table>
                <tbody>
                <tr>
                    <th align="left"><label for="dbhost"><?php _e('Host'); ?></label></th>
                    <td><input type="text" id="dbhost" name="dbhost" value="localhost" size="25" /></td>
                    <td class="small"><?php _e('Server name or IP where the database engine resides'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="dbname"><?php _e('Database name'); ?></label></th>
                    <td><input type="text" id="dbname" name="dbname" value="osclass" size="25" /></td>
                    <td class="small"><?php _e('The name of the database you want to run Osclass in'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="username"><?php _e('User Name'); ?></label></th>
                    <td><input type="text" id="username" name="username" size="25" /></td>
                    <td class="small"><?php _e('Your MySQL username'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="password"><?php _e('Password'); ?></label></th>
                    <td><input type="password" id="password" name="password" value="" size="25" autocomplete="off" /></td>
                    <td class="small"><?php _e('Your MySQL password'); ?></td>
                </tr>
                <tr>
                    <th align="left"><label for="tableprefix"><?php _e('Table prefix'); ?></label></th>
                    <td><input type="text" id="tableprefix" name="tableprefix" value="oc_" size="25" /></td>
                    <td class="small"><?php _e('If you want to run multiple Osclass installations in a single database, change this'); ?></td>
                </tr>
                </tbody>
            </table>
            <div id="advanced_install" class="shrink">
                <div class="text">
                    <span><?php _e('Advanced'); ?></span>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('#advanced_install').click(function() {
                        $('#more-options').toggle();
                        if( $('#advanced_install').attr('class') == 'shrink' ) {
                            $('#advanced_install').removeClass('shrink');
                            $('#advanced_install').addClass('expanded');
                        } else {
                            $('#advanced_install').addClass('shrink');
                            $('#advanced_install').removeClass('expanded');
                        }
                    });
                    $('#createdb').on('click', function() {
                        if($("#createdb").is(':checked')) {
                            if ($("#admin_username").attr("value") == '') {
                                $("#admin_username").attr("value", $("#username").attr("value"));
                            };
                            if ($("#admin_password").attr("value") == '') {
                                $("#admin_password").attr("value", $("#password").attr("value"));
                                $("#password_copied").show();
                            };
                        } else {
                            $("#password_copied").hide();
                        };
                    });
                    $("#password_copied").hide();
                });
            </script>
            <div style="clear:both;"></div>
            <table id="more-options" style="display:none;">
                <tbody>
                <tr>
                    <th></th>
                    <td><input type="checkbox" id="createdb" name="createdb" onclick="db_admin();" value="1" /><label for="createdb"><?php _e('Create DB'); ?></label></td>
                    <td class="small"><?php _e('Check here if the database is not created and you want to create it now'); ?></td>
                </tr>
                <tr id="admin_username_row">
                    <th align="left"><label for="admin_username"><?php _e('DB admin username'); ?></label></th>
                    <td><input type="text" id="admin_username" name="admin_username" size="25" disabled="disabled" /></td>
                    <td></td>
                </tr>
                <tr id="admin_password_row">
                    <th align="left"><label for="admin_password"><?php _e('DB admin password'); ?></label></th>
                    <td><input type="password" id="admin_password" name="admin_password" value="" size="25" disabled="disabled" autocomplete="off" /> <span id="password_copied"><?php _e('Password copied from above'); ?></span></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="clear"></div>
        <p class="margin20">
            <input type="submit" class="button" name="submit" value="Next" />
            <a style="float:right;" href="https://osclass.org/page/hosting"><?php _e('Discover the best hosting providers for Osclass'); ?></a>
        </p>
        <div class="clear"></div>
    </form>
<?php
}

function display_target() {
    $country_list = osc_file_get_contents('https://geo.osclass.org/newgeo.services.php?action=countries');
    $country_list = json_decode(substr($country_list, 1, strlen($country_list)-2), true);

    $region_list = array();

    $country_ip = '';
    if(preg_match('|([a-z]{2})-([A-Z]{2})|', Params::getServerParam('HTTP_ACCEPT_LANGUAGE'), $match)) {
        $country_ip = $match[2];
        $region_list = osc_file_get_contents('https://geo.osclass.org/newgeo.services.php?action=regions&country='.$match[2]);
        $region_list = json_decode(substr($region_list, 1, strlen($region_list)-2), true);
    }

    if(!isset($country_list[0]) || !isset($country_list[0]['s_name'])) {
        $internet_error = true;
    }


    ?>
    <form id="target_form" name="target_form" action="#" method="post" onsubmit="return false;">
        <h2 class="target"><?php _e('Information needed'); ?></h2>
        <div class="form-table">
            <h2 class="title"><?php _e('Admin user'); ?></h2>
            <table class="admin-user">
                <tbody>
                <tr>
                    <th><label for="admin_user"><?php _e('Username'); ?></label></th>
                    <td>
                        <input size="25" id="admin_user" name="s_name" type="text" value="admin" />
                    </td>
                    <td>
                        <span id="admin-user-error" class="error" aria-hidden="true" style="display:none;"><?php _e('Admin user is required'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="s_passwd"><?php _e('Password'); ?></label></th>
                    <td>
                        <input size="25" class="password_test" name="s_passwd" id="s_passwd" type="password" value="" autocomplete="off" />
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <div class="admin-user">
                <?php _e('A password will be automatically generated for you if you leave this blank.'); ?>
                <img src="<?php echo get_absolute_url() ?>oc-includes/images/question.png" class="question-skip vtip" title="<?php echo osc_esc_html(__('You can modify username and password if you like, just change the input value.')); ?>" alt="" />
            </div>
            <h2 class="title"><?php _e('Contact information'); ?></h2>
            <table class="contact-info">
                <tbody>
                <tr>
                    <th><label for="webtitle"><?php _e('Web title'); ?></label></th>
                    <td><input type="text" id="webtitle" name="webtitle" size="25" /></td>
                    <td></td>
                </tr>
                <tr>
                    <th><label for="email"><?php _e('Contact e-mail'); ?></label></th>
                    <td><input type="text" id="email" name="email" size="25" /></td>
                    <td><span id="email-error" class="error" style="display:none;"><?php _e('Put your e-mail here'); ?></span></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="checkbox" checked="checked" id="createmarketaccount" name="createmarketaccount" value="1" /><label for="createmarketaccount"><?php _e('Create market.osclass.org account'); ?>
                            <br><?php _e("(You agree to our <a href=\"https://osclass.org/page/legal-note\">Terms & Conditions</a>)"); ?></label>
                        <img class="vtip" src="<?php echo get_absolute_url(); ?>oc-includes/images/question.png" title="<?php echo osc_esc_html(__("Create a market.osclass.org account and download free themes and plugins.")); ?>" alt="" />
                    </td>
                </tr>
                </tbody>
            </table>
            <h2 class="title"><?php _e('Location'); ?></h2>
            <p class="space-left-25 left no-bottom"><?php _e('Choose countries/cities where your target users are located'); ?></p>
            <div id="location-question" class="left question">
                <img class="vtip" src="<?php echo get_absolute_url(); ?>oc-includes/images/question.png" title="<?php echo osc_esc_html(__("Once you type a country, you'll be able to choose region and city as well. Therefore, the installation will be more specific.")); ?>" alt="" />
            </div>
            <div class="clear"></div>
            <div id="location">
                <?php if(!$internet_error) { ?>
                    <input type="hidden" id="skip-location-input" name="skip-location-input" value="0" />
                    <input type="hidden" id="country-input" name="country-input" value="" />
                    <input type="hidden" id="region-input" name="region-input" value="" />
                    <input type="hidden" id="city-input" name="city-input" value="" />
                    <div id="country-box">

                        <select name="country_select" id="country_select" >
                            <option value="skip"><?php _e("Skip location"); ?></option>
                            <!-- <option value="all"><?php _e("International"); ?></option> -->
                            <?php foreach($country_list as $c) { ?>
                                <option value="<?php echo $c['code']; ?>" <?php if($c['code']==$country_ip) { echo 'selected="selected"'; }; ?>><?php echo $c['s_name']; ?></option>
                            <?php }; ?>
                        </select>

                        <select name="region_select" id="region_select" style="display: none;">
                            <option value="all"><?php _e("All regions"); ?></option>
                        </select>

                        <select name="city_select" id="city_select" style="display: none;">
                            <option value="all"><?php _e("All cities"); ?></option>
                        </select>

                        <div id="no_region_text" aria-hidden="true" style="display: none;"><?php _e("There are no regions available for this country"); ?></div>

                        <div id="no_city_text" aria-hidden="true" style="display: none;"><?php _e("There are no cities available for this region"); ?></div>


                    </div>
                <?php } else { ?>
                    <div id="location-error">
                        <?php _e('No internet connection. You can continue the installation and insert countries later.'); ?>
                        <input type="hidden" id="skip-location-input" name="skip-location-input" value="1" />
                    </div>
                <?php }; ?>
            </div>
        </div>
        <div class="clear"></div>
        <p class="margin20">
            <a href="#" class="button" onclick="validate_form();">Next</a>
        </p>
        <div class="clear"></div>
    </form>
    <div id="lightbox" style="display:none;">
        <div class="center">
            <img src="<?php echo get_absolute_url(); ?>oc-includes/images/loading.gif" alt="<?php echo osc_esc_html(__("Loading...")); ?>" title="" />
        </div>
    </div>
<?php
}

function display_database_error($error ,$step) {
    ?>
    <h2 class="target"><?php _e('Error'); ?></h2>
    <p class="bottom space-left-10">
        <?php echo $error['error']?>
    </p>
    <a href="<?php echo get_absolute_url(); ?>oc-includes/osclass/install.php?step=<?php echo $step; ?>" class="button"><?php _e('Go back'); ?></a>
    <div class="clear bottom"></div>
<?php
}

function ping_search_engines($bool){
    $mPreference = Preference::newInstance();
    if($bool == 1){
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '1'
            ,'e_type'   => 'BOOLEAN'
            )
        );
        // GOOGLE
        osc_doRequest( 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.urlencode(osc_search_url(array('sFeed' => 'rss') )), array());
        // BING
        osc_doRequest( 'http://www.bing.com/webmaster/ping.aspx?siteMap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
        // YAHOO!
        osc_doRequest( 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='.urlencode( osc_search_url(array('sFeed' => 'rss') ) ), array());
    } else {
        $mPreference->insert (
            array(
                's_section' => 'osclass'
            ,'s_name'   => 'ping_search_engines'
            ,'s_value'  => '0'
            ,'e_type'   => 'BOOLEAN'
            )
        );
    }
}
function display_finish($password) {
    $data = finish_installation( $password );
    ?>
    <?php if(Params::getParam('error_location') == 1) { ?>
        <script type="text/javascript">
            setTimeout (function(){
                $('.error-location').fadeOut('slow');
            }, 2500);
        </script>
        <div class="error-location">
            <?php _e('The selected location could not been installed'); ?>
        </div>
    <?php } ?>
    <h2 class="target"><?php _e('Congratulations!');?></h2>
    <p class="space-left-10"><?php _e("Osclass has been installed. Were you expecting more steps? Sorry to disappoint you!");?></p>
    <p class="space-left-10"><?php echo sprintf(__('An e-mail with the password for oc-admin has been sent to: %s'), $data['s_email']);?></p>
    <div style="clear:both;"></div>
    <div class="form-table finish">
        <table>
            <tbody>
            <tr>
                <th><span class="label-like"><?php _e('Username');?></span></th>
                <td>
                    <div class="s_name">
                        <span style="float:left;" ><?php echo $data['admin_user']; ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><span class="label-like"><?php _e('Password');?></span></th>
                <td>
                    <div class="s_passwd">
                        <span style="float: left;"><?php echo osc_esc_html($data['password']); ?></span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="form-table" style="margin-top:1em;">
        <p><?php _e('Do not forget to connect your site with Osclass Market in order to download free and paid themes or plugins. You should connect your site as soon as your log in to your new site.'); ?>
    </div>
    <div class="form-table" style="margin-top:1em;">
        <h3 style="font-weight: 300;"><?php _e('Do you know Osclass Free, the Osclass cloud solution?'); ?></h3>
        <p><?php _e('With Osclass Free you can create your classifieds page without any technical knowledge and in less than one minute.'); ?></p>
        <input style="padding: 8px 14px;background-color: white;" type="button" class="button" onclick="document.location = 'https://osclass.org/hosted/start'" value="<?php echo osc_esc_html( __('Try Osclass Free'));?>" />
    </div>

    <p class="margin20">
        <a target="_blank" href="<?php echo get_absolute_url() ?>oc-admin/index.php" class="button"><?php _e('Finish and go to the administration panel');?></a>
    </p>
<?php
}
?>
