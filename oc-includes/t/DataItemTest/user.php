<?php
// user 1
$secret = osc_genRandomPassword() ;
$pass_secret = osc_genRandomPassword() ;
$array_set_user = array(
    's_name'        => 'user name',
    's_password'    => 'password',
    's_secret'      => $secret,
    'dt_reg_date'   => date('Y-m-d H:i:s'),
    's_email'       => 'test@email.com',
    's_pass_code'   => $pass_secret
);
?>
