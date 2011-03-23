<?php



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
        // allow no users to add ads
        // save status
        $this->bool_enabled_user_validation = Preference::newInstance()->findValueByName('enabled_user_validation');
        $this->bool_reg_user_post           = Preference::newInstance()->findValueByName('reg_user_post');
        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'reg_user_post'));

        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'enabled_item_validation'));

        echo "inserting items...<br>";
    }

    /*           TESTS          */
    
    public function testInitial()
    {
       require 'itemData.php';
        foreach($aData as $item){
            echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - insertItem()</div>";
            flush();
            $this->insertItem($item['catId'], $item['title'], $item['description'], $item['price'], $item['regionId'], $item['cityId'], $item['photo'], $item['contactName'], $item['contactEmail']);
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

    public function testFinal()
    {
       require 'itemData.php';
        foreach($aData as $item){
            echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - delete()</div>";
            flush();
            Item::newInstance()->delete(array('s_contact_email' => 'mail@contact.com'));
        }
        if( $this->bool_enabled_user_validation ){
            Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'enabled_item_validation'));
        } else {
            Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'enabled_item_validation'));
        }
        if( $this->bool_reg_user_post ){
            Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'reg_user_post'));
        } else {
            Preference::newInstance()->update(array('s_value' => 1)
                                         ,array('s_name'  => 'reg_user_post'));
        }



    }

    /*
     * PRIVATE FUNCTIONS
     */

    private function newly()
    {
        $this->selenium->open( osc_base_url(true) . "?page=search" );
        $this->selenium->click("link=Newly listed");
        $this->selenium->waitForPageToLoad("30000");
        // last item added -> TITLE : SPANISH LESSONS
        $text = $this->selenium->getText('//table/tbody/tr[1]/td[2]');
        $this->assertTrue(preg_match('/SPANISH LESSONS/', $text), "Can't match last title in item. ERROR");
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

    private function insertItem($cat, $title, $description, $price, $regionId, $cityId, $aPhotos, $user, $email )
    {
        $reg_user_post = Preference::newInstance()->findValueByName('reg_user_post');
        $enabled_item_validation = Preference::newInstance()->findValueByName('enabled_item_validation');
        $logged_user_item_validation = Preference::newInstance()->findValueByName('logged_user_item_validation');

        flush();

        $this->selenium->open( osc_base_url(true) );

        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->select("catId", "label=$cat");

        $this->selenium->type("title[en_US]", $title);
        $this->selenium->type("description[en_US]", $description);
        $this->selenium->type("price", $price);

        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("regionId", "label=$regionId");
        $this->selenium->select("cityId", "label=$cityId");
        $this->selenium->type("cityArea", "my area");
        $this->selenium->type("address", "my address");

        if( count($aPhotos) > 0 ){
            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            $this->selenium->click("link=Add new photo");
            $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }
        echo $title."<br>";
        $this->selenium->type("contactName" , $user);
        $this->selenium->type("contactEmail", $email);
        
        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");
        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";

        if( $this->logged == 0 ){
            if($enabled_item_validation){
                $this->assertTrue($this->selenium->isTextPresent("Great! You'll receive an e-mail to activate your item","Need validation but message don't appear") );
            } else {
                $this->assertTrue($this->selenium->isTextPresent("Great! We've just published your item","no logged in error inserting ad.") );
            }
        } else {
            if($logged_user_item_validation){
                $this->assertTrue($this->selenium->isTextPresent("Great! We've just published your item","insert ad error ") );
            } else {
                $this->assertTrue($this->selenium->isTextPresent("Great! You'll receive an e-mail to activate your item","Need validation but message don't appear") );
            }
        }
    }


}
?>
