<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_seo extends FrontendTest {


    function returnStatusCode($url)
    {
        $header = get_headers($url);
        $aux_headers = $header;
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

        // get url from homepage
        $this->selenium->open( osc_base_url() );
        $xpath      = "xpath=(//div[@class='listing-basicinfo']/a)[1]@href";
        $item_url   = $this->selenium->getAttribute($xpath);

        /*
         * visit item -> status 200
         */
        // block item
        $item = Item::newInstance()->findByPrimaryKey($item_id);
        $ia = new ItemActions(false);

        // 200 OK ?
        $code = $this->returnStatusCode($item_url);
        $this->assertTrue($code=='200', 'Active, Enabled, Return code 200 OK');
        // 400
        $ia->deactivate($item_id, $item['s_secret']);
        $code = $this->returnStatusCode($item_url);
        $this->assertTrue($code=='400', 'NO Active, Enabled, Return code 400 OK');
        $ia->activate($item_id, $item['s_secret']);
        // 400
        $ia->disable($item_id);
        $code = $this->returnStatusCode($item_url);
        $this->assertTrue($code=='400', 'Active, NO Enabled, Return code 400 OK');
        $ia->enable($item_id);

        /*
         * Remove all items inserted previously
         */
        $aItem = Item::newInstance()->listAll('s_contact_email = '.$this->_email." AND fk_i_user IS NULL");
        foreach($aItem as $item){
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            $this->selenium->open( $url );
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
}
?>