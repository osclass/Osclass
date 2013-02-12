<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_market extends OCadminTest {

    function testMarketURLOn()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org.devel/api/');
    }


//    function testMarketPluginsPagination()
//    {
//
//        $this->loginWith();
//        $this->selenium->open( osc_admin_base_url(true) ) ;
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("//a[@id='market_view_plugins']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $text = $this->selenium->getText("//h2[@class='section-title']");
//        $count = $this->selenium->getXpathCount("//div[@class='mk-item mk-item-plugin']");
//        $this->assertTrue(($count==9), "Correct number of market items");
//        $p1 = $this->getPluginName();
//
//        if(preg_match('|([0-9]+) plugins|', $text, $match)) {
//            $last = $this->selenium->getText("css=a[class=searchPaginationNonSelected]:last");
//            $this->assertTrue(($last=ceil($match[1]/9)), "Pagination shows correct number of pages");
//            $this->selenium->click("css=a[class=searchPaginationNonSelected]:last");
//            $this->selenium->waitForPageToLoad("10000");
//
//            $count = $this->selenium->getXpathCount("//div[@class='mk-item mk-item-plugin']");
//            $this->assertTrue(($count==($match[1]-((ceil($match[1]/9)-1)*9))), "Correct number of market items");
//            $p2 = $this->getPluginName();
//            $this->assertFalse(($p1==$p2 || strpos($p2, "OR: Element")), "Same item in both pages, page didn't changed ( ".$p1." - ".$p2." )");
//
//        } else {
//            $this->assertTrue(false, "preg_match 'XX plugins' failed");
//        }
//
//    }
//
//    function testMarketPluginsViewInfo()
//    {
//
//        $this->loginWith();
//        $this->selenium->open( osc_admin_base_url(true) ) ;
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("//a[@id='market_view_plugins']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $text = $this->getPluginName();
//        $this->assertFalse(strpos($text, "OR: Element"), "Market : View info failed");
//
//    }
//
//    function testMarketPluginsInstall()
//    {
//        osc_check_plugins_update(true);
//        $old_plugins = json_decode(osc_get_preference('plugins_downloaded'));
//
//        $this->loginWith();
//        $this->selenium->open( osc_admin_base_url(true) ) ;
//        $this->selenium->waitForPageToLoad("10000");
//        $this->selenium->click("//a[@id='market_view_plugins']");
//        $this->selenium->waitForPageToLoad("10000");
//
//        $this->selenium->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='more']");
//        sleep(2);
//        $this->selenium->click("//div[@class='mk-info']/table/tbody/tr/td[@class='actions']/a[contains(.,'Download')]");
//        sleep(2);
//        $textIsPresent = false;
//        for($t=0;$t<60;$t++) {
//            sleep(1);
//            $textIsPresent = $this->selenium->isTextPresent("The plugin has been downloaded correctly, proceed to install and configure");
//            if($textIsPresent) { break; };
//            break;
//        }
//        $this->assertTrue($textIsPresent, "Plugin failed downloading");
//        sleep(1);
//        $this->selenium->click("//div[@id='downloading']/div/p/a[contains(.,'Ok')]");//"//div[@='osc-modal-content']/p/a[@class='btn btn-mini btn-green']");
//
//
//        // GET INFO OF NEW PLUGIN
//        osc_check_plugins_update(true);
//        $plugins = json_decode(osc_get_preference('plugins_downloaded'));
//        foreach($old_plugins as $p) {
//            foreach($plugins as $k => $v) {
//                if($p==$v) {
//                    unset($plugins[$k]);
//                    break;
//                }
//            }
//        }
//        $info = array();
//        $plugin = current($plugins);
//
//        $plugin = "new_plugin_1";
//
//        $plugins = Plugins::listAll(false);
//        foreach($plugins as $p) {
//            $pinfo = Plugins::getInfo($p);
//            if($pinfo['short_name']==$plugin) {
//                $info = $pinfo;
//                break;
//            }
//        }
//
//
//        // CHECK IT'S ON THE INSTALLED LIST
//        $this->selenium->click("//a[@id='plugins_manage']");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->assertTrue($this->selenium->isTextPresent(@$info['plugin_name']), "Plugin does not appear on the list");
//
//
//        // DELETE FOLDER
//        $tmp = explode("/", $info['filename']);
//        $this->deletePlugin($tmp[0]);
//
//        // CHECK IT'S *NOT* ON THE INSTALLED LIST
//        $this->selenium->click("//a[@id='plugins_manage']");
//        $this->selenium->waitForPageToLoad("10000");
//        $this->assertFalse($this->selenium->isTextPresent(@$info['plugin_name']), "Plugin does appear on the list");
//
//
//    }

    /**
     * test order by data and downloads
     */
    function testMarketOrderUpdate()
    {
        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        // coger fecha y parsear del primer elemento
        $this->selenium->click("xpath=(//div[@class='mk-info']/div[@class='market-actions']/span[@class='more'])[1]");
        $last_update = $this->selenium->getText("xpath=//span[contains(.,'Last update ')]");
        error_log($last_update);

//         parse date
        $last_update = str_replace('Last update ', '', $last_update);
        



        // paginar a la última página

        // coger fecha y parsear del último elemento ç

        // comprobar que la fecha_uno es mayor que la fecha_dos

    }

    function testMarketURLOff()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/');
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
