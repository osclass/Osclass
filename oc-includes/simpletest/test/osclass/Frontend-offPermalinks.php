<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('Frontendtest.php');

class Frontend_offPermalinks extends OCadmintest {
  
    function testPermalinks()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Settings");
        $this->selenium->waitForPageToLoad("4000");
        $this->selenium->click("link=Permalinks");
        $this->selenium->waitForPageToLoad("10000");
        $value = $this->selenium->getValue('rewrite_enabled');
        
        // return to previous state (before starting the tests)
        if($value=='on') {
            $this->selenium->click("rewrite_enabled");
            $this->selenium->click("//input[@type='submit']");
            $this->selenium->waitForPageToLoad("10000");
            $this->assertTrue( $this->selenium->isTextPresent("Friendly URLs successfully deactivated") , "Disable permalinks" ) ;
        }   

    }
}
?>