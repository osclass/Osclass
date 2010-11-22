<?php
    require_once  '../../../common.php';
    require_once  APP_PATH.'/oc-includes/osclass/check.php';
    require_once  APP_PATH.'/config.php';
    require_once  LIB_PATH.'/osclass/web.php';
    require_once  LIB_PATH.'/osclass/db.php';
    require_once  LIB_PATH.'/osclass/classes/DAO.php';
    require_once  LIB_PATH.'/osclass/model/City.php';

    $cities = City::newInstance()->ajax($_REQUEST['term']);

    echo json_encode($cities);

?>
