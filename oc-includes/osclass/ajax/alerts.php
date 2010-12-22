<?php
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

    require_once  ABS_PATH . 'common.php';
    require_once  ABS_PATH . 'config.php';
    require_once  LIB_PATH . 'osclass/web.php';
    require_once  LIB_PATH . 'osclass/db.php';
    require_once  LIB_PATH . 'osclass/classes/DAO.php';
    require_once  LIB_PATH . 'osclass/model/Alerts.php';

    if(isset($_REQUEST['alert'])) {
        if(isset($_REQUEST['email']) && $_REQUEST['email']!='') {
            Alerts::newInstance()->insert(array( 'fk_i_user_id' => $_REQUEST['userid'], 's_email' => $_REQUEST['email'], 's_search' => $_REQUEST['alert'], 'e_type' => 'DAILY'));
        echo "1";
        return true;
    }
    echo '0';
    return false;
}

?>
