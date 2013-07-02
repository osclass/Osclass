<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Installer_clean extends InstallerTest {

    function testRemoveExampleAd()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);
        $this->selenium->type('user', 'admin');
        $this->selenium->type('password', 'admin');
        sleep(4);
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_manage']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(2); // time enough to load table data
        $num = $this->selenium->getXpathCount('//table/tbody/tr');

        $loops = 0;
        while( $loops < 5 && $num >= 1 ) {
            $this->selenium->click("xpath=//input[@id='check_all']");
            $this->selenium->select('bulk_actions', 'value=delete_all');
            $this->selenium->click("xpath=//input[@id='bulk_apply']");
            $this->selenium->click("xpath=//a[@id='bulk-actions-submit']");
            $this->selenium->waitForPageToLoad("10000");
            $this->assertTrue($this->selenium->isTextPresent("listings have been deleted") || $this->selenium->isTextPresent("listing has been deleted")
                , "BulkActions delete all on delete test. ERROR");

            $num = $this->selenium->getXpathCount('//table/tbody/tr');
            $loops++;
            print_r($loops);
            flush();
            if($this->selenium->isTextPresent("No data available in table") ) {
                break;
            }
        }
    }
}
?>