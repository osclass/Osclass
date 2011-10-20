<?php
// item 1
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

$title1       = "Title ad 1";
$description1 = "Description ad 1 keywords moto , forums , osclass";
$what1        = $title1." ".$description1 ;

// item 2
$array_set2 = array(
    'dt_pub_date'           => date('Y-m-d H:i:s'),
    'fk_i_category_id'      => 2,
    'i_price'               => '2000',
    'fk_c_currency_code'    => 'USD',
    's_contact_name'        => 'contact name 1',
    's_contact_email'       => 'contact1@email.com',
    's_secret'              => osc_genRandomPassword(),
    'b_active'              => 1,
    'b_enabled'             => 1,
    'b_show_email'          => 0
);

$title2       = "Title ad 2";
$description2 = "Description ad 2 keywords moto , osclass";
$what2        = $title2." ".$description2 ;

?>