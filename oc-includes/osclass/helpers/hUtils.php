<?php

    function osc_show_widgets($location) {
        $widgets = Widget::newInstance()->findByLocation($location);
        foreach ($widgets as $w)
            echo $w['s_content'] ;
    }

    /**
     * @return true if the item has uploaded a thumbnail.
     */
    //osc_itemHasThumbnail
    function osc_item_has_thumbnail($item) {
        $conn = getConnection() ;
        $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
        return!is_null($resource) ;
    }

    /**
     * Formats the price using the appropiate currency.
     */
    //osc_formatPrice
    function osc_format_price($item) {
        if (!isset($item['f_price']))
            return __('Check with seller');

        if ($item['f_price'] == 0)
            return __('Free');

        if (!empty($item['f_price']))
            return sprintf('%.02f %s', $item['f_price'], $item['fk_c_currency_code']);

        return __('Check with seller');
    }

    /**
     * Formats the date using the appropiate format.
     */
    //osc_formatDate
    function osc_format_date($item) {
        $date = strtotime($item['dt_pub_date']) ;
        return date(osc_date_format(), $date) ;
    }

    /**
     * Prints the user's account menu
     *
     * @param array with options of the form array('name' => 'display name', 'url' => 'url of link')
     *
     * @return void
     */
    function osc_private_user_menu($options = null)
    {
        if($options == null) {
            $options = array();
            $options[] = array('name' => __('Dashboard'), 'url' => osc_user_dashboard_url()) ;
            $options[] = array('name' => __('Manage your items'), 'url' => osc_user_list_items_url()) ;
            $options[] = array('name' => __('Manage your alerts'), 'url' => osc_user_alerts_url()) ;
            $options[] = array('name' => __('My account'), 'url' => osc_user_profile_url()) ;
            $options[] = array('name' => __('Logout'), 'url' => osc_user_logout_url()) ;
        }

        echo '<script type="text/javascript">' ;
            echo '$(".user_menu > :first-child").addClass("first") ;' ;
            echo '$(".user_menu > :last-child").addClass("last") ;' ;
        echo '</script>' ;
        echo '<ul class="user_menu">' ;

            $var_l = count($options) ;
            for($var_o = 0 ; $var_o < $var_l ; $var_o++) {
                echo '<li><a href="' . $options[$var_o]['url'] . '" >' . $options[$var_o]['name'] . '</a></li>' ;
            }

            osc_run_hook('user_menu') ;

        echo '</ul>' ;
    }
    
    /**
     * Prints a select with al the categories
     *
     * @param select_name name of the select (optional)
     *
     * @param selected category's ID (optional)
     *
     * @return void
     */
    function osc_categories_select($select_name = "categories", $selected = null)
    {
        echo '<select name="'.$select_name.'" id="'.$select_name.'">
                <option value="">'.__("Select a category").'</option>' ;
        $categories = Category::newInstance()->toTree();
        osc_subcategories_select($categories, $selected, 0);
        echo '</select>' ;
        return true ;
    }

    /**
     * Prints a select with al the categories
     *
     * @param categories (optional)
     *
     * @param selected category's ID (optional)
     *
     * @param deep how deep is the option (optional)
     *
     * @return void
     */    
    function osc_subcategories_select($categories, $selected = null, $deep = 0)
    {
        $deep_string = "";
        for($var = 0;$var<$deep;$var++) {
            $deep_string .= '-';
        }
        $deep++;
        foreach($categories as $c) {
            echo '<option value="' . $c['pk_i_id'] . '"' . ( ($selected == $c['pk_i_id']) ? 'selected="selected"' : '' ) . '>' . $deep_string.$c['s_name'] . '</option>' ;
            if(isset($c['categories']) && is_array($c['categories'])) {
                osc_subcategories_select($c['categories'], $selected, $deep+1);
            }
        }
    }
    
    /**
     * Prints a select with al the countries
     *
     * @param select_name name of the select (optional)
     *
     * @param selected country's ID (optional)
     *
     * @return void
     */
    function osc_countries_select($select_name = "categories", $selected = null)
    {
        echo '<select name="'.$select_name.'" id="'.$select_name.'">
                <option value="">'.__("Select a country").'</option>' ;
        $countries = Country::newInstance()->listAll();
        foreach($countries as $c) {
            echo '<option value="' . $c['pk_c_code'] . '"' . ( ($selected == $c['pk_c_code']) ? 'selected="selected"' : '' ) . '>' . $c['s_name'] . '</option>' ;
        }
        echo '</select>' ;
        return true ;
    }


  

?>
