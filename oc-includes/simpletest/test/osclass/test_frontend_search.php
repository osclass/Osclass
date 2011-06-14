<?php

error_reporting(E_ALL);
require_once('util_settings.php');

require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';



class TestOfSearch extends WebTestCase {

    private $selenium;
    private $mail;
    private $password;
    private $email_fixed;
    private $array;
    private $logged;
    
    private $email_items;
    
    private $enabled_recaptcha_items;
    private $items_wait_time;
    private $bool_enabled_user_validation;
    private $bool_reg_user_post;

    function setUp()
    {
        $conn = getConnection();

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }

    public function __construct($label = false) {
        
        parent::__construct($label);
        
        $uSettings = new utilSettings();
        $this->enabled_recaptcha_items      = $uSettings->set_enabled_recaptcha_items(0);
        $this->items_wait_time              = $uSettings->set_items_wait_time(0);
        $this->bool_enabled_user_validation = $uSettings->set_moderate_items(-1);
        $this->bool_reg_user_post           = $uSettings->set_reg_user_post(0);
        unset($uSettings);
        echo "inserting items...<br>";
    }

    /*           TESTS          */
    
    public function testInitial()
    {
        $email = "carlos@osclass.org" ;
        $this->email_items = $email;
        require 'itemData.php';
        require 'item_frontend.php';
        $itemFrontend = new ItemFrontend();
        echo "testInitial<br>";
        flush();
        foreach($aData as $item){
            echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - insertItem()</div>";
            flush();
            $itemFrontend->insertItem( $item['catId'], $item['title'], $item['description'], $item['price'], $item['regionId'], $item['cityId'], $item['photo'], $item['contactName'], $item['contactEmail'] , $this->selenium, $this, 0);
        }
    }

    public function testNewly()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testNewly</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testNewly </div>";
        $this->newly();
        flush();
    }

    public function testLowerPrice()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testLower</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testLower </div>";
        $this->lowerPrice();
        flush();
    }

    public function testHigherPrice()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testHigherPrice</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testHigherPrice </div>";
        $this->higherPrice();
        flush();
    }

    public function testSPattern()
    {
//        Moto
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPattern</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPattern </div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->type("sPattern", "Moto");
        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 6 , "There aren't 6 items filtered by Moto");
    }

    public function testSPatternCombi1()
    {
//        Moto
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPatternCombi1</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPatternCombi1 sPattern & pMin - pMax </div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->type("sPattern", "Moto");

        $this->selenium->type("sPriceMin", "3000");
        $this->selenium->type("sPriceMax", "9000");

        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 3 , "There aren't 3 items filtered by Moto + pMin - pMax (3000-9000)");
    }

    public function testSPatternCombi2()
    {
//        Moto
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPatternCombi2</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPatternCombi2 sPattern & sCity </div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->type("sPattern", "Moto");

        $this->selenium->type("sCity" , "Balsareny");

        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 3 , "There aren't 3 items filtered by Moto + sCity = Balsareny");
    }

    public function testSPatternCombi3()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPatternCombi3</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPatternCombi3 sCity </div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->type("sCity" , "Balsareny");

        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 4 , "There aren't 4 items filtered by sCity = Balsareny");
    }

    /**
     * Only category "Classes"
     */
    public function testSPatternCombi4()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPatternCombi4</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPatternCombi4 sCategory[]</div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->click("xpath=//input[@value='2']"); // deselect category 2 (vehicles)

        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 3 , "There aren't 3 items filtered by sCategory = Classes");
    }

    public function testSPatternCombi5()
    {
        echo "<div style='background-color: green; color: white;'><h2> TestOfSearch >> testSPatternCombi5</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> testSPatternCombi5 [ Show only items with pictures ]</div>";
        $this->selenium->open( osc_base_url(true) . "?page=search" );

        $this->selenium->click("bPic"); // only items with pictures

        $this->selenium->click("xpath=//span/button[text()='Apply']");
        $this->selenium->waitForPageToLoad("30000");

        $count = $this->selenium->getXpathCount('//table/tbody/tr/td[2]');
        $this->assertTrue($count == 9 , "There aren't 9 items filtered by [ Show only items with pictures ]");
    }

    /**
     *  1) expire one category
     *  2) update dt_pub_date 
     *  3) run cron.hourly.php manualy (update values)
     *  4) asserts
     *      frontend
     *      backoffice
     *      search
     *      
     */
    public function testExpiredItems()
    {
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - testExpiredItems </div>";
        // expire one category (Language Classes)
        $mCategory = new Category();
        $mCategory->update(array('i_expiration_days' => '1') , array('pk_i_id' => '39') );
        // update dt_pub_date
        $mItems = new Item();
        $aItems = $mItems->listWhere('fk_i_category_id = 39');
        foreach($aItems as $actual_item) {
            echo "update -> " . $actual_item['pk_i_id'] ."<br>";
            $mItems->update( array('dt_pub_date' => '2010-05-05 10:00:00') , array('pk_i_id' => $actual_item['pk_i_id']) );
        }

        $this->selenium->open( osc_base_url() . "oc-includes/osclass/cron.hourly.php" );
        $this->selenium->waitForPageToLoad("30000");

        // tests
        $this->_testMainFrontend();
        $this->_testSearch();
    }

    private function _testMainFrontend()
    {
        $this->selenium->open( osc_base_url(true) );
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - testExpiredItems - parent category counter</div>";
        $this->assertTrue($this->selenium->isTextPresent("Classes (0)"), "Main frontend - category parent of category id 39 have bad counters ERROR" );
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - testExpiredItems - category counter</div>";
        $this->assertTrue($this->selenium->isTextPresent("Language Classes (0)"), "Main frontend - category 'Language Classes' (id 39) have bad counters ERROR" );
    }
    
    private function _testSearch()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search&sCategory=3" );
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - testExpiredItems - search </div>";
        $this->assertTrue($this->selenium->isTextPresent("There are no results matching"), "search frontend - there are items ERROR" );
    }

    private function _testLatestItems()
    {
        $item = Item::newInstance()->findByConditions( array('s_contact_email' => $this->email_items) ) ;
        while( $item ) {
            if( $item['fk_i_category_id'] != '39') {
                echo "deleting item ... <br>";
                flush();
                $this->deleteItemUrl( osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] ) );
                flush();
                $item =Item::newInstance()->findByConditions( array('s_contact_email' => $this->email_items) ) ;
                flush();
            }
        }
        $this->selenium->open( osc_base_url(true) );
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - testExpiredItems - latest items</div>";
        $this->assertTrue($this->selenium->isTextPresent("No Latest Items"), "Main frontend latest items - there are items ERROR" );
    }

    public function testFinal()
    {
        $item = Item::newInstance()->findByConditions( array('s_contact_email' => $this->email_items) ) ;
        while( $item ) {
            echo "deleting item ... <br>";
            flush();
            $this->deleteItemUrl( osc_item_delete_url( $item['s_secret'] , $item['pk_i_id'] ) );
            flush();
            $item =Item::newInstance()->findByConditions( array('s_contact_email' => $this->email_items) ) ;
            flush();
        }

        $uSettings = new utilSettings();
        $uSettings->set_enabled_recaptcha_items( $this->enabled_recaptcha_items );
        $uSettings->set_items_wait_time( $this->items_wait_time );
        $uSettings->set_moderate_items( $this->bool_enabled_user_validation );
        $uSettings->set_reg_user_post( $this->bool_reg_user_post );
        unset($uSettings);
    }

    /*
     * PRIVATE FUNCTIONS
     */

    private function deleteItemUrl($url)
    {
        echo "URL -> $url<br>";
        $this->selenium->open( $url );
        $this->assertTrue($this->selenium->isTextPresent("Your item has been deleted"), "Can't delete item. ERROR ");
    }
    
    private function newly()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search" );
        $this->selenium->click("link=Newly listed");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : SPANISH LESSONS
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        $this->assertTrue(preg_match('/SPANISH LESSONS/i', $text), "Can't match last title in item. ERROR<br>=>$text");
    }

    private function lowerPrice()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search" );
        $this->selenium->click("link=Lower price first");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : German Training Coordination Agent (Barcelona centre) en Barcelona
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        $this->assertTrue(preg_match('/German Training Coordination Agent \(Barcelona centre\) en Barcelona/', $text), "Can't match last title in item. ERROR");
    }

    private function higherPrice()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search" );
        $this->selenium->click("link=Higher price first");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : Avion ULM TL96 cerca de Biniagual
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        $this->assertTrue(preg_match('/Avion ULM TL96 cerca de Biniagual/', $text), "Can't match last title in item. ERROR");
    }
}
?>
