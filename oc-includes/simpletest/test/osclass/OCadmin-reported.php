<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_reported extends OCadminTest {
    
    /*
     * Login oc-admin
     * Insert item
     * 
     */
    function testInsertItem()
    {
        $this->loginWith();
        // insert 3 items
//        $this->insertItem() ;
//        $this->insertItem() ;
//        $this->insertItem() ;
//        $this->insertItem() ;
        
        // mark as spam item 1, 2, 3, 4
//        $this->markAs('spam',array(1,2,3,4) );
//        // mark as bad item 1 & 3
//        $this->markAs('bad',array(1,3,4) );
//        // mark as expire item 1 & 3
//        $this->markAs('exp',array(4) );
        
        // go to admin reported listings
        // and sort the table by spam and bad
        // checkOrder($type, $count)
        $this->checkOrder('spam', 4 );
        $this->checkOrder('bad' , 3 );
        $this->checkOrder('exp' , 1 );
        
//        // unmark 1 as spam
//        $this->unmarkAs('spam', array(2));
//        // unmark 1 as spam
//        $this->unmarkAs('bad', array(3));
//        // unmark 1 as ALL
//        $this->unmarkAs('all', array(1));
    }

//    /*
//     * Login oc-admin
//     * Edit item
//     */
//    function testEditItem()
//    {
//        $this->loginWith() ;
//        $this->editItem() ;
//    }

    private function checkOrder($type, $count) 
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("//a[@id='items_reported']");
        $this->selenium->waitForPageToLoad("10000");
        sleep(1);
        switch ($type) {
            case 'spam':
                error_log('case spam');
                $this->selenium->click("//a[@id='order_spam']");
                sleep(1);
                $num = $this->selenium->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows SPAM');
                break;
            case 'exp':
                error_log('case exp');
                $this->selenium->click("//a[@id='order_exp']");
                sleep(1);
                $num = $this->selenium->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows EXPIRED');
                break;
            case 'bad':
                error_log('case bad');
                $this->selenium->click("//a[@id='order_bad']");
                sleep(1);
                $num = $this->selenium->getXpathCount('//table/tbody/tr');
                $this->assertTrue( ($num == $count) , 'There are the correct rows BAD');
                break;
            default:
                break;
        }
    }
    
    private function markAs($type, $array)
    {
        $xpath_str = "xpath=//table/tbody/tr[position()=_ID_]/td/a[contains(.,'title item')]@href";
        foreach($array as $id) {
            // go to reported listings
            $this->selenium->open( osc_admin_base_url(true) );
            $this->selenium->click("//a[@id='items_manage']");
            sleep(2);
            $new_xpath = str_replace('_ID_', $id, $xpath_str);
            $href = $this->selenium->getAttribute($new_xpath);
            
            $this->selenium->open($href);
            $this->selenium->waitForPageToLoad("10000");
            sleep(2);
            // item detail -> mark as XXX
            switch ($type) {
                case 'spam':
                    $this->selenium->click("//a[@id='item_spam']");
                    $this->selenium->waitForPageToLoad("10000");
                    $this->assertTrue($this->selenium->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                case 'exp':
                    $this->selenium->click("//a[@id='item_expired']");
                    $this->selenium->waitForPageToLoad("10000");
                    $this->assertTrue($this->selenium->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                case 'bad':
                    $this->selenium->click("//a[@id='item_bad_category']");
                    $this->selenium->waitForPageToLoad("10000");
                    $this->assertTrue($this->selenium->isTextPresent("Thanks! That's very helpful"), 'Item has been marked');
                    break;
                default:
                    break;
            }
        }
    }
    
    // todo test minim lenght title, description , contact email
    private function insertItem($bPhotos = FALSE )
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Items");
        $this->selenium->click("//a[@id='items_new']");
        $this->selenium->waitForPageToLoad("10000");

        // insert non registered user
        $this->selenium->type("contactName" , "contact name");
        $this->selenium->type("contactEmail", "test@mail.com");

        $this->selenium->select("catId", "label=regexp:\\s*Cars");
        $this->selenium->type("title[en_US]", "title item");
        $this->selenium->type("description[en_US]", "description test description test description test");
        $this->selenium->type("price", "12".osc_locale_thousands_sep()."34".osc_locale_thousands_sep()."56".osc_locale_dec_point()."78".osc_locale_dec_point()."90");
        $this->selenium->fireEvent("price", "blur");
        sleep(2);
        $this->assertTrue($this->selenium->getValue("price")=="123456".osc_locale_dec_point()."78", "Check price correction input");
        $this->selenium->type("price", "11");
        $this->selenium->select("currency", "label=Euro €");

        $this->selenium->select("countryId", "label=Spain");

        $this->selenium->type('id=region', 'A Coruña');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type('id=city', 'A Capela');
        $this->selenium->click('id=ui-active-menuitem');

        $this->selenium->type("address", "address item");

        if( $bPhotos ){
            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/img_test1.gif");
            sleep(0.5);
            $this->selenium->type("//div[@id='p-0']/input", LIB_PATH."simpletest/test/osclass/img_test2.gif");
        }
        
        $this->selenium->click("//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
        
        $this->assertTrue($this->selenium->isTextPresent("A new listing has been added"), "Can't insert a new item. ERROR");
    }
}
?>