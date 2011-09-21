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
        $this->createPage('test_page_example') ;
        $this->deletePage('test_page_example') ;
    }

    /*
     * Login oc-admin
     * Create page
     * Create page again
     * Delete page
     */  
    function testPagesInsertDuplicate()
    {
        $this->loginWith() ;
        $this->createPage('test_page_example') ;
        $this->createPageAgain('test_page_example') ;
        $this->deletePage('test_page_example') ;
    }

    /*
     * Login oc-admin
     * Create page
     * Edit page
     * Delete page
     */  
    function testPageEdit()
    {
        $this->loginWith() ;
        $this->createPage('test_page_example') ;
        $this->editPage('test_page_example') ;
        $this->deletePage('new foo new') ;
    }

    /*
     * Login oc-admin
     * insert 10 new pages and delete all the pages.
     */
    function testMultiplePagesInsert()
    {
        $this->loginWith() ;
     
        $count = 0;
        while( $count < 10 ) {
            $this->newPage('test_page_example'.$count) ;
            $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "text present");
            $count++;
        }
        $this->selectAndDelete('test_page_example', 0, 5);
        
        $this->selectAllAndDelete();
    }

    /*
     * Login oc-admin
     * Navigate throw pages
     */
    public function testTableNavigation()
    {
        $this->loginWith() ;

        $count = 0;
        while( $count < 15 ) {
            $this->newPage('test_page_example'.$count) ;
            $this->assertTrue($this->selenium->isTextPresent('The page has been added'), "text present");
            $count++;
            flush();
        }

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Pages");
        $this->selenium->click("link=» Manage pages");
        $this->selenium->waitForPageToLoad("10000");
        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
        $this->assertEqual(10, $res,"10 rows does not appear [$res]");

        $this->selenium->click("//span[@class='next paginate_button']");
        $this->selenium->waitForPageToLoad("10000");
        
        $res = $this->selenium->getXpathCount("//table[@id='datatables_list']/tbody/tr");
        $this->assertEqual(5, $res,"5 rows does not appear [$res]");

        // two pages
        $this->selectAllAndDelete();
        $this->selectAllAndDelete();
    }
}
?>
