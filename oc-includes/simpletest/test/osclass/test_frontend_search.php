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
        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'reg_user_post'));

        Preference::newInstance()->update(array('s_value' => 0)
                                         ,array('s_name'  => 'enabled_item_validation'));

        echo "inserting items...<br>";
        
    }
    
    public function testInitial()
    {
       require 'itemData.php';
        foreach($aData as $item){
            echo "<div style='background-color: green; color: white;padding-left:15px;'> - TestOfSearch - insertItem()</div>";
            flush();
            $this->insertItem($item['catId'], $item['title'], $item['description'], $item['price'], $item['regionId'], $item['cityId'], $item['photo'], $item['contactName'], $item['contactEmail']);

        }
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

        $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
        $this->selenium->click("link=Add new photo");
        $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");

        if($this->logged == 0){
            $this->selenium->type("contactName" , $user);
            $this->selenium->type("contactEmail", $email);
        }

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("30000");

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
