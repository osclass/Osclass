<?php
// item 
$array_set1 = array(
    'dt_pub_date'           => date('Y-m-d H:i:s'),
    'fk_i_category_id'      => 1,
    'i_price'               => '1000',
    'fk_c_currency_code'    => 'USD',
    's_contact_name'        => 'contact name 1',
    's_contact_email'       => 'contact1@email.com',
    's_secret'              => osc_genRandomPassword(),
    'b_active'              => 1,
    'b_enabled'             => 1,
    'b_show_email'          => 0
);

$array_set1['s_secret'] = osc_genRandomPassword();
$array_set1['i_price']  = '200';
$array_set1['fk_i_category_id']  = 2;

$title1       = "Title ad 1";
$description1 = "Description ad 1 keywords moto , forums , osclass";
$what1        = $title1." ".$description1 ;
?>