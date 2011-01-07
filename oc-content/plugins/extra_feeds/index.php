<?php
/*
Plugin Name: Extra feeds
Plugin URI: http://www.osclass.org/
Description: Extra feeds.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Short Name: extra_feeds
*/
global $preferences;

function feed_indeed($items) {
    global $preferences;
    $items = $items[0];

    echo '<?xml version="1.0" encoding="utf-8"?>
    <source>
    <publisher>'.$preferences["pageTitle"].'</publisher>
    <publisherurl>'.ABS_WEB_URL.'</publisherurl>
    <lastBuildDate>'.date("D, j M Y G:i:s T").'</lastBuildDate>';

    foreach($items as $item) {
        $item = feed_get_job_data($item);

        $salary = "";
        if(isset($item['i_salary_min']) && $item['i_salary_min']!='') {
            $salary = $item['i_salary_min'];
        }
        if(isset($item['i_salary_max']) && $item['i_salary_max']!='') {
            if($salary!="") { $salary .= ' - '; };
            $salary .= $item['i_salary_max'];
        }
        if(isset($item['e_salary_period']) && $item['e_slary_period']!='') {
            if($salary!="") {
                $salary .= ' ';
                $salary .= $item['e_salary_period'];
            }
        }

        $locale = current($item['locale']);
        if(isset($locale['s_desired_exp']) && $locale['s_desired_exp']!='') {
            $experience = $locale['s_desired_exp'];
        } else {
            $experience = '';
        }
        if(isset($locale['s_studies']) && $locale['s_studies']!='') {
            $education = $locale['s_studies'];
        } else {
            $education = '';
        }

        echo '<job>
        <title><![CDATA['.$item['s_title'].']]></title>
        <date><![CDATA['.$item['dt_pub_date'].']]></date>
        <referencenumber><![CDATA['.$item['pk_i_id'].']]></referencenumber>
        <url><![CDATA['.osc_createItemURL($item, true).']]></url>
        <company><![CDATA['.((isset($item['s_company_name']) && $item['s_company_name']!=NULL)?$item['s_company_name']:'').']]></company>
        <city><![CDATA['.(($item['s_city']!=NULL)?$item['s_city']:'').']]></city>
        <state><![CDATA['.(($item['s_region']!=NULL)?$item['s_region']:'').']]></state>
        <country><![CDATA['.(($item['s_country']!=NULL)?$item['s_country']:'').']]></country>
        <postalcode><![CDATA['.(($item['s_zip']!=NULL)?$item['s_zip']:'').']]></postalcode>
        <description><![CDATA['.(($item['s_description']!=NULL)?$item['s_description']:'').']]></description>
        <salary><![CDATA['.$salary.']]></salary>
        <education><![CDATA['.$education.']]></education>
        <jobtype><![CDATA['.((isset($item['e_position_type']) && $item['e_position_type']!=NULL)?$item['e_position_type']:'').']]></jobtype>
        <category><![CDATA[]]></category>
        <experience><![CDATA['.$experience.']]></experience>
        </job>';
    }
    echo '</source>';

}

function feed_trovit_houses($items) {
    $items = $items[0];
    global $preferences;
    echo '<?xml version="1.0" encoding="utf-8"?>
            <trovit>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_house_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
            $time = $tmp[4].":".$tmp[5];
        }

        echo '<ad>
            <id><![CDATA['.$item['pk_i_id'].']]></id>
            <url><![CDATA['.osc_createItemURL($item, true).']]></url>
            <title><![CDATA['.$item['s_title'].']]></title>
            <type><![CDATA['.((isset($item['e_type']))?$item['e_type']:'').']]></type>

            <agency><![CDATA['.((isset($item['s_agency']))?$item['s_agency']:'').']]></agency>
            <content><![CDATA['.$item['s_description'].']]></content>

            <price><![CDATA['.$item['f_price'].']]></price>
            <property_type><![CDATA['.((isset($item['property_type']))?$item['property_type']:'').']]></property_type>
            <floor_area unit="meters"><![CDATA['.((isset($item['s_square_meters']))?$item['s_square_meters']:'').']]></floor_area>
            <rooms><![CDATA['.((isset($item['i_num_rooms']))?$item['i_num_rooms']:'').']]></rooms>

            <bathrooms><![CDATA['.((isset($item['i_num_bathrooms']))?$item['i_num_bathrooms']:'').']]></bathrooms>
            <parking><![CDATA['.((isset($item['b_parking']))?$item['b_parking']:'0').']]></parking>

            <address><![CDATA['.((isset($item['s_address']))?$item['s_address']:'').']]></address>
            <city><![CDATA['.((isset($item['s_city']))?$item['s_city']:'').']]></city>

            <city_area><![CDATA['.((isset($item['s_city_area']))?$item['s_city_area']:'').']]></city_area>
            <postcode><![CDATA['.((isset($item['s_zip']))?$item['s_zip']:'').']]></postcode>
            <region><![CDATA['.((isset($item['s_region']))?$item['s_region']:'').']]></region>

            <latitude><![CDATA['.((isset($item['d_coord_lat']))?$item['d_coord_lat']:'').']]></latitude>
            <longitude><![CDATA['.((isset($item['d_coord_lond']))?$item['d_coord_long']:'').']]></longitude>

            <floor_number><![CDATA['.((isset($item['i_floor_number']))?$item['i_floor_number']:'').']]></floor_number>
            <plot_area><![CDATA['.((isset($item['i_plot_area']))?$item['i_plot_area']:'').']]></plot_area>
            <is_furnished><![CDATA['.((isset($item['b_furnished']))?$item['b_furnished']:'0').']]></is_furnished>
            <is_new><![CDATA['.((isset($item['b_new']))?$item['b_new']:'0').']]></is_new>
            <condition><![CDATA['.((isset($item['s_condition']))?$item['s_condition']:'').']]></condition>
            <year><![CDATA['.((isset($item['i_year']))?$item['i_year']:'').']]></year>
            <by_owner><![CDATA['.((isset($item['b_by_owner']))?$item['b_by_owner']:'0').']]></by_owner>';

        $res_string = '';
        foreach($resources as $res) {
            if(strpos($res['s_content_type'], 'image')!==FALSE) {
                $res_string .= '<picture>
                                    <picture_url><![CDATA['.ABS_WEB_URL."/".str_replace('_thumbnail', '', $res['s_path']).']]></picture_url>
                                    <picture_title><![CDATA['.$res['s_name'].']]></picture_title>
                                </picture>';
            }
        }

        if($res_string!='') {
            echo '<pictures>'.$res_string.'</pictures>';
        }
            
        echo '
            <date><![CDATA['.$date.']]></date>
            <time><![CDATA['.$time.']]></time>
        </ad>';
    }

    echo '</trovit>';
}

function feed_trovit_products($items) {
    $items = $items[0];

    global $preferences;

    echo '<?xml version="1.0" encoding="utf-8"?>
            <trovit>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_product_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
            $time = $tmp[4].":".$tmp[5];
        }

        echo '<ad>
                <id><![CDATA['.$item['pk_i_id'].']]></id>
                <url><![CDATA['.osc_createItemURL($item, true).']]></url>
                <title><![CDATA['.$item['s_title'].']]></title>

                <content><![CDATA['.$item['s_description'].']]></content>

                <price><![CDATA['.$item['f_price'].']]></price>

                <make><![CDATA['.((isset($item['s_make']))?$item['s_make']:'').']]></make>
                <model><![CDATA['.((isset($item['s_model']))?$item['s_model']:'').']]></model>
                <category><![CDATA['.((isset($item['s_category']))?$item['s_category']:'').']]></category>

                <address><![CDATA['.((isset($item['s_address']))?$item['s_address']:'').']]></address>
                <city><![CDATA['.((isset($item['s_city']))?$item['s_city']:'').']]></city>

                <city_area><![CDATA['.((isset($item['s_city_area']))?$item['s_city_area']:'').']]></city_area>
                <postcode><![CDATA['.((isset($item['s_zip']))?$item['s_zip']:'').']]></postcode>
                <region><![CDATA['.((isset($item['s_region']))?$item['s_region']:'').']]></region>

                <latitude><![CDATA['.((isset($item['d_coord_lat']))?$item['d_coord_lat']:'').']]></latitude>
                <longitude><![CDATA['.((isset($item['d_coord_lond']))?$item['d_coord_long']:'').']]></longitude>';


        $res_string = '';
        foreach($resources as $res) {
            if(strpos($res['s_content_type'], 'image')!==FALSE) {
                $res_string .= '<picture>
                                    <picture_url><![CDATA['.ABS_WEB_URL."/".str_replace('_thumbnail', '', $res['s_path']).']]></picture_url>
                                    <picture_title><![CDATA['.$res['s_name'].']]></picture_title>
                                </picture>';
            }
        }

        if($res_string!='') {
            echo '<pictures>'.$res_string.'</pictures>';
        }
            
        echo '
            <date><![CDATA['.$date.']]></date>
            <time><![CDATA['.$time.']]></time>
        </ad>';
    }

    echo '</trovit>';
}

function feed_trovit_jobs($items) {
    global $preferences;

    $items = $items[0];
    echo '<?xml version="1.0" encoding="utf-8"?>
            <trovit>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_job_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
            $time = $tmp[4].":".$tmp[5];
        }

        echo '<ad>
                <id><![CDATA['.$item['pk_i_id'].']]></id>
                <url><![CDATA['.osc_createItemURL($item, true).']]></url>
                <title><![CDATA['.$item['s_title'].']]></title>

                <content><![CDATA['.$item['s_description'].']]></content>

                <address><![CDATA['.((isset($item['s_address']))?$item['s_address']:'').']]></address>
                <city><![CDATA['.((isset($item['s_city']))?$item['s_city']:'').']]></city>

                <city_area><![CDATA['.((isset($item['s_city_area']))?$item['s_city_area']:'').']]></city_area>
                <postcode><![CDATA['.((isset($item['s_zip']))?$item['s_zip']:'').']]></postcode>
                <region><![CDATA['.((isset($item['s_region']))?$item['s_region']:'').']]></region>

                <latitude><![CDATA['.((isset($item['d_coord_lat']))?$item['d_coord_lat']:'').']]></latitude>
                <longitude><![CDATA['.((isset($item['d_coord_lond']))?$item['d_coord_long']:'').']]></longitude>

                <salary><![CDATA['.((isset($item['i_salary_min']) && isset($item['i_salary_max']))?$item['i_salary_min'].' - '.$item['i_salary_max']:'').']]></salary>
                <company><![CDATA['.((isset($item['s_company_name']))?$item['s_company_name']:'').']]></company>
                <experience><![CDATA['.((isset($item['s_experience']))?$item['s_experience']:'').']]></experience>
                <requirements><![CDATA['.((isset($item['s_requirements']))?$item['s_requirements']:'').']]></requirements>
                <contract><![CDATA['.((isset($item['s_contract']))?$item['s_contract']:'').']]></contract>
                <category><![CDATA['.((isset($item['s_category']))?$item['s_category']:'').']]></category>';
        echo '
            <date><![CDATA['.$date.']]></date>
            <time><![CDATA['.$time.']]></time>
        </ad>';
    }

    echo '</trovit>';
}


function feed_trovit_cars($items) {
    global $preferences;
    
    $items = $items[0];

    echo '<?xml version="1.0" encoding="utf-8"?>
            <trovit>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_car_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
            $time = $tmp[4].":".$tmp[5];
        }

        echo '<ad>
                <id><![CDATA['.$item['pk_i_id'].']]></id>
                <url><![CDATA['.osc_createItemURL($item, true).']]></url>
                <title><![CDATA['.$item['s_title'].']]></title>

                <content><![CDATA['.$item['s_description'].']]></content>

                <price><![CDATA['.$item['f_price'].']]></price>

                <make><![CDATA['.((isset($item['s_make']))?$item['s_make']:'').']]></make>
                <model><![CDATA['.((isset($item['s_model']))?$item['s_model']:'').']]></model>
                <color><![CDATA['.((isset($item['s_color']))?$item['s_color']:'').']]></color>

                <mileage><![CDATA['.((isset($item['i_mileage']))?$item['i_mileage']:'').']]></mileage>
                <doors><![CDATA['.((isset($item['i_doors']))?$item['i_doors']:'').']]></doors>
                <fuel><![CDATA['.((isset($item['e_fuel']))?$item['e_fuel']:'').']]></fuel>
                <transmission><![CDATA['.((isset($item['e_transmission']))?$item['e_transmission']:'').']]></transmission>
                <engine_size><![CDATA['.((isset($item['i_engine_size']))?$item['i_engine_size']:'').']]></engine_size>
                <cylinders><![CDATA['.((isset($item['i_cylinders']))?$item['i_cylinders']:'').']]></cylinders>
                <power unit="'.((isset($item['e_power_unit']))?$item['e_power_unit']:'').'"><![CDATA['.((isset($item['i_power']))?$item['i_power']:'').']]></power>
                <seats><![CDATA['.((isset($item['i_seats']))?$item['i_seats']:'').']]></seats>
                <gears><![CDATA['.((isset($item['i_gears']))?$item['i_gears']:'').']]></gears>

                <address><![CDATA['.((isset($item['s_address']))?$item['s_address']:'').']]></address>
                <city><![CDATA['.((isset($item['s_city']))?$item['s_city']:'').']]></city>

                <city_area><![CDATA['.((isset($item['s_city_area']))?$item['s_city_area']:'').']]></city_area>
                <postcode><![CDATA['.((isset($item['s_zip']))?$item['s_zip']:'').']]></postcode>
                <region><![CDATA['.((isset($item['s_region']))?$item['s_region']:'').']]></region>

                <latitude><![CDATA['.((isset($item['d_coord_lat']))?$item['d_coord_lat']:'').']]></latitude>
                <longitude><![CDATA['.((isset($item['d_coord_lond']))?$item['d_coord_long']:'').']]></longitude>';


        $res_string = '';
        foreach($resources as $res) {
            if(strpos($res['s_content_type'], 'image')!==FALSE) {
                $res_string .= '<picture>
                                    <picture_url><![CDATA['.ABS_WEB_URL."/".str_replace('_thumbnail', '', $res['s_path']).']]></picture_url>
                                    <picture_title><![CDATA['.$res['s_name'].']]></picture_title>
                                </picture>';
            }
        }

        if($res_string!='') {
            echo '<pictures>'.$res_string.'</pictures>';
        }
            
        echo '
            <date><![CDATA['.$date.']]></date>
            <time><![CDATA['.$time.']]></time>
        </ad>';
    }
    
    echo '</trovit>';
}


function feed_google_jobs($items) {
    global $preferences;    
    $items = $items[0];
 
    echo '<rss version ="2.0" xmlns:g="http://base.google.com/ns/1.0"> 
     
    <channel> 
	    <title>'.$preferences["pageTitle"].'</title> 
	    <description>'.$preferences["pageDesc"].'</description> 
	    <link>'.ABS_WEB_URL.'</link>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_job_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
        }

       


        echo '<item> 
        <title>'.$item['s_title'].'</title> 
        <description>'.$item['s_description'].'</description> 
        <g:education>'.((isset($item['s_studies']))?$item['s_studies']:'').'</g:education> 
        <g:employer>'.((isset($item['s_company_name']))?$item['s_company_name']:'').'</g:employer> 
        <g:id>'.$item['pk_i_id'].'</g:id> 
        <g:job_industry>'.((isset($item['s_category']))?$item['s_category']:'').'</g:job_industry> 
        <g:job_type>'.((isset($item['s_contract']))?$item['s_contract']:'').'</g:job_type> 
        <link>'.osc_createItemURL($item).'</link> 
        <g:location>'.((isset($item['s_address']))?$item['s_address']:'').', '.((isset($item['s_city']))?$item['s_city']:'').', '.((isset($item['s_region']))?$item['s_region']:'').', '.((isset($item['s_zip']))?$item['s_zip']:'').' '.((isset($item['s_country']))?$item['s_country']:'').'</g:location> 
        <g:publish_date>'.$date.'</g:publish_date> 
        <g:salary>'.((isset($item['i_salary_min']) && isset($item['i_salary_max']))?$item['i_salary_min'].' - '.$item['i_salary_max']:'').'</g:salary> 
        </item>';
    }

    echo '</channel> 
    </rss>';

}


function feed_google_cars($items) {
    global $preferences;    
    $items = $items[0];
 
    echo '<rss version ="2.0" xmlns:g="http://base.google.com/ns/1.0"> 
     
    <channel> 
	    <title>'.$preferences["pageTitle"].'</title> 
	    <description>'.$preferences["pageDesc"].'</description> 
	    <link>'.ABS_WEB_URL.'</link>';

    foreach($items as $item) {
        $resources = Item::newInstance()->findResourcesByID($item['pk_i_id']);

        $item = feed_get_car_data($item);

        $date = date('d/m/Y');
        $time = date('H:i');

        if(preg_match('|([0-9]{4})-([0-9]{2})-([0-9]{2})|', $item['dt_pub_date'], $tmp)) {
            $date = $tmp[3]."/".$tmp[2]."/".$tmp[1];
        }

       


        echo '<item> 
        <title>'.$item['s_title'].'</title> 
        <description>'.$item['s_description'].'</description> 
        <g:id>'.$item['pk_i_id'].'</g:id> 
        <link>'.osc_createItemURL($item).'</link> 
        <g:location>'.((isset($item['s_address']))?$item['s_address']:'').', '.((isset($item['s_city']))?$item['s_city']:'').', '.((isset($item['s_region']))?$item['s_region']:'').', '.((isset($item['s_zip']))?$item['s_zip']:'').' '.((isset($item['s_country']))?$item['s_country']:'').'</g:location> 
        <g:publish_date>'.$date.'</g:publish_date> 
        <g:color>'.((isset($item['s_color']))?$item['s_color']:'').'</g:color> 
        <g:condition>'.((isset($item['b_new']) && $item['b_new']==1)?'new':'used').'</g:condition> 
        <g:image_link>'.ABS_WEB_URL."/".str_replace('_thumbnail', '', $res['s_path']).'</g:image_link> 
        <g:make>'.((isset($item['s_make']))?$item['s_make']:'').'</g:make> 
        <g:mileage>'.((isset($item['i_mileage']))?$item['i_mileage']:'').'</g:mileage> 
        <g:model>'.((isset($item['s_model']))?$item['s_model']:'').'</g:model> 
        <g:price>'.((isset($item['f_price']))?$item['f_price']:'').'</g:price> 
        <g:vehicle_type>'.((isset($item['s_name']))?$item['s_name']:'').'</g:vehicle_type> 
        <g:year>'.((isset($item['i_year']))?$item['i_year']:'').'</g:year>
        </item>';


        if($res_string!='') {
            echo '<pictures>'.$res_string.'</pictures>';
        }

    }

    echo '</channel> 
    </rss>';

}



function feed_get_house_data($item) {
    global $preferences;
    
    $conn = getConnection() ;
    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_house_attr WHERE fk_i_item_id = %d ", DB_TABLE_PREFIX, $item['pk_i_id']);
    foreach($detail as $k => $v) {
        $item[$k] = $v;
    }

    $detail = $conn->osc_dbFetchResult("SELECT s_name as property_type FROM %st_item_house_property_type_attr WHERE pk_i_id = %d AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $item['fk_i_property_type_id'], $preferences['language']);
    if(count($detail)==0) {
        $detail = $conn->osc_dbFetchResult("SELECT s_name as property_type FROM %st_item_house_property_type_attr WHERE pk_i_id = %d ", DB_TABLE_PREFIX, $item['fk_i_property_type_id']);
    }
    $item['property_type'] = $detail['property_type'];

    return $item;
}

function feed_get_car_data($item) {
    global $preferences;
    
    $conn = getConnection() ;
    $detail = $conn->osc_dbFetchResult("SELECT make.s_name as s_make, model.s_name as s_model, car.* FROM %st_item_car_attr as car, %st_item_car_make_attr as make, %st_item_car_model_attr as model WHERE car.fk_i_item_id = %d, make.pk_i_id = car.fk_i_make_id AND model.pk_i_id = car.fk_i_model_id ", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $item['pk_i_id']);
    foreach($detail as $k => $v) {
        $item[$k] = $v;
    }
    
    return $item;        
}


function feed_get_job_data($item) {
    global $preferences;

    $conn = getConnection() ;
    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_job_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
    foreach($detail as $k => $v) {
        $item[$k] = $v;
    }

    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_job_description_attr WHERE fk_i_item_id = %d AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $item['pk_i_id'], $preferences['language']);
    if(count($detail)==0) {
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_job_description_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
    }

    foreach($detail as $k => $v) {
        $item[$k] = $v;
    }

    return $item;        
}

function feed_get_product_data($item) {
    global $preferences;
    
    $conn = getConnection() ;
    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_item_products_attr WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $item['pk_i_id']);
    $item['s_make'] = $detail['s_make'];
    $item['s_model'] = $detail['s_model'];

    $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_category_description WHERE fk_i_category_id = %d AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $item['fk_i_category_id'], $preferences['language']);
    if(count($detail)>0) {
        $item['s_category'] = $detail['s_category'];
    } else {
        $detail = $conn->osc_dbFetchResult("SELECT * FROM %st_category_description WHERE fk_i_category_id = %d", DB_TABLE_PREFIX, $item['fk_i_category_id']);
        $item['s_category'] = $detail['s_category'];
    }

    return $item;        
}


// This is needed in order to be able to activate the plugin
osc_registerPlugin(__FILE__, '');
// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_addHook(__FILE__."_uninstall", '');

osc_addFilter('feed_indeed', 'feed_indeed');
osc_addFilter('feed_trovit_houses', 'feed_trovit_houses');
osc_addFilter('feed_trovit_jobs', 'feed_trovit_jobs');
osc_addFilter('feed_trovit_products', 'feed_trovit_products');
osc_addFilter('feed_trovit_cars', 'feed_trovit_cars');
osc_addFilter('feed_google_jobs', 'feed_google_jobs');
osc_addFilter('feed_google_cars', 'feed_google_cars');

?>
