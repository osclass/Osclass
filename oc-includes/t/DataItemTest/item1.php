<?php
// item 1
$array_set1 = array(
    'fk_i_user_id'          => self::$aInfo['userID'],
    'dt_pub_date'           => date('Y-m-d H:i:s'),
    'fk_i_category_id'      => 1,
    'i_price'               => '1000',
    'fk_c_currency_code'    => 'USD',
    's_contact_name'        => 'contact name 1',
    's_contact_email'       => 'contact1@email.com',
    's_secret'              => osc_genRandomPassword(),
    'b_active'              => 0,
    'b_enabled'             => 1,
    'b_show_email'          => 0
);

$title1       = "Title ad 1";
$description1 = "Description ad 1 keywords car , foobar, osclass";
$what1        = $title1." ".$description1 ;
?>
