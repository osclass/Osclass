<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

/**
 * @todo test lastest items
 * 
 */

class Frontend_search extends FrontendTest {
    
    function __construct($label = false) {
        parent::__construct($label);
    }

    /*
     * Load items for test propouse.
     */
    function testLoadItems()
    {
        // insert items for test
        require 'ItemData.php';
        $uSettings = new utilSettings();
        $old_reg_user_port          = $uSettings->set_reg_user_post(0);
        $old_items_wait_time        = $uSettings->set_items_wait_time(0);
        $old_enabled_recaptcha_items = $uSettings->set_enabled_recaptcha_items(0);
        $old_moderate_items         = $uSettings->set_moderate_items(-1);
        
        foreach($aData as $item){
            $this->insertItem(  $item['parentCatId'], $item['catId'], $item['title'],
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'],  $item['cityArea'],
                                $item['photo'], $item['contactName'], 
                                $this->_email);
            
            // ------
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published","Insert item.") );
        }
        
        $uSettings->set_reg_user_post( $old_reg_user_port );
        $uSettings->set_items_wait_time( $old_items_wait_time );
        $uSettings->set_enabled_recaptcha_items( $old_enabled_recaptcha_items );
        $uSettings->set_moderate_items( $old_moderate_items );
        
        unset($uSettings);
    }
    
    /*
     * Order results by Newly
     */
    function testNewly()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->click("link=Newly listed");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : SPANISH LESSONS
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        $this->assertTrue(preg_match('/SPANISH LESSONS/i', $text), "Search, order by Newly");
    }
    
    /*
     * Order results by Lower price
     */
    function testLowerPrice()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->click("link=Lower price first");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : German Training Coordination Agent (Barcelona centre) en Barcelona
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        sleep(4);
        $this->assertTrue(preg_match('/German Training Coordination Agent \(Barcelona centre\) en Barcelona/', $text), "Search, order by Lower");
    }
    
    /*
     * Order results by Higher price
     */
    function testHigherPrice()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->click("link=Higher price first");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : Avion ULM TL96 cerca de Biniagual
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        sleep(4);
        $this->assertTrue(preg_match('/Avion ULM TL96 cerca de Biniagual/', $text), "Search, order by Higher "); 
    }
    
    /*
     * Search by pattern: Moto
     */
    function testSPattern()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->type("sPattern", "Moto");
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 4 , "Search by sPattern.");
    }

    /*
     * Search by pattern & pMin - pMax 
     */
    function testSPatternCombi1()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->type("sPattern", "Moto");
        $this->selenium->type("sPriceMin", "3000");
        $this->selenium->type("sPriceMax", "9000");
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        sleep(4);
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 2 , "Search by sPattern & pMin - pMax.");
    }

    /*
     * Search by pattern & sCity
     */
    function testSPatternCombi2()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->type("sPattern", "Moto");
        $this->selenium->type("sCity" , "Balsareny");
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 3 , "Search by Moto + sCity = Balsareny.");
    }

    /*
     * Search by sCity
     */
    function testSPatternCombi3()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->type("sCity" , "Balsareny");
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 4 , "Search by sCity = Balsareny.");
    }

    /*
     * Search by category "Classes"
     */
    function testSPatternCombi4()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search" );
        $this->selenium->click("xpath=//div[@id='cat2_']"); // deselect category 2 (vehicles)
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        sleep(4);
        $this->assertTrue($count == 3 , "Search by sCategory = Classes.");
    }

    /*
     * Search by, only items with pictures
     */
    function testSPatternCombi5()
    {
        $this->selenium->open( osc_search_url() );
        $this->selenium->click("xpath=//input[@id='withPicture']"); // only items with pictures
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 9 , "Search by [ Show only items with pictures ].");
    }
    
    /*
     * Search by userId
     * Prepare new user and add new items
     */
    function testSearchUserItems()
    {
        require 'ItemData.php';
        $uSettings = new utilSettings();
        $old_enable_user_val  = $uSettings->set_enabled_user_validation(0);
        // create a new user
        $userId = $this->doRegisterUser('testusersearch@osclass.org', 'password');
        // add new items to user
        $this->loginWith('testusersearch@osclass.org', 'password');
        for($i=0; $i<2; $i++){
            $item = $aData[$i];
            $this->insertItem(  $item['parentCatId'], $item['catId'], $item['title'],
                                $item['description'], $item['price'],
                                $item['regionId'], $item['cityId'], $item['cityArea'], 
                                $item['photo'], $item['contactName'], 
                                $this->_email);
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been published", "Insert item.") );
        }
        
        $uSettings->set_enabled_user_validation( $old_enable_user_val );
        
        // check search
        $this->selenium->open( osc_search_url(array('sUser' => $userId)) );
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        
        $this->assertTrue($count == 2 , "Search by [ User id ].");
        
        // remove user test
        $this->removeUserByMail('testusersearch@osclass.org');
    }
    
    /*
     * Search test sCountry - sRegion - sCity - sCityArea
     */
    function testLocations()
    {
        $searchCountry  = osc_search_url(array('sCountry'   => 'ES'));
        $this->selenium->open( $searchCountry );
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 10 , "Search by [ sCountry es ].");
        
        $searchRegion   = osc_search_url(array('sRegion'    => 'Alicante'));
        $this->selenium->open( $searchRegion );
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 3 , "Search by [ sRegion Alicante ].");
        
        $searchCity     = osc_search_url(array('sCity'      => 'Balsareny'));
        $this->selenium->open( $searchCity );
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 4 , "Search by [ sCity Balsereny ].");
        
        $searchCityArea = osc_search_url(array('sCityArea'  => 'city area test')); 
        //echo $searchCityArea."<br>"; 
        $this->selenium->open( $searchCityArea );
        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 2 , "Search by [ sCityArea city area test ].");
    }
    
    /*
     * Create alert with search params
     */
    function testCreateAlert()
    {
        $this->_createAlert('foobar@invalid_email', false);
        
        $this->_createAlert($this->_email);
        
        Alerts::newInstance()->delete(array('s_email' => $this->_email));
    }
    
    /*
     *  1) expire one category
     *  2) update dt_pub_date 
     *  3) run cron.hourly.php manualy (update values)
     *  4) asserts
     *      frontend
     *      backoffice
     *      search
     */
    function testExpiredItems()
    {
        // expire one category (Language Classes)
        $mCategory = new Category();
        $mCategory->update(array('i_expiration_days' => '1') , array('pk_i_id' => '39') );
        // update dt_pub_date
        $mItems = new Item();
        $aItems = $mItems->listWhere('fk_i_category_id = 39');
        foreach($aItems as $actual_item) {
            //echo "update -> " . $actual_item['pk_i_id'] ."<br>";
            $mItems->update( array('dt_expiration' => '2010-05-05 10:00:00', 'dt_pub_date' => '2010-05-03 10:00:00') , array('pk_i_id' => $actual_item['pk_i_id']) );
        }

        Cron::newInstance()->update(array('d_last_exec' => '0000-00-00 00:00:00', 'd_next_exec' => '0000-00-00 00:00:00'), array('e_type' => 'DAILY'));
        
        $this->selenium->open( osc_base_url(true) . "?page=cron" ); 
        $this->selenium->waitForPageToLoad("3000");

        // tests
        // _testMainFrontend();
        $this->selenium->open( osc_base_url() );
        $this->assertTrue($this->selenium->isTextPresent("Classes (0)"), "Main frontend - category parent of category id 39 have bad counters ERROR" );
        $this->assertTrue($this->selenium->isTextPresent("Language Classes (0)"), "Main frontend - category 'Language Classes' (id 39) have bad counters ERROR" );
        // _testSearch();
        $searchCategory = osc_search_url(array('sCategory'  => '3'));
        $this->selenium->open( $searchCategory );
        $this->assertTrue($this->selenium->isTextPresent("There are no results matching"), "search frontend - there are items ERROR" );
    }
    
    /*
     * Remove all items inserted previously
     */
    function testRemoveLoadedItems()
    {
        $aItems = Item::newInstance()->findByEmail($this->_email) ;
        foreach( $aItems as $item ) {
            $url = osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] );
            //echo $url."<br>";
            $this->selenium->open( $url );
            $this->assertTrue($this->selenium->isTextPresent("Your listing has been deleted"), "Delete item.");
        }
    }
    
}
?>
