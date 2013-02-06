<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_market extends OCadminTest {

    function atestMarketURLOn()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org.devel/api/');
    }


    function testMarketPlugins()
    {

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        $text = $this->selenium->getText("//h2[@class='section-title']");

        $count = $this->selenium->getCount("//a[@class='mk-item-parent']");
        print_r($count);
        print_r("~~~");

        if(preg_match('|([0-9]+) plugins|', $text, $match)) {
            $last = $this->selenium->getText("css=a[class=searchPaginationNonSelected]:last");
            $this->assertTrue(($last=ceil($match[1]/9)), "Pagination shows correct number of pages");
            $this->selenium->click("css=a[class=searchPaginationNonSelected]:last");
            $this->selenium->waitForPageToLoad("10000");

            $count = $this->selenium->getCount("//a[@class='mk-item-parent']");
            print_r($count);

        } else {
            $this->assertTrue(false, "preg_match 'XX plugins' failed");
        }

        //$p1 = $this->selenium->getText("//a[@class='mk-item-parent']");
        //print_r($p1);

    }

    function atestMarketURLOff()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/');
    }


    private function deletePlugin() {
        @chmod(CONTENT_PATH."plugins/breadcrumbs/index.php", 0777);
        @chmod(CONTENT_PATH."plugins/breadcrumbs/", 0777);
        osc_deleteDir(CONTENT_PATH . "plugins/breadcrumbs/");
    }


}
?>
