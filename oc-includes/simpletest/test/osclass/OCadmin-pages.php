<?php
require_once '../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_pages extends OCadminTest {
    
    /*
     * Login oc-admin
     * Create page
     * Delete page
     */  
    function testPagesInsert()
    {
        $this->loginWith() ;
//        $this->newPage('test_page_example') ;
//        $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
//        $this->deletePage('test_page_example') ;
        
//        // complex title & description
        $this->newPageWithData('test_page_example', "cos's test", "cos's test") ;
        $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
        $this->assertTrue($this->selenium->isTextPresent("cos's test"), "Title present");
        sleep(5);
        $this->deletePage('test_page_example') ;
    }

    /*
     * Login oc-admin
     * Create page
     * Create page again
     * Delete page
     */  
//    function testPagesInsertDuplicate()
//    {
//        $this->loginWith() ;
//        $this->newPage('test_page_example') ;
//        $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
//        $this->newPage('test_page_example') ;
//        $this->assertTrue($this->selenium->isTextPresent('Oops! That internal name is already in use. We can\'t made the changes'), "Insert page.");
//        $this->deletePage('test_page_example') ;
//    }
//
//    /*
//     * Login oc-admin
//     * Create page
//     * Edit page
//     * Delete page
//     */  
//    function testPageEdit()
//    {
//        $this->loginWith() ;
//        $this->newPage('test_page_example') ;
//        $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
//        $this->editPage('test_page_example') ;
//        $this->deletePage('new foo new') ;
//    }
//
//    /*
//     * Login oc-admin
//     * insert 10 new pages and delete all the pages.
//     */
//    function testMultiplePagesInsert()
//    {
//        $this->loginWith() ;
//     
//        $count = 0;
//        while( $count < 10 ) {
//            $this->newPage('test_page_example'.$count) ;
//            $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
//            $count++;
//        }
//        $this->selectAndDelete('test_page_example', 0, 5);
//        
//        $this->selectAllAndDelete();
//    }
//
//    /*
//     * Login oc-admin
//     * Navigate throw pages
//     */
//    public function testTableNavigation()
//    {
//        $this->loginWith() ;
//
//        $count = 0;
//        while( $count < 15 ) {
//            $this->newPage('test_page_example'.$count) ;
//            $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "Insert page.");
//            $count++;
//            flush();
//        }
//
//        $this->selenium->open( osc_admin_base_url(true) );
//        $this->selenium->click("link=Pages");
//        $this->selenium->click("link=» Manage pages");
//        $this->selenium->waitForPageToLoad("10000");
//        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
//        $this->assertEqual(10, $res,"10 rows does not appear [$res]");
//
//        $this->selenium->click("//span[@class='next paginate_button']");
//        $this->selenium->waitForPageToLoad("10000");
//        
//        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
//        $this->assertEqual(5, $res,"5 rows does not appear [$res]");
//
//        // two pages
//        $this->selectAllAndDelete();
//        $this->selectAllAndDelete();
//    }
    
    /*
     * Private functions
     */
    
    private function newPage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Create page");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_internal_name", $internal_name );
        $this->selenium->type("en_US#s_title", "title US");
        $this->selenium->type("en_US#s_text", "text for US");

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
    }
    
    private function newPageWithData($internal_name, $title, $description)
    {
        $this->selenium->open( osc_admin_base_url(true) ) ;
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Create page");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("s_internal_name", $internal_name );
        $this->selenium->type("en_US#s_title", $title);
        $this->selenium->type("en_US#s_text", $description);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");
    }
    
    private function editPage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->mouseOver("xpath=//table/tbody/tr[contains(.,'$internal_name')]");
        $this->selenium->click("xpath=//table/tbody/tr/td[contains(.,'$internal_name')]/div/a[text()='Edit']");
        $this->selenium->waitForPageToLoad("30000");

        // editing page ...
        $this->selenium->type("s_internal_name", "new foo new");
        $this->selenium->type("en_US#s_title", "new bar");
        $this->selenium->click("xpath=//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        if( $this->selenium->isTextPresent("The page has been updated") ){
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - The page has been updated - ");
        }
    }

    private function deletePage($internal_name)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->mouseOver("//table/tbody/tr/td[contains(.,'$internal_name')]");
        $this->selenium->click("xpath=//table/tbody/tr/td[contains(.,'$internal_name')]/div/a[text()='Delete']");

        // click alert OK
        
        $this->selenium->waitForPageToLoad("30000");
        
        if( $this->selenium->isTextPresent('One page has been deleted correctly') ){
            $this->assertTrue("text present");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - One page has been deleted correctly - ");
        }
    }
    
    private function selectAndDelete($internal_name, $beg, $fin)
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("30000");

        $beg_ = $beg;
        for($beg_; $beg_ <= $fin-1; $beg_++){
            $this->selenium->click("xpath=//table/tbody/tr[contains(.,'$internal_name".$beg_."')]/td/input");
        }

        $this->selenium->select("bulk_actions", "label=Delete");
        $this->selenium->click("xpath=//button[@id='bulk_apply']");
        $this->selenium->waitForPageToLoad("30000");

        echo "< ".$this->selenium->getText('//*[@id="FlashMessage"]')." ><br>";

        if( $this->selenium->isTextPresent( ($fin-$beg) . " pages have been deleted correctly") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - ". ($fin-$beg) . " pages have been deleted correctly");
        }
    }

    private function selectAllAndDelete()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("30000");


        $this->selenium->click("check_all");
        $this->selenium->select("bulk_actions", "label=Delete");
        $this->selenium->click("bulk_apply");
        $this->selenium->waitForPageToLoad("30000");
        // "regexpi:This is SeleniumWiki.com"
        if( $this->selenium->isTextPresent( "regexpi:pages have been deleted correctly") ){
            $this->assertTrue("Deleted ok");
        } else {
            $this->assertFalse("TEXT NOT PRESENT - X pages have been deleted correctly");
        }
    }
}
?>
