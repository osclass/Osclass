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

/*
 * The url of the site
 *
 * @since 1.2
 *
 * @return string The url of the site
 */
function get_absolute_url( ) {
    $protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http' ;
    $pos      = strpos($_SERVER['REQUEST_URI'], 'oc-includes') ;
    $URI      = rtrim( substr( $_SERVER['REQUEST_URI'], 0, $pos ), '/' ) . '/' ;
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . $URI ;
}

/*
 * The relative url on the domain url
 *
 * @since 1.2
 *
 * @return string The relative url on the domain url
 */
function get_relative_url( ) {
    $url = $_SERVER['REQUEST_URI'];
    return substr($url, 0, strpos($url, '/oc-includes')) . "/";
}

/*
 * Get the requirements to install OSClass
 *
 * @since 1.2
 *
 * @return array Requirements
 */
function get_requirements( ) {
    $gd    = gd_info();
    $array = array(
        'PHP version >= 5.x' => version_compare(PHP_VERSION, '5.0.0', '>='),
        'MySQLi extension for PHP' => extension_loaded('mysqli'),
        'GD extension for PHP' => extension_loaded('gd'),
        'Folder <code>oc-content/uploads</code> exists' => file_exists( ABS_PATH . 'oc-content/uploads/' ),
        'Folder <code>oc-content/uploads</code> is writable' => is_writable( ABS_PATH . 'oc-content/uploads/' ),
        'Folder <code>oc-content/languages</code> exists' => file_exists( ABS_PATH . 'oc-content/languages/' )
    );

    $config_writable = false;
    $root_writable = false;
    $config_sample = false;
    if( file_exists(ABS_PATH . 'config.php') ) {
        if( is_writable(ABS_PATH . 'config.php') ) {
            $config_writable = true;
        }
        $array['File <code>config.php</code> is writable'] = $config_writable;
    } else {
        if (is_writable(ABS_PATH) ) {
            $root_writable = true;
        }
        $array['Root directory is writable'] = $root_writable;

        if( file_exists(ABS_PATH . 'config-sample.php') ) {
            $config_sample = true;
        }
        $array['File <code>config-sample.php</code> exists'] = $config_sample;
    }
    
    return $array;
}

/*
 * Get help of requirements to install OSClass
 *
 * @since 2.1
 *
 * @return array Help of requirements
 */
function get_solution_requirements( ) {
    $array = array(
        'PHP version >= 5.x' => 'PHP5 is required to run OSClass. You may talk with your hosting to upgrade your PHP version.',
        'MySQLi extension for PHP' => 'MySQLi extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/mysqli.setup.php">install/configure</a>.',
        'GD extension for PHP' => 'GD extension is required. How to <a target="_blank" href="http://www.php.net/manual/en/image.setup.php">install/configure</a>.',
        'Folder <code>oc-content/uploads</code> exists' => 'You have to create <code>uploads</code> folder, i.e.: <code>mkdir ' . ABS_PATH . 'oc-content/uploads/</code>' ,
        'Folder <code>oc-content/uploads</code> is writable' => 'Folder <code>uploads</code> has to be writable, i.e.: <code>chmod a+w ' . ABS_PATH . 'oc-content/uploads/</code>',
        'Folder <code>oc-content/languages</code> exists' => 'You have to create <code>languages</code> folder, i.e.: <code>mkdir ' . ABS_PATH . 'oc-content/languages/</code>',
        'Root directory is writable' => 'Root folder has to be writable, i.e.: <code>chmod a+w ' . ABS_PATH . '</code>',
        'File <code>config.php</code> is writable' => 'File <code>config.php</code> has to be writable, i.e.: <code>chmod a+w ' . ABS_PATH . 'config.php</code>',
        'File <code>config-sample.php</code> exists' => 'File <code>config-sample.php</code> is required, you should download OSClass again.'
    );
    return $array;
}

/**
 * Check if some of the requirements to install OSClass are correct or not
 *
 * @since 1.2
 *
 * @return boolean Check if all the requirements are correct
 */
function check_requirements($array) {
    foreach($array as $k => $v) {
        if( !$v ) return true;
    }
    return false;
}

/**
 * Check if allowed to send stats to Osclass
 *
 * @return boolean Check if allowed to send stats to Osclass
 */
function reportToOsclass() {
    return $_COOKIE['osclass_save_stats'] ;
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
    ) ;

    Preference::newInstance()->insert($values) ;
}

/*
 * Install OSClass database
 *
 * @since 1.2
 *
 * @return mixed Error messages of the installation
 */
function oc_install( ) {
    $dbhost      = Params::getParam('dbhost') ;
    $dbname      = Params::getParam('dbname') ;
    $username    = Params::getParam('username') ;
    $password    = Params::getParam('password') ;
    $tableprefix = Params::getParam('tableprefix') ;
    $createdb    = false ;

    if( $tableprefix == '' ) {
        $tableprefix = 'oc_' ;
    }

    if( Params::getParam('createdb') != '' ) {
        $createdb = true ;
    }

    if ( $createdb ) {
        $adminuser = Params::getParam('admin_username') ;
        $adminpwd  = Params::getParam('admin_password') ;

        $master_conn = new DBConnectionClass($dbhost, $adminuser, $adminpwd, '') ;
        $error_num   = $master_conn->getErrorConnectionLevel() ;

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('Cannot connect to database. Error number: ' . $error_num , __FILE__."::".__LINE__) ;
            }

            switch ($error_num) {
                case 1049:  return array('error' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges') ;
                break;
                case 1045:  return array('error' => 'Cannot connect to the database. Check if the user has privileges.') ;
                break;
                case 1044:  return array('error' => 'Cannot connect to the database. Check if the username and password are correct.') ;
                break;
                case 2005:  return array('error' => 'Cannot resolve MySQL host. Check if the host is correct.') ;
                break;
                default:    return array('error' => 'Cannot connect to database. Error number: ' . $error_num . '.') ;
                break;
            }
        }

        $m_db = $master_conn->getOsclassDb() ;
        $comm = new DBCommandClass( $m_db ) ;
        $comm->query( sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname) ) ;

        $error_num = $comm->getErrorLevel() ;

        if( $error_num > 0 ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('Cannot create the database. Error number: ' . $error_num , __FILE__."::".__LINE__) ;
            }

            if( in_array( $error_num, array(1006, 1044, 1045) ) ) {
                return array('error' => 'Cannot create the database. Check if the admin username and password are correct.') ;
            }

            return array('error' => 'Cannot create the database. Error number: ' . $error_num . '.') ;
        }

        unset($conn) ;
        unset($comm) ;
        unset($master_conn) ;
    }

    $conn      = new DBConnectionClass($dbhost, $username, $password, $dbname) ;
    $error_num = $conn->getErrorLevel() ;

    if($error_num > 0) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot connect to database. Error number: ' . $error_num , __FILE__."::".__LINE__) ;
        }

        switch ($error_num) {
            case 1049:  return array('error' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges') ;
            break;
            case 1045:  return array('error' => 'Cannot connect to the database. Check if the user has privileges.') ;
            break;
            case 1044:  return array('error' => 'Cannot connect to the database. Check if the username and password are correct.') ;
            break;
            case 2005:  return array('error' => 'Cannot resolve MySQL host. Check if the host is correct.') ;
            break;
            default:    return array('error' => 'Cannot connect to database. Error number: ' . $error_num . '.') ;
            break;
        }
    }
    
    if( file_exists(ABS_PATH . 'config.php') ) {
        if( !is_writable(ABS_PATH . 'config.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('Cannot write in config.php file. Check if the file is writable.' , __FILE__."::".__LINE__) ;
            }
            return array('error' => 'Cannot write in config.php file. Check if the file is writable.');
        }
        create_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    } else {
        if( !file_exists(ABS_PATH . 'config-sample.php') ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('It doesn\'t exist config-sample.php. Check if you have everything well decompressed.' , __FILE__."::".__LINE__) ;
            }

            return array('error' => 'It doesn\'t exist config-sample.php. Check if you have everything well decompressed.');
        }
        if( !is_writable(ABS_PATH) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('Can\'t copy config-sample.php. Check if the root directory is writable.' , __FILE__."::".__LINE__) ;
            }

            return array('error' => 'Can\'t copy config-sample.php. Check if the root directory is writable.');
        }
        copy_config_file($dbname, $username, $password, $dbhost, $tableprefix);
    }

    require_once ABS_PATH . 'config.php';

    $sql = file_get_contents( ABS_PATH . 'oc-includes/osclass/installer/struct.sql' ) ;

    $c_db = $conn->getOsclassDb() ;
    $comm = new DBCommandClass( $c_db ) ;
    $comm->importSQL($sql) ;

    $error_num = $comm->getErrorLevel() ;

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot create the database structure. Error number: ' . $error_num  , __FILE__."::".__LINE__) ;
        }

        switch ($error_num) {
            case 1050:  return array('error' => 'There are tables with the same name in the database. Change the table prefix or the database and try again.') ;
            break;
            default:    return array('error' => 'Cannot create the database structure. Error number: ' . $error_num . '.') ;
            break;
        }
    }

    require_once LIB_PATH . 'osclass/locales.php';
    require_once LIB_PATH . 'osclass/model/OSCLocale.php';
    $localeManager = OSCLocale::newInstance();

    $locales = osc_listLocales() ;
    foreach($locales as $locale) {
        $values = array(
            'pk_c_code'         => $locale['code'],
            's_name'            => $locale['name'],
            's_short_name'      => $locale['short_name'],
            's_description'     => $locale['description'],
            's_version'         => $locale['version'],
            's_author_name'     => $locale['author_name'],
            's_author_url'      => $locale['author_url'],
            's_currency_format' => $locale['currency_format'],
            's_date_format'     => $locale['date_format'],
            'b_enabled'         => ($locale['code'] == 'en_US') ? 1 : 0,
            'b_enabled_bo'      => 1
        ) ;

        if( isset($locale['stop_words']) ) {
            $values['s_stop_words'] = $locale['stop_words'] ;
        }

        $localeManager->insert($values) ;
    }

    $required_files = array('basic_data.sql', 'categories.sql', 'pages.sql');

    $sql = '';
    foreach($required_files as $file) {
        if ( !file_exists(ABS_PATH . 'oc-includes/osclass/installer/' . $file) ) {
            if( reportToOsclass() ) {
                LogOsclassInstaller::instance()->error('the file ' . $file . ' doesn\'t exist in data folder' , __FILE__."::".__LINE__) ;
            }

            return array('error' => 'the file ' . $file . ' doesn\'t exist in data folder' );
        } else {
            $sql .= file_get_contents(ABS_PATH . 'oc-includes/osclass/installer/' . $file);
        }
    }

    $comm->importSQL($sql) ;

    $error_num = $comm->getErrorLevel() ;

    if( $error_num > 0 ) {
        if( reportToOsclass() ) {
            LogOsclassInstaller::instance()->error('Cannot insert basic configuration. Error number: ' . $error_num  , __FILE__."::".__LINE__) ;
        }

        switch ($error_num) {
            case 1471:  return array('error' => 'Cannot insert basic configuration. This user has no privileges to \'INSERT\' into the database.') ;
            break;
            default:    return array('error' => 'Cannot insert basic configuration. Error number: ' . $error_num . '.');
            break;
        }
    }

    if( reportToOsclass() ) {
        set_allow_report_osclass( true ) ;
    } else {
        set_allow_report_osclass( false ) ;
    }

    return false ;
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
    $abs_url = get_absolute_url();
    $rel_url = get_relative_url();
    $config_text = <<<CONFIG
<?php
/**
 * The base MySQL settings of OSClass
 */
define('MULTISITE', 0);

/** MySQL database name for OSClass */
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
        return false ;
    }

    require_once ABS_PATH . 'config.php' ;

    $conn = new DBConnectionClass( osc_db_host(), osc_db_user(), osc_db_password(), osc_db_name() ) ;
    $c_db = $conn->getOsclassDb() ;
    $comm = new DBCommandClass( $c_db ) ;
    $rs   = $comm->query( sprintf( "SELECT * FROM %st_preference WHERE s_name = 'osclass_installed'", DB_TABLE_PREFIX ) ) ;

    if( $rs == false ) {
        return false ;
    }

    if( $rs->numRows() != 1 ) {
        return false ;
    }

    return true ;
}

function finish_installation( $password ) {
    require_once LIB_PATH . 'osclass/model/Admin.php' ;
    require_once LIB_PATH . 'osclass/model/Category.php';
    require_once LIB_PATH . 'osclass/model/Item.php';
    require_once LIB_PATH . 'osclass/helpers/hPlugins.php';
    require_once LIB_PATH . 'osclass/compatibility.php';
    require_once LIB_PATH . 'osclass/plugins.php';
    
    $data = array();

    $mAdmin = new Admin() ;

    $mPreference = Preference::newInstance() ;
    $mPreference->insert (
        array(
            's_section' => 'osclass'
            ,'s_name' => 'osclass_installed'
            ,'s_value' => '1'
            ,'e_type' => 'BOOLEAN'
        )
    );

    // update categories
    $mCategories = new Category();
    if(Params::getParam('submit') != '') {
        $categories = Params::getParam('categories');
        if(is_array($categories)) {
            foreach($categories as $category_id) {
                $mCategories->update(array('b_enabled' => '1')
                                    ,array('pk_i_id'   => $category_id));
            }
        }
    }
    $aCategoriesToDelete = $mCategories->listWhere("a.b_enabled = 0");
    foreach($aCategoriesToDelete as $aCategory) {
        $mCategories->deleteByPrimaryKey($aCategory['pk_i_id']);
    }

    $admin = $mAdmin->findByPrimaryKey(1) ;

    $data['s_email'] = $admin['s_email'] ;
    $data['admin_user'] = $admin['s_username'] ;
    $data['password'] = $password;

    return $data ;
}

/* Menus */
function display_database_config() {
?>
<form action="install.php" method="POST">
    <input type="hidden" name="step" value="3" />
    <h2 class="target">Database information</h2>
    <div class="form-table">
        <table>
            <tbody>
                <tr>
                    <th align="left"><label for="dbhost">Host</label></th>
                    <td><input type="text" id="dbhost" name="dbhost" value="localhost" size="25" /></td>
                    <td class="small">Server name or IP where the database engine resides</td>
                </tr>
                <tr>
                    <th align="left"><label for="dbname">Database name</label></th>
                    <td><input type="text" id="dbname" name="dbname" value="osclass" size="25" /></td>
                    <td class="small">The name of the database you want to run OSClass in</td>
                </tr>
                <tr>
                    <th align="left"><label for="username">User Name</label></th>
                    <td><input type="text" id="username" name="username" size="25" /></td>
                    <td class="small">Your MySQL username</td>
                </tr>
                <tr>
                    <th align="left"><label for="password">Password</label></th>
                    <td><input type="password" id="password" name="password" value="" size="25" /></td>
                    <td class="small">Your MySQL password</td>
                </tr>
                <tr>
                    <th align="left"><label for="tableprefix">Table prefix</label></th>
                    <td><input type="text" id="tableprefix" name="tableprefix" value="oc_" size="25" /></td>
                    <td class="small">If you want to run multiple OSClass installations in a single database, change this</td>
                </tr>
            </tbody>
        </table>
        <div id="advanced_install" class="shrink">
            <div class="text">
                <span>Advanced</span>
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
            });
        </script>
        <div style="clear:both;"></div>
        <table id="more-options" style="display:none;">
            <tbody>
                <tr>
                    <th></th>
                    <td><input type="checkbox" id="createdb" name="createdb" onclick="db_admin();" value="1" /><label for="createdb">Create DB</label></td>
                    <td class="small">Check here if the database is not created and you want to create it now</td>
                </tr>
                <tr id="admin_username_row">
                    <th align="left"><label for="admin_username">DB admin username</label></th>
                    <td><input type="text" id="admin_username" name="admin_username" size="25" disabled/></td>
                    <td></td>
                </tr>
                <tr id="admin_password_row">
                    <th align="left"><label for="admin_password">DB admin password</label></th>
                    <td><input type="password" id="admin_password" name="admin_password" value="" size="25" disabled/></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <input type="submit" class="button" name="submit" value="Next"/>
    </p>
    <div class="clear"></div>
</form>
<?php
}

function display_target() {
?>
<form id="target_form" name="target_form" action="#" method="POST" onsubmit="return false;">
    <h2 class="target">Information needed</h2>
    <div class="form-table">
        <h2 class="title">Admin user</h2>
        <table class="admin-user">
            <tbody>
                <tr>
                    <th><label>Username</label></th>
                    <td>
                        <input size="25" id="admin_user" name="s_name" type="text" value="admin"/>
                    </td>
                    <td><span id="admin-user-error" class="error" style="display:none;">Admin user is required</span></td>
                </tr>
                <tr>
                    <th><label>Password</label></th>
                    <td>
                        <input size="25" class="password_test" name="s_passwd" type="text" value=""/>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <div class="admin-user">
            A password will be automatically generated for you if you leave this blank.
            <img src="<?php echo get_absolute_url() ?>oc-includes/images/question.png" class="question-skip vtip" title="You can modify username and password if you like, only need change the inputs value." alt=""/>
        </div>
        <h2 class="title">Contact information</h2>
        <table class="contact-info">
            <tbody>
                <tr>
                    <th><label for="webtitle">Web title</label></th>
                    <td><input type="text" id="webtitle" name="webtitle" size="25"/></td>
                    <td></td>
                </tr>
                <tr>
                    <th><label for="email">Contact e-mail</label></th>
                    <td><input type="text" id="email" name="email" size="25"/></td>
                    <td><span id="email-error" class="error" style="display:none;">Put your e-mail here</span></td>
                </tr>
            </tbody>
        </table>
        <h2 class="title">Location</h2>
        <p class="space-left-25 left no-bottom">Choose countries/cities where your target users are located</p>
        <div id="location-question" class="left question">
            <img class="vtip" src="<?php echo get_absolute_url(); ?>oc-includes/images/question.png" title="Once you write a country, you'll be able to choose region and city too. Therefore, the intallation'll be more specific." alt=""/>
        </div>
        <div class="clear"></div>
        <div id="location">
            <div id="country-box">
                <div id="radio-target" style="display: none;">
                    <input id="icountry" type="radio" name="c_country" value="Country" checked onclick="change_to_country(this);"/>
                    <label for="icountry">Country</label>
                    <input id="worlwide" type="radio" name="c_country" value="International" onclick="change_to_international(this);" />
                    <label for="worlwide">Worldwide</label>
                </div>
                <div id="d_country" class="box">
                    <input type="text" id="t_country" class="left" name="t_country" size="1" onkeydown="more_size(this, event);"/>
                    <div class="clear"></div>
                </div>
                <div id="a_country">

                </div>
                <p id="country-error" style="display:none;">Region/City targeting is only available when you choose only "one country"</p>
            </div>
            <div id="region-div" style="display:none;">
                <div id="region-info" class="space-left-10">
                    <a href="javascript://" onclick="$('#region-box').attr('style', '');$('#region-info').attr('style', 'display:none');$('#t_location').focus();">Click here if you want to specify region/regions or city/cities</a>
                </div>
                <div id="region-box"  class="space-left-60" style="display:none;">
                    <div id="radio-target">
                        <input id="iregion" type="radio" name="c_location" value="By region" onclick='$("#d_location span").remove();' checked="checked"/>
                        <label for="iregion">By Region</label>
                        <input id="icity" type="radio" name="c_location" value="By City" onclick='$("#d_location span").remove();'/>
                        <label for="icity">By City</label>
                    </div>
                    <div id="d_location" class="box">
                        <input type="text" id="t_location" name="t_location" size="1" onkeydown="more_size(this);" />
                    </div>
                    <div id="a_location">

                    </div>
                </div>
            </div>
            <div style="display: none;" id="location-error">
                No internet connection. You can continue the installation and insert countries later.
                <input type="hidden" id="skip-location-h" name="skip-location-h" value="0"/>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <a href="#" class="button" onclick="validate_form();">Next</a>
    </p>
    <div id="skip-location-d" style="display:none;">
        <label for="skip-location" style="padding-left: 12px;"><input id="skip-location" name="skip-location" type="checkbox"/>Continue installation process and insert countries later</label>
    </div>
    <div class="clear"></div>
</form>
<div id="lightbox" style="display:none;">
    <div class="center">
        <img src="<?php echo get_absolute_url(); ?>oc-includes/images/loading.gif" alt="" title=""/>
    </div>
</div>
<?php
}

function display_database_error($error ,$step) {
?>
<h2 class="target">Error</h2>
<p class="bottom space-left-10">
    <?php echo $error['error']?>
</p>
<a href="<?php echo get_absolute_url(); ?>oc-includes/osclass/install.php?step=<?php echo $step; ?>" class="button">Go back</a>
<div class="clear bottom"></div>
<?php
}

function display_categories($error, $password) {
    require_once ABS_PATH . 'config.php';
    require_once LIB_PATH . 'osclass/model/Category.php';

    $categories = Category::newInstance()->toTreeAll();
    $numCols = 3;
    $catsPerCol = ceil(count($categories)/$numCols) ;
?>
<?php if($error) { ?>

    <h2 class="target">Error</h2>
    <p class="bottom space-left-10">
        <?php echo $error;?>
    </p>

<?php } ?>
<form id="category_form" action="install.php?step=5" method="POST">
    <input type="hidden" name="password" value="<?php echo $password;?>"/>
    <h2 class="target">Categories</h2>
    <div class="form-table">
        <?php if(Params::getParam('error_location') == 1) { ?>
        <script type="text/javascript">
            setTimeout (function(){
                $('.error-location').fadeOut('slow');
            }, 2500);
        </script>
        <div class="error-location">
            The location selected could not been installed
        </div>
        <?php } ?>
        <div class="select-categories">
            &nbsp;
            <div class="right">
                <a href="#" onclick="check_all('category_form', true); return false;">Check all</a>
                ·
                <a href="#" onclick="check_all('category_form', false); return false;">Uncheck all</a>
            </div>
            <div class="left">
                <h3>Select your classified categories <span style="font-size:11px;">or</span> <a href="install.php?step=5">Skip</a><img src="<?php echo get_absolute_url() ?>oc-includes/images/question.png" class="question-skip vtip" title="You can add/remove categories after the installation, using the admin dashboard." alt=""/></h3>
            </div>
        </div>
        <table class="list-categories">
            <tr>
                <?php for ($j = 0 ; $j < $numCols ; $j++) {?>
                        <td>
                            <?php for ($i = $catsPerCol*$j ; $i < $catsPerCol*($j+1) ; $i++) {?>
                            <?php if (isset($categories[$i]) && is_array($categories[$i])) {?>
                            <div class="cat-title">
                                <label for="category-<?php echo $categories[$i]['pk_i_id']?>">
                                    <input id="category-<?php echo $categories[$i]['pk_i_id']?>" class="left" type="checkbox" name="categories[]" value="<?php echo $categories[$i]['pk_i_id']?>" onclick="javascript:check_cat('<?php echo $categories[$i]['pk_i_id']?>', this.checked);"/>
                                    <span><?php echo $categories[$i]['s_name']?></span>
                                </label>
                            </div>
                            <div id="cat<?php echo $categories[$i]['pk_i_id'];?>" class="sub-cat-title">
                                <?php foreach($categories[$i]['categories'] as $sc) { ?>
                                <div id="category" class="space">
                                    <label for="category-<?php echo $sc['pk_i_id']?>" class="space">
                                        <input id="category-<?php echo $sc['pk_i_id']?>" type="checkbox" name="categories[]" value="<?php echo $sc['pk_i_id']?>" onclick="javascript:check('category-<?php echo $categories[$i]['pk_i_id']?>')"/>
                                        <?php echo $sc['s_name']; ?>
                                    </label>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        </td>
                <?php } ?>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <p class="margin20">
        <input type="submit" class="button" name="submit" value="Next"/>
    </p>
    <div class="clear"></div>
</form>
<?php
}

function ping_search_engines($bool){
    $mPreference = Preference::newInstance() ;
    if($bool == 1){
        $mPreference->insert (
            array(
                's_section' => 'osclass'
                ,'s_name'   => 'ping_search_engines'
                ,'s_value'  => '1'
                ,'e_type'   => 'BOOLEAN'
            )
        ) ;
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
        ) ;
    }
}
function display_finish($password) {
    $data = finish_installation( $password );
?>
<h2 class="target">Congratulations!</h2>
<p class="space-left-10">OSClass has been installed. Were you expecting more steps? Sorry to disappoint.</p>
<p class="space-left-10">An e-mail with the password for oc-admin has sent to: <?php echo $data['s_email']?></p>
<div style="clear:both;"></div>
<div class="form-table finish">
    <table>
        <tbody>
            <tr>
                <th><label>Username</label></th>
                <td>
                    <div class="s_name">
                        <span style="float:left;" ><?php echo $data['admin_user']; ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th><label>Password</label></th>
                <td>
                    <div class="s_passwd">
                        <span style="float: left;"><?php echo $data['password']; ?></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<p class="margin20">
    <a target="_blank" href="<?php echo get_absolute_url() ?>oc-admin/index.php" class="button">Finish and go to the administration panel</a>
</p>
<?php
}
?>
