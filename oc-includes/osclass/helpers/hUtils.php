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
     * Returns the URL to the thumbnail of the item passed by paramater.
     */
    function osc_create_item_thumbnail_url($item) {
        $conn = getConnection() ;
        $resource = $conn->osc_dbFetchResult('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']) ;
        echo osc_createThumbnailURL($resource) ;
    }

    /**
     * Formats the price using the appropiate currency.
     */
    function osc_format_price($item) {
        if (!isset($item['f_price']))
            return __('Consult') ;

        if ($item['f_price'] == 0)
            return __('Free') ;

        if (!empty($item['f_price']))
            return sprintf('%.02f %s', $item['f_price'], $item['fk_c_currency_code']) ;

        return __('Consult') ;
    }

    /**
     * Formats the date using the appropiate format.
     */
    function osc_formatDate($item) {
        $date = strtotime($item['dt_pub_date']) ;
        return date(osc_date_format(), $date) ;
    }

?>
