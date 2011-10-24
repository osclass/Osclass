<?php
// item 3
$array_set3 = $array_set1;
unset($array_set3['fk_i_user_id']);

$array_set3['s_secret'] = osc_genRandomPassword();
$array_set3['i_price']  = '200';
$array_set3['fk_i_category_id']  = 2;

$title3       = "Title ad 3";
$description3 = "Description ad 3 keywords moto , forums , osclass";
$what3        = $title3." ".$description3 ;
?>
