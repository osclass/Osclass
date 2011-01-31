<?php
    define('IS_AJAX', 1);
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

    require_once  ABS_PATH . 'config.php';
    require_once  ABS_PATH . 'common.php';
    require_once  LIB_PATH . 'osclass/web.php';
    require_once  LIB_PATH . 'osclass/db.php';
    require_once  LIB_PATH . 'osclass/classes/DAO.php';
    require_once  LIB_PATH .'osclass/model/City.php';

    $cities = City::newInstance()->ajax($_REQUEST['term']);

    echo json_encode($cities);

?>
