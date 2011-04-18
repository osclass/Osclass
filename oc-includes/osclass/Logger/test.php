<?php


require_once './Logger.php';
require_once './LogOsclass.php';

LogOsclassInstaller::instance()->info('Cannot create the database. Error number: FOOBAR ' ,__FILE__.":".__FUNCTION__.":".__LINE__);


//LogOsclass::instance()->info('Cannot create the database. Error number: 0' , __FILE__." >> ".( (__FUNCTION__ == '') ? __FUNCTION__ : "-") ." >> ".__LINE__) ;


?>
