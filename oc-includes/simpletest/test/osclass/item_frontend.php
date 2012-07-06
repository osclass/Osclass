<?php
error_reporting(E_ALL);

class ItemFrontend {

    private $selenium;
    
    public function __construct() {
        echo "contruct <br>";flush();
    }

    public function insertItem($cat, $title, $description, $price, $regionId, $cityId, $aPhotos, $user, $email ,$selenium = null, $simpletest = null, $logged = 0)
    {
        if( $selenium != null ) {
            $this->selenium = $selenium;
        }

        $reg_user_post               = Preference::newInstance()->findValueByName('reg_user_post');
        $enabled_item_validation     = Preference::newInstance()->findValueByName('enabled_item_validation');
        $logged_user_item_validation = Preference::newInstance()->findValueByName('logged_user_item_validation');

//        echo "reg_user_post ".$reg_user_post."<br>";
//        echo "enabled_item_validation ".$enabled_item_validation."<br>";
//        echo "logged_user_item_validation ".$logged_user_item_validation."<br>";

        flush();

        $this->selenium->open( osc_base_url(true) );

        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->select("catId", "label=regexp:\\s*$cat");

        $this->selenium->type("title[en_US]", $title);
        $this->selenium->type("description[en_US]", $description);
        $this->selenium->type("price", $price);

        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("countryId", "label=Spain");

//        $this->selenium->select("regionId", "label=$regionId");
        $this->selenium->type('id=region', $regionId);
        $this->selenium->click('id=ui-active-menuitem');

//        $this->selenium->select("cityId", "label=$cityId");
        $this->selenium->type('id=city', $cityId);
        $this->selenium->click('id=ui-active-menuitem');

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

        flush();
        
        if( $logged == 0 ){
            if($enabled_item_validation){
                $simpletest->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address","Need validation but message don't appear") );
            } else {
                $simpletest->assertTrue($this->selenium->isTextPresent("Your item has been published","no logged in error inserting ad.") );
            }
        } else {
            if($logged_user_item_validation){
                $simpletest->assertTrue($this->selenium->isTextPresent("Your item has been published","insert ad error ") );
            } else {
                $simpletest->assertTrue($this->selenium->isTextPresent("Check your inbox to verify your email address","Need validation but message don't appear") );
            }
        }
    }
}
?>
