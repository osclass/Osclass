<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_seo extends FrontendTest {


    function returnStatusCode($url)
    {
        $header = get_headers($url, 1);
        $aux_headers = $header;
        error_log("--- " . print_r($aux_headers, true) );
        $aux = explode(' ', $aux_headers[0]);
        return $aux[1];
    }

    /*
     * Insert items, no user.
     *  - No validation, no user can post, no wait time
     *  - With validation.
     *  - Can't post items
     */
    function testItems_noUser()
    {
        error_log('--------------------> init test');
        if(osc_rewrite_enabled()) {
            error_log('rewrite ok');
        } else {
            error_log('no rewrite');
        }
        require 'ItemData.php';
        $item = $aData[0];
        $uSettings = new utilSettings();
        $items_wait_time                  = $uSettings->set_items_wait_time(0);
        $set_selectable_parent_categories = $uSettings->set_selectable_parent_categories(1);
        $bool_reg_user_post               = $uSettings->set_reg_user_post(0);
        $bool_enabled_user_validation     = $uSettings->set_moderate_items(-1);

        $this->insertItem($item['parentCatId'], $item['catId'], $item['title'],
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'],
                                $item['photo'], $item['contactName'],
                                $this->_email);


        $item_id = $this->_lastItemId();
        $this->assertTrue($this->selenium->isTextPresent("Your listing has been published"),"Items, insert item, no user, no validation.") ;

        $uSettings->set_items_wait_time($items_wait_time);
        $uSettings->set_selectable_parent_categories($set_selectable_parent_categories);
        $uSettings->set_reg_user_post($bool_reg_user_post);
        $uSettings->set_moderate_items($bool_enabled_user_validation);

        unset($uSettings);


        sleep(2);
        // get url from homepage
        $this->selenium->open( osc_base_url() );
        $xpath      = "xpath=//div[@class='latest_ads']/table/tbody/tr[1]/td[@class='text']/h3/a[1]@href";
        $item_url   = $this->selenium->getAttribute($xpath);

        error_log('url --- ' . $item_url);

        /*
         * visit item -> status 200
         */
        // block item
        $item = Item::newInstance()->findByPrimaryKey($item_id);

        $ia = new ItemActions(false);
        $ia->deactivate($item_id, $item['s_secret']);
        echo $this->returnStatusCode($item_url)."|<br/>";

//        /*
//         * Remove all items inserted previously
//         */
//        $aItem = Item::newInstance()->listAll('s_contact_email = '.$this->_email." AND fk_i_user IS NULL");
//        foreach($aItem as $item){
//            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
//            $this->selenium->open( $url );
//            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
//        }
    }
}
?>