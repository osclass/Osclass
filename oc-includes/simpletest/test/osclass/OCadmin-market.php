<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_market extends OCadminTest {

    function testMarketURLOn()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org.devel/api/');
    }


    function testMarketPluginsPagination()
    {

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        $text = $this->selenium->getText("//h2[@class='section-title']");
        $count = $this->selenium->getXpathCount("//div[@class='mk-item mk-item-plugin']");
        $this->assertTrue(($count==9), "Correct number of market items");
        $p1 = $this->getPluginName();

        if(preg_match('|([0-9]+) plugins|', $text, $match)) {
            $last = $this->selenium->getText("css=a[class=searchPaginationNonSelected]:last");
            $this->assertTrue(($last=ceil($match[1]/9)), "Pagination shows correct number of pages");
            $this->selenium->click("css=a[class=searchPaginationNonSelected]:last");
            $this->selenium->waitForPageToLoad("10000");

            $count = $this->selenium->getXpathCount("//div[@class='mk-item mk-item-plugin']");
            $this->assertTrue(($count==($match[1]-((ceil($match[1]/9)-1)*9))), "Correct number of market items");
            $p2 = $this->getPluginName();
            $this->assertFalse(($p1==$p2 || strpos($p2, "OR: Element")), "Same item in both pages, page didn't changed ( ".$p1." - ".$p2." )");

        } else {
            $this->assertTrue(false, "preg_match 'XX plugins' failed");
        }

    }

    function testMarketPluginsViewInfo()
    {

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        $text = $this->getPluginName();
        $this->assertFalse(strpos($text, "OR: Element"), "Market : View info failed");

    }

    function testMarketPluginsInstall()
    {
        osc_check_plugins_update(true);
        $old_plugins = json_decode(osc_get_preference('plugins_downloaded'));

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='more']");
        sleep(2);
        $this->selenium->click("//div[@class='mk-info']/table/tbody/tr/td[@class='actions']/a[contains(.,'Download')]");
        sleep(2);
        $textIsPresent = false;
        for($t=0;$t<60;$t++) {
            sleep(1);
            $textIsPresent = $this->selenium->isTextPresent("The plugin has been downloaded correctly, proceed to install and configure");
            if($textIsPresent) { break; };
            break;
        }
        $this->assertTrue($textIsPresent, "Plugin failed downloading");
        sleep(1);
        $this->selenium->click("//div[@id='downloading']/div/p/a[contains(.,'Ok')]");//"//div[@='osc-modal-content']/p/a[@class='btn btn-mini btn-green']");


        // GET INFO OF NEW PLUGIN
        osc_check_plugins_update(true);
        $plugins = json_decode(osc_get_preference('plugins_downloaded'));
        foreach($old_plugins as $p) {
            foreach($plugins as $k => $v) {
                if($p==$v) {
                    unset($plugins[$k]);
                    break;
                }
            }
        }
        $info = array();
        $plugin = current($plugins);

        $plugin = "new_plugin_1";

        $plugins = Plugins::listAll(false);
        foreach($plugins as $p) {
            $pinfo = Plugins::getInfo($p);
            if($pinfo['short_name']==$plugin) {
                $info = $pinfo;
                break;
            }
        }


        // CHECK IT'S ON THE INSTALLED LIST
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertTrue($this->selenium->isTextPresent(@$info['plugin_name']), "Plugin does not appear on the list");


        // DELETE FOLDER
        $tmp = explode("/", $info['filename']);
        $this->deletePlugin($tmp[0]);

        // CHECK IT'S *NOT* ON THE INSTALLED LIST
        $this->selenium->click("//a[@id='plugins_manage']");
        $this->selenium->waitForPageToLoad("10000");
        $this->assertFalse($this->selenium->isTextPresent(@$info['plugin_name']), "Plugin does appear on the list");


    }

    /**
     * test order by mod_date
     *
     */
    function testMarketOrderUpdate()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        // get first item
        $last_update = '';
        $this->selenium->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[1]");
        $last_update = $this->selenium->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $first_date  = $this->createDate($last_update);

        // go to last page
        $this->selenium->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->selenium->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->selenium->waitForPageToLoad("10000");

        // get last item
        $last_update = '';
        $this->selenium->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[last()]");
        $last_update = $this->selenium->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $last_date   = $this->createDate($last_update);

        // comprobar que la fecha_uno es mayor que la fecha_dos
        // error_log('=>    '.$first_date.'  '.$last_date);
        $this->assertTrue( strtotime($first_date) >= strtotime($last_date) , 'last item is newer than first item');

        /*
         *  ------------------------ reverse order ------------------------
         */
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        // change order ...
        $this->selenium->click("xpath=//a[@id='sort_updated']");
        $this->selenium->waitForPageToLoad("10000");

        // get first item
        $last_update = '';
        $this->selenium->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[1]");
        $last_update = $this->selenium->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $first_date  = $this->createDate($last_update);

        // go to last page
        $this->selenium->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->selenium->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->selenium->waitForPageToLoad("10000");

        // get last item
        $last_update = '';
        $this->selenium->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[last()]");
        $last_update = $this->selenium->getText("xpath=//span[contains(.,'Last update ')]");

        // parse date
        $last_update = str_replace('Last update ', '', $last_update);
        $last_date   = $this->createDate($last_update);

        // comprobar que la fecha_uno es mayor que la fecha_dos
        // error_log('=>    '.$first_date.'  '.$last_date);
        $this->assertTrue( strtotime($first_date) <= strtotime($last_date) , 'last item is older than first item');

    }

    function testMarketOrderDownload()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        // change order ...
        $this->selenium->click("xpath=//a[@id='sort_download']");
        $this->selenium->waitForPageToLoad("10000");

        // get first item
        $downloads   = $this->selenium->getText("xpath=(//span[@class='downloads']/strong)[1]");

        // go to last page
        $this->selenium->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->selenium->waitForPageToLoad("10000");

        // get last item
        $last_downloads = $this->selenium->getText("xpath=(//span[@class='downloads']/strong)[last()]");

        // check total downloads
        $this->assertTrue( $downloads >= $last_downloads , 'last item have more downloads than first item');

        /*
         *  ------------------------ reverse order ------------------------
         */
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        // change order ... twice
        $this->selenium->click("xpath=//a[@id='sort_download']");
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("xpath=//a[@id='sort_download']");
        $this->selenium->waitForPageToLoad("10000");

        // get first item
        $downloads   = $this->selenium->getText("xpath=(//span[@class='downloads']/strong)[1]");

        // go to last page
        $this->selenium->click("xpath=//span[@class='ui-dialog-title']/../a");
        $this->selenium->click("xpath=(//div[@class='has-pagination']/ul/li/a[@class='searchPaginationNonSelected'])[last()]");
        $this->selenium->waitForPageToLoad("10000");

        // get last item
        $last_downloads = $this->selenium->getText("xpath=(//span[@class='downloads']/strong)[last()]");

        $this->assertTrue( $downloads <= $last_downloads, 'last item have less downloads than first item');
    }

    function testMarketURLOff()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/');
    }

    /*
     *      Private functions
     */

    private function createDate($date) {
        echo "createDate : " . $date . "<br>";
        flush();
        $aDate  = explode('-', $date);
        $date   = date("Y-m-d", mktime(0,0,0,$aDate[1], $aDate[2], intval($aDate[0])) );
        echo "date : " . $date . "<br>";
        flush();
        return $date;
    }

    private function getPluginName() {
        $this->selenium->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='more']");
        sleep(1);
        $text =  $this->selenium->getText("//div[@class='mk-info']/table/tbody/tr/td/h3");
        $this->selenium->click("link=close");
        return $text;
    }

    private function deletePlugin($folder) {
        if(trim($folder)=='') { return false; }
        $this->rchmod(CONTENT_PATH."plugins/".$folder);
        osc_deleteDir(CONTENT_PATH."plugins/".$folder);
    }

    private function rchmod($path = '.', $level = 0 ) {
        $ignore = array('.', '..');
        $dh = @opendir( $path );
        while( false !== ( $file = readdir( $dh ) ) ) {
            if( !in_array( $file, $ignore ) ){
                @chown($path.'/'.$file,getmyuid());
                @chmod($path.'/'.$file,0777);
                if( is_dir( $path.'/'.$file ) ){
                    $this->rchmod( $path.'/'.$file, ($level+1));
                }
            }
        }
        closedir( $dh );
    }


}
?>
