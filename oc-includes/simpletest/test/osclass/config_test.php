<?php
if(file_exists(dirname(__FILE__)."/custom_config.php")) {
    print_r("EL FICHERO EXISTE");
    // ALLOW OVERRIDE OF CONFIGURATION
    require dirname(__FILE__)."/custom_config.php";
} else {
    print_r("EL FICHERO *NO* EXISTE");
    // selenium config
    //$browser = "*googlechrome";
    /*$browser = "*firefox";
    $speed = '150';


    // Installer database configuration
    $db_host = "localhost";
    $db_name = "osclass";
    $db_user = "root";
    $db_pass = "";

    // test config
    $email      = "carlos+tests@osclass.org";
    $password   = "12345678";
    // test admin config
    $email_admin    = $email;
    $password_admin = 'password';*/
};
?>
