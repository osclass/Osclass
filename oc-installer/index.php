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

function osc_getRelativeWebURL() {
    $url = $_SERVER['REQUEST_URI'];
    $pos = strpos($url, '/oc-installer');
    return substr($url, 0, strpos($url, '/oc-installer'));
}

function osc_getAbsoluteWebURL() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
    return $protocol . '://' . $_SERVER['HTTP_HOST'] . osc_getRelativeWebURL();
}

function __($k) {
    return $k;
}

function oc_install() 
{
    $config_file = '../config.php';

    $dbhost = trim($_POST['dbhost']);
    $dbname = trim($_POST['dbname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $tableprefix  = trim($_POST['tableprefix']);
    $createdb = false;

    if ( empty($tableprefix) ) $tableprefix = 'oc_';
    if ( isset($_POST['createdb']) ) $createdb = true;
    if ( $createdb ) 
    {
        $adminuser = trim($_POST['admin_username']);
        $adminpwd = trim($_POST['admin_password']);

    	try {
    	    $master_conn = getConnection($dbhost, $adminuser, $adminpwd, 'mysql', DEBUG_LEVEL) ;
            $master_conn->osc_dbExec(sprintf("CREATE DATABASE IF NOT EXISTS %s DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI'", $dbname)) ;
    	} catch (Exception $e) {
            $error_num = $e->getErrno();
            if($error_num == 1006 || $error_num == 1044 || $error_num == 1045) {
                return array('error' => 'Cannot create the database. Check if the admin username and password are correct.');
            }
            return array('error' => 'Cannot create the database. Unknown error.');
        }
    }

    try {
        $conn = getConnection($dbhost, $username, $password, $dbname, DEBUG_LEVEL) ;
    } catch (Exception $e) {
        $error_num = $e->getErrno();
        if( $error_num == 1049 ) return array('error' => 'The database doesn\'t exist. You should check the "Create DB" checkbox and fill username and password with the right privileges');
        if ( $error_num == 1045 ) return array('error' => 'Cannot connect to the database. Check if the user has privileges.');
        if ( $error_num == 1044 ) return array('error' => 'Cannot connect to the database. Check if the username and password are correct.');
        
        return array('error' => 'Cannot connect to database. Unknown error.');
    }

    $abs_url = osc_getAbsoluteWebURL();
    $rel_url = osc_getRelativeWebURL();

    if(file_exists($config_file)) {
        if(!is_writable($config_file)) {
            return array('error' => 'Cannot write in config.php file. Check if the file is writable.');
        }

$config_text = <<<CONFIG
<?php
/**
 * The base MySQL settings of OSClass
 */

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

define('ABS_WEB_URL', '$abs_url');
define('REL_WEB_URL', '$rel_url');

define('WEB_PATH', ABS_WEB_URL);

CONFIG;

        file_put_contents($config_file, $config_text);
    } else {
        
        if(!file_exists('../config-sample.php')) {
            return array('error' => 'It doesn\'t exist config-sample.php. Check if you have everything well decompressed.');
        }
        
        if(!is_writable(dirname(dirname(__FILE__)).'/')) {
            return array('error' => 'Can\'t copy config-sample.php. Check if the root directory is writable.');
        }

        $config_sample = file(dirname(dirname(__FILE__)). '/' . 'config-sample.php');
        
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
                case "define('ABS_WEB_":
                    $config_sample[$line_num] = str_replace('http://localhost', $abs_url, $line);
                    break;
                case "define('REL_WEB_":
                    $config_sample[$line_num] = str_replace('rel_here', $rel_url, $line);
                    break;
            }
	}

        $handle = fopen(dirname(dirname(__FILE__)) . '/' . 'config.php', 'w');
        foreach( $config_sample as $line ) {
            fwrite($handle, $line);
        }
        fclose($handle);
        chmod(dirname(dirname(__FILE__)).'/' . 'config.php', 0666);
    }


    $htaccess_file = '../.htaccess';
    if(file_exists($htaccess_file) && is_writable($htaccess_file)) {
$htaccess_text = <<<CONFIG
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $rel_url/

#Pages: contact, feed, sitemap
RewriteRule ^contact(.html)?$ index.php?action=contact [NC,L]
RewriteRule ^feed.xml$ index.php?action=feed [NC,L]
RewriteRule ^sitemap.xml$ index.php?action=sitemap [NC,L]

#Advertisements
RewriteRule -(\d+)$ item.php?id=$1 [NC,L]

#Static pages
RewriteRule -p(\d+)$ page.php?id=$1 [NC,L]

#Redirect 301 of first page of category
RewriteCond %{REQUEST_URI} !^((oc-admin)|(oc-content)|(oc-includes)).*
RewriteRule ^([a-zA-Z\_\-]+/([a-zA-Z\_\-]*/)?)1/?$ $1 [R=301,L]

#Categories
RewriteRule ^([a-zA-Z\_\-]+/([a-zA-Z\_\-]*/)?)(\d+)/?$ category.php?slug=$1&page=$3 [NC,L]
RewriteCond %{REQUEST_URI} !^/((oc-admin)|(oc-includes)|(oc-content)).*
RewriteRule ^([a-zA-Z\_\-]+)$ $1/ [R=301,L]
RewriteCond %{REQUEST_URI} !^/((oc-admin)|(oc-includes)|(oc-content)).*
RewriteRule ^([a-zA-Z\_\-]+/[a-zA-Z\_\-]*?)/?$ category.php?slug=$1&page=1 [NC,L]

ErrorDocument 404 /index.php?action=errorPage&code=404
ErrorDocument 500 /index.php?action=errorPage&code=500
</IfModule>

CONFIG;

        file_put_contents($htaccess_file, $htaccess_text);
    }

    require $config_file;

    try {
        $sql = file_get_contents('data/struct.sql');
        $conn->osc_dbImportSQL($sql);
    } catch (Exception $e) {
        $error_num = $e->getErrno();
        if ( $error_num == 1050 ) {
            return array('error' => 'There are tables with the same name in the database. Change the table prefix or the database and try again.');
        }
        return array('error' => 'Cannot create the database structure. Unknown error.');
    }

    try {
        require_once 'osclass/classes/DAO.php';
    	require_once 'osclass/locales.php';
    	require_once 'osclass/model/Locale.php';
    	$localeManager = Locale::newInstance();
    
    	$locales = osc_listLocales();
    	foreach($locales as $locale) 
    	{
            $values = array(
                'pk_c_code' => $locale['code'],
                's_name' => $locale['name'],
                's_short_name' => $locale['short_name'],
                's_description' => $locale['description'],
                's_version' => $locale['version'],
                's_author_name' => $locale['author_name'],
                's_author_url' => $locale['author_url'],
                's_currency_format' => $locale['currency_format'],
                's_date_format' => $locale['date_format'],
                'b_enabled' => ($locale['code'] == 'en_US') ? 1:0,
                'b_enabled_bo' => 1
            );
            if(isset($locale['stop_words'])) $values['s_stop_words'] = $locale['stop_words'];

            $localeManager->insert($values);
        }

        $required_files = array('basic_data.sql', 'categories.sql', 'pages.sql');

    	$sql = '';
    	foreach($required_files as $file) {
            if (!file_exists('data/' . $file)) return array('error' => 'the file ' . $file . ' doesn\'t exist in data folder' );
            else $sql .= file_get_contents('data/' . $file);
        }
        
        $conn->osc_dbImportSQL($sql, ')');
    } catch (Exception $e) {
        $error_num = $e->getErrno();
        if ( $error_num == 1471 ) {
            return array('error' => 'Cannot insert basic configuration. This user has no privileges to \'INSERT\' into the database.');
        }
        return array('error' => 'Cannot insert basic configuration.');
    }
    return false;
}

require_once '../common.php';
require_once 'osclass/db.php';
require_once 'osclass/web.php';

// We get the step
if( isset($_GET['step']) ) {
    $step = (int) $_GET['step'];
} else {
    $step = 1;
}

$config = false;
$config_writable = false;
$root_writable = false;

if($step == 1) {
    if( file_exists('../config.php') )
        $config = true;

    if( is_writable('../config.php') )
        $config_writable = true;

    if ( !file_exists('../config.php') ) {
        if (!is_writable( dirname(dirname(__FILE__)) . '/' ))
            $root_writable = true;
    }

    $checks = array(
            'PHP version >= 5.x' => version_compare(PHP_VERSION, '5.0.0', '>='),
            'MySQL extension for PHP installed' => extension_loaded('mysqli'),
            'GD extension for PHP installed' => extension_loaded('gd'),
            'Folder "oc-content/uploads" exists' => file_exists('../oc-content/uploads'),
            'Folder "oc-content/uploads" is writable' => is_writable('../oc-content/uploads'),
            'Folder "oc-includes/translations" exists' => file_exists('../oc-includes/translations'),
            'Folder "oc-includes/translations" is writable' => is_writable('../oc-includes/translations'),
    );

    $error = false;
    foreach($checks as $e => $c) {
        if(!$c)
            $error = true;
    }

    if($config) {
        if(!$config_writable) {
            $error = true;
            $checks['File "config.php" is writable'] = false;
        } else {
            $checks['File "config.php" is writable'] = true;
        }
    } else {
        if($root_writable) {
            $error = true;
            $checks['Root direcotry is writable'] = false;
        } else {
            $checks['Root direcotry is writable'] = true;
        }
    }
} else if($step == 3) {
    if( isset($_POST['dbname']) )
        $error = oc_install();
} else if($step == 5) {
    require_once '../config.php';
    require_once 'osclass/classes/DAO.php';
    require_once 'osclass/model/Category.php';

    if( isset($_POST['submit']) ) {
        $enabled = array(0);
        if (isset($_POST['categories']) && !empty($_POST['categories']) && is_array($_POST['categories'])) {
            $enabled = $_POST['categories'];
            Category::newInstance()->update(
                    array('b_enabled' => DB_CONST_TRUE)
            );

            Category::newInstance()->update(
                    array('b_enabled' => DB_CONST_FALSE),
                    array(DB_CUSTOM_COND => 'pk_i_id NOT IN (' . implode(', ', $enabled) . ')')
            );
        }
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php echo __( 'OSClass Installation' ); ?></title>
            <script src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-includes/js/jquery-1.4.2.js" type="text/javascript"></script>
            <script src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-includes/js/jquery-ui-1.8.5.js" type="text/javascript"></script>
            <script src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-installer/vtip/vtip.js" type="text/javascript"></script>
            <script src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-installer/jquery.jsonp.js" type="text/javascript"></script>
            <script src="<?php echo osc_getAbsoluteWebURL(); ?>/oc-installer/functions.js" type="text/javascript"></script>
            <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_getAbsoluteWebURL(); ?>/oc-includes/css/install.css" />
            <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_getAbsoluteWebURL(); ?>/oc-installer/vtip/css/vtip.css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div id="header" class="installation">
                    <h1 id="logo">
                        <img src="images/osclass-logo.png" alt="OSClass" title="OSClass"/>
                    </h1>
                    <?php if($step > 0) : ?>
                    <ul id="nav">
                        <li class="<?php if($step == 1) { ?>actual<?php } else { ?>past<?php }?>">1 - Welcome</li>
                        <li class="<?php if($step == 2) { ?>actual<?php } elseif($step < 2) { ?>next<?php } else { ?>past<?php }?>">2 - Database</li>
                        <li class="<?php if($step == 3) { ?>actual<?php } elseif($step < 3) { ?>next<?php } else { ?>past<?php }?>">3 - Target</li>
                        <li class="<?php if($step == 4) { ?>actual<?php } elseif($step < 4) { ?>next<?php } else { ?>past<?php }?>">4 - Categories</li>
                        <li class="<?php if($step == 5) { ?>actual<?php } elseif($step < 5) { ?>next<?php } else { ?>past<?php }?>">5 - Congratulations!</li>
                    </ul>
                    <div class="clear"></div>
                    <?php endif; ?>
                </div>
                <div id="content">
                <?php if($step == 1) : ?>
                <h2 class="welcome">Welcome</h2>
                <?php if($error) : ?>
                    <p>Check the next requirements:</p>
                <?php else: ?>
                    <p>All right! All the requirements have met:</p>
                <?php endif; ?>
                    <ul>
                    <?php foreach($checks as $req => $satisfied): ?>
                            <li><?php echo $req; ?> <img src="images/<?php echo $satisfied ? 'tick.png' : 'cross.png'; ?>" /></li>
                    <?php endforeach; ?>
                    </ul>
                    <div class="clear">&nbsp;</div>
                <?php if($error) : ?>
                    <p><a class="button" href="index.php?step=1">Try again</a></p>
                <?php else: ?>
                    <p><a class="button" href="index.php?step=2">Next</a></p>
                <?php
                    endif;
                    elseif($step == 2) :
                         display_database_config();
                    elseif($step == 3) :
                        if(!isset($error["error"]))
                            display_target();
                        else
                            display_database_error($error, ($step - 1));
                    elseif($step == 4) :
                        display_categories();
                    elseif($step == 5) :
                        display_finish();
                    endif;
                ?>  
                </div>
                <div id="footer">
                    <ul>
                        <li><a href="<?php echo osc_getAbsoluteWebURL(); ?>/readme.php" target="_blank">Readme</a></li>
                        <li><a href="http://osclass.org/contact/" target="_blank">Feedback</a></li>
                        <li><a href="http://forums.osclass.org/index.php" target="_blank">Forums</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    function display_database_config() {
?>
<form action="index.php?step=3" method="POST">
    <h2 class="target">Database information</h2>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="dbhost">Host</label></th>
                <td><input type="text" id="dbhost" name="dbhost" value="localhost" size="25" /></td>
                <td>Server name or IP where the database engine resides</td>
            </tr>
            <tr>
                <th><label for="dbname">Database name</label></th>
                <td><input type="text" id="dbname" name="dbname" value="osclass" size="25" /></td>
                <td>The name of the database you want to run OSClass in</td>
            </tr>
            <tr>
                <th><label for="username">User Name</label></th>
                <td><input type="text" id="username" name="username" size="25" /></td>
                <td>Your MySQL username</td>
            </tr>
            <tr>
                <th><label for="password">Password</label></th>
                <td><input type="password" id="password" name="password" value="" size="25" /></td>
                <td>Your MySQL password</td>
            </tr>
            <tr>
                <th><label for="tableprefix">Table prefix</label></th>
                <td><input type="text" id="tableprefix" name="tableprefix" value="oc_" size="25" /></td>
                <td>If you want to run multiple OSClass installations in a single database, change this</td>
            </tr>
            <tr>
                <th></th>
                <td><input type="checkbox" id="createdb" name="createdb" onclick="db_admin();"/><label for="createdb">Create DB</label></td>
                <td>Check here if the database is not created and you want to create it now</td>
            </tr>
            <tr id="admin_username_row">
                <th><label for="admin_username">DB admin username</label></th>
                <td><input type="text" id="admin_username" name="admin_username" size="25" disabled/></td>
                <td>Check here if the database is not created and you want to create it now</td>
            </tr>
            <tr id="admin_password_row">
                <th><label for="admin_password">DB admin password</label></th>
                <td><input type="password" id="admin_password" name="admin_password" value="" size="25" disabled/></td>
                <td>Check here if the database is not created and you want to create it now</td>
            </tr>
        </tbody>
    </table>
    <div class="clear"></div>
    <p><input type="submit" class="button" name="submit" value="Next"/></p>
    <div class="clear"></div>
</form>
<?php
    }
?>

<?php
    function display_target() {
?>
<form id="target_form" name="target_form" action="#" method="POST" onsubmit="return false;">
    <h2 class="target">Information needed</h2>
    <div class="form-table">
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
            <img class="vtip" src="images/question.png" title="Worldwide install all the countries. However, if you choose 'Country' and you write one specific country, you'll be able to choose region and city too. Therefore, the intallation'll be more specific." alt=""/>
        </div>
        <div class="clear"></div>
        <div id="location">
            <div id="country-box">
                <div id="radio-target">
                    <input id="icountry" type="radio" name="c_country" value="Country" checked onclick="change_to_country(this);"/>
                    <label for="icountry">Country</label>
                    <input id="worlwide" type="radio" name="c_country" value="International" onclick="change_to_international(this);" />
                    <label for="worlwide">Worldwide</label>
                </div>
                <div id="d_country" class="box">
                    <input type="text" id="t_country" class="left" name="t_country" size="1" onkeydown="more_size(this);"/>
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
    <p class="left">
        <a href="#" class="button" onclick="validate_form();">Next</a>
    </p>
    <div id="skip-location-d" style="display:none;">
        <label for="skip-location" style="padding-left: 12px;"><input id="skip-location" name="skip-location" type="checkbox"/>Continue installation process and insert countries later</label>
    </div>
    <div class="clear"></div>
</form>
<div id="lightbox" style="display:none;">
    <div class="center">
        <img src="images/loading.gif"/>
    </div>
</div>
<?php
    }
?>

<?php
    function display_database_error($error ,$step) {
?>
<h2 class="target">Error</h2>
<p class="bottom space-left-10"><?php echo $error['error']?></p>
<a href="index.php?step=<?php echo $step?>" class="button">Go back</a>
<div class="clear bottom"></div>
<?php
    }
?>

<?php
    function display_categories() {
        require_once '../config.php';
        require_once 'osclass/classes/DAO.php';
        require_once 'osclass/model/Category.php';

        $categories = Category::newInstance()->toTreeAll();
        $numCols = 3;
        $catsPerCol = ceil(count($categories)/$numCols) ;
?>
<form id="category_form" action="index.php?step=5" method="POST">
    <h2 class="target">Categories</h2>
    <div class="form-table">
        <div class="select-categories">
            &nbsp;
            <div class="right">
                <a href="#" onclick="check_all('category_form', true); return false;">Check all</a>
                ·
                <a href="#" onclick="check_all('category_form', false); return false;">Uncheck all</a>
            </div>
            <div class="left">
                <h3>Select your classified categories <span style="font-size:11px;">or</span> <a href="index.php?step=5">Skip</a><img src="images/question.png" class="question-skip vtip" title="You can add/remove categories after the installation, using the admin dashboard." alt=""/></h3>
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
                                <?php foreach($categories[$i]['categories'] as $sc): ?>
                                <div id="category" class="space">
                                    <label for="category-<?php echo $sc['pk_i_id']?>" class="space">
                                        <input id="category-<?php echo $sc['pk_i_id']?>" type="checkbox" name="categories[]" value="<?php echo $sc['pk_i_id']?>" onclick="javascript:check('category-<?php echo $categories[$i]['pk_i_id']?>')"/>
                                        <?php echo $sc['s_name']; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php } ?>
                        <?php } ?>
                        </td>
                <?php } ?>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
    <p><input type="submit" class="button" name="submit" value="Next"/></p>
    <div class="clear"></div>
</form>
<?php
    }
?>

<?php
    function display_finish() {
        require_once 'osclass/security.php';
        require_once 'osclass/model/Admin.php';
        require_once 'osclass/model/Preference.php';
        
        $password = osc_genRandomPassword();
        $admin = Admin::newInstance()->update(
            array('s_password' => sha1($password)),
            array('s_username' => 'admin')
        );
        $admin = Admin::newInstance()->findByPrimaryKey(1);

        $preferences = Preference::newInstance()->toArray();

        $body = 'Welcome ' . $preferences['pageTitle'] . ',<br/><br/>';
        $body .= 'Your OSClass installation at ' . ABS_WEB_URL . ' is up and running. You can access to the administration panel with this data access:<br/>';
        $body .= '<ul>';
        $body .= '<li>username: ' . 'admin' . '</li>';
        $body .= '<li>password: ' . $password . '</li>';
        $body .= '</ul>';
        $body .= 'Regards,<br/>';
        $body .= 'The <a href=\'http://osclass.org/\'>OSClass</a> team';

        $sitename = strtolower( $_SERVER['SERVER_NAME'] );
        if ( substr( $sitename, 0, 4 ) == 'www.' ) {
                $sitename = substr( $sitename, 4 );
        }
        require_once 'phpmailer/class.phpmailer.php';
        $mail = new PHPMailer;
        $mail->CharSet="utf-8";
        $mail->Host = "localhost";
        $mail->From = 'osclass@' . $sitename;
        $mail->FromName = __('OSClass');
        $mail->Subject = __('OSClass successfully installed!');
        $mail->AddAddress($admin['s_email'], __('OSClass administrator'));
        $mail->Body = $body;
        $mail->AltBody = $body;
        if (!$mail->Send())
            echo $mail->ErrorInfo;
?>
<h2 class="target">Congratulations!</h2>
<p class="space-left-10">OSClass has been installed. Were you expecting more steps? Sorry to disappoint.</p>
<p class="space-left-10">An e-mail with the password for oc-admin has sent to: <?php echo $admin['s_email']?></p>
<div class="form-table finish">
    <table>
        <tbody>
            <tr>
                <th><label>Username</label></th>
                <td>admin</td>
            </tr>
            <tr>
                <th><label>Password</label></th>
                <td><p><?php echo $password?></p></td>
            </tr>
            <tr>
                <th></th>
                <td>Note that password carefully! It is a random password that was generated just for you.</td>
            </tr>
        </tbody>
    </table>
</div>
<div class="space20 space-left-10">
    <p>Remember to <strong>delete</strong> oc-installer folder.</p>
</div>
<a target="_blank" href="../oc-admin/index.php" class="button">Finish and go to the administration panel</a>
<div class="space20"></div>
<?php
    }
?>
