<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_market extends OCadminTest {

    function atestMarketURLOn()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org.devel/api/');
    }


    function atestMarketPluginsPagination()
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
            $this->assertFalse(($p1==$p2 || strpos($p2, "OR: Element")), "Same item in both pages, page didn't changed");

        } else {
            $this->assertTrue(false, "preg_match 'XX plugins' failed");
        }

    }

    function atestMarketPluginsViewInfo()
    {

        $this->loginWith();
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->waitForPageToLoad("10000");
        $this->selenium->click("//a[@id='market_view_plugins']");
        $this->selenium->waitForPageToLoad("10000");

        $text = $this->getPluginName();
        $this->assertFalse(strpos($text, "OR: Element"), "Market : View info failed");

    }

    function testMarketInstall()
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
        $this->selenium->click("//div[@='osc-modal-content']/p/a[contains(.,'Ok')]");

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

        $plugin = current($plugins);
        $plugins = Plugins::listAll(false);
        foreach($plugins as $p) {
            $info = Plugins::getInfo($p);
            if($info['short_name']==$plugin) {
                $tmp = explode("/", $p);
                $this->deletePlugin($tmp[0]);
                break;
            }
        }

    }

    function atestMarketURLOff()
    {
        osc_set_preference('marketURL', 'http://market.osclass.org/api/');
    }


    private function getPluginName() {
        $this->selenium->click("//div[@class='mk-item mk-item-plugin']/div/div/span[@class='more']");
        sleep(1);
        $text =  $this->selenium->getText("//div[@class='mk-inffo']/table/tbody/tr/td/h3");
        $this->selenium->click("link=close");
        return $text;
    }

    private function deletePlugin($folder) {
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
                    rchmod( $path.'/'.$file, ($level+1));
                }
            }
        }
        closedir( $dh );
    }


}
?>
