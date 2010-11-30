<?php

    require_once  '../../../common.php';
    require_once  APP_PATH.'/oc-includes/osclass/check.php';
    require_once  APP_PATH.'/config.php';
    require_once  LIB_PATH.'/osclass/web.php';
    require_once  LIB_PATH.'/osclass/db.php';
    require_once  LIB_PATH.'/osclass/classes/DAO.php';
    require_once  LIB_PATH.'/osclass/model/Alerts.php';

if(isset($_REQUEST['alert'])) {
    if(isset($_REQUEST['email']) && $_REQUEST['email']!='') {
        Alerts::newInstance()->insert(array( 's_email' => $_REQUEST['email'], 's_search' => $_REQUEST['alert'], 'e_type' => 'DAILY'));
        echo "1";
        return true;
    }
    echo '0';
    return false;
}

?>
