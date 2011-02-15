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

define( 'ABS_PATH', dirname(dirname(dirname(__FILE__))) . '/' );

//require_once ABS_PATH . 'common.php';
require_once ABS_PATH . 'oc-includes/osclass/db.php';
require_once ABS_PATH . 'oc-includes/osclass/classes/DAO.php';
require_once ABS_PATH . 'oc-includes/osclass/model/Preference.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hPreference.php';
require_once ABS_PATH . 'oc-includes/osclass/helpers/hErrors.php';
require_once ABS_PATH . 'oc-includes/osclass/install-functions.php';

( isset($_REQUEST['step']) ) ? $step = (int) $_REQUEST['step'] : $step = '1' ;

if( is_osclass_installed( ) ) {
    $message = 'You appear to have already installed OSClass. To reinstall please clear your old database tables first.' ;
    osc_die('OSClass &raquo; Error', $message) ;
}

switch ($step) {
    case 1:
        $requirements = get_requirements() ;
        $error = check_requirements($requirements) ;
        break;
    case 2:
        if( isset($_REQUEST['save_stats']) ) {
            setcookie('osclass_save_stats', 1) ;
            header('Location: '. get_absolute_url() . 'oc-includes/osclass/install.php?step=2') ;
        }
        break;
    case 3:
        if( isset($_POST['dbname']) )
            $error = oc_install();
        break;
    case 5:
        
        break;
    default:
        break;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>OSClass Installation</title>
        <script src="<?php echo get_absolute_url(); ?>oc-includes/js/jquery-1.4.2.js" type="text/javascript"></script>
        <script src="<?php echo get_absolute_url(); ?>oc-includes/js/jquery-ui-1.8.5.js" type="text/javascript"></script>
        <script src="<?php echo get_absolute_url(); ?>oc-includes/js/vtip/vtip.js" type="text/javascript"></script>
        <script src="<?php echo get_absolute_url(); ?>oc-includes/js/jquery.jsonp.js" type="text/javascript"></script>
        <script src="<?php echo get_absolute_url(); ?>oc-includes/js/install.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_absolute_url(); ?>oc-includes/css/install.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_absolute_url(); ?>oc-includes/js/vtip/css/vtip.css" />
    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div id="header" class="installation">
                    <h1 id="logo">
                        <img src="<?php echo get_absolute_url(); ?>oc-includes/images/osclass-logo.png" alt="OSClass" title="OSClass"/>
                    </h1>
                    <?php if(in_array($step, array(2,3,4))) { ?>
                    <ul id="nav">
                        <li class="<?php if($step == 2) { ?>actual<?php } elseif($step < 2) { ?>next<?php } else { ?>past<?php }?>">1 - Database</li>
                        <li class="<?php if($step == 3) { ?>actual<?php } elseif($step < 3) { ?>next<?php } else { ?>past<?php }?>">2 - Target</li>
                        <li class="<?php if($step == 4) { ?>actual<?php } elseif($step < 4) { ?>next<?php } else { ?>past<?php }?>">3 - Categories</li>
                    </ul>
                    <div class="clear"></div>
                    <?php } ?>
                </div>
                <div id="content">
                <?php if($step == 1) { ?>
                    <h2 class="target">Welcome</h2>
                    <form action="install.php" method="POST">
                        <div class="form-table">
                        <?php if($error) { ?>
                            <p>Check the next requirements:</p>
                        <?php } else { ?>
                            <p>All right! All the requirements have met:</p>
                        <?php } ?>
                            <ul>
                            <?php foreach($requirements as $k => $v) { ?>
                                <li><?php echo $k; ?> <img src="<?php echo get_absolute_url(); ?>oc-includes/images/<?php echo $v ? 'tick.png' : 'cross.png'; ?>" alt="" title="" /></li>
                            <?php } ?>
                            </ul>
                            <div class="more-stats">
                                <input type="checkbox" name="save_stats" id="save_stats" checked="checked" value="1"/>
                                <input type="hidden" name="step" value="2" />
                                <label for="save_stats">
                                    <b>Optional:</b> Help make OSClass better by automatically sending usage statistics and crash reports to OSClass.
                                </label>
                            </div>
                        </div>
                        <?php if($error) { ?>
                        <p class="margin20">
                            <input type="button" class="button" onclick="document.location = 'install.php?step=1'" value="Try again" />
                        </p>
                        <?php } else { ?>
                        <p class="margin20">
                            <input type="submit" class="button" value="Run the install" />
                        </p>
                    <?php } ?>
                    </form>
                <?php } elseif($step == 2) {
                         display_database_config();
                    } elseif($step == 3) {
                        if( !isset($error["error"]) ) {
                            display_target();
                        } else {
                            display_database_error($error, ($step - 1));
                        }
                    } elseif($step == 4) {
                        display_categories();
                    } elseif($step == 5) {
                        display_finish();
                    }
                ?>
                </div>
                <div id="footer">
                    <ul>
                        <li>
                            <a href="<?php echo get_absolute_url(); ?>readme.php" target="_blank">Readme</a>
                        </li>
                        <li>
                            <a href="http://osclass.org/contact/" target="_blank">Feedback</a>
                        </li>
                        <li>
                            <a href="http://forums.osclass.org/index.php" target="_blank">Forums</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
