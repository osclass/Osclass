<?php
if(file_exists(dirname(__FILE__)."/custom_config.php")) {
    // ALLOW OVERRIDE OF CONFIGURATION
    require dirname(__FILE__)."/custom_config.php";
} else {
    // selenium config
    //$browser = "*googlechrome";
    $browser = "*firefox";
    $speed = '150';


    // Installer database configuration
    $db_host = "localhost";
    $db_name = "osclass";
    $db_user = "root";
    $db_pass = "";

    // test config
    $email      = "testing@osclass.org";
    $password   = "12345678";
    // test admin config
    $email_admin    = $email;
    $password_admin = 'password';
    
    $https = 'off';
    $host = 'localhost';
    
    
    $talker_room = "";
    $talker_token = "";

    $entry_point = "http://localhost/";
    
};
?>
