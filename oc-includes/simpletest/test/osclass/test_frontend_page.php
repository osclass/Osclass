<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

include 'test_frontend_register.php';

class TestOfPage extends WebTestCase {

    private $selenium;

    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */
    public function testPage()
    {
        echo "<div style='background-color: green; color: white;'>FRONTEND - <h2>testPage</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'> - testPage - show page</div>";
        $this->showPage();
        flush();
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function showPage()
    {
        // create page
        echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - Inserting a new page -</div>";
        $id = $this->insertNewPage("internal name");
        
        // go directly with url
        $this->selenium->open(osc_base_url(true) . "?page=page&id=$id");
        $this->selenium->waitForPageToLoad("30000");
        echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - Goto page url directly-</div>";
        $this->assertTrue( $this->selenium->isTextPresent("TITLE NEW PAGE") );
        // go through footer
        $this->selenium->open(osc_base_url(true));
        $this->selenium->click("link=TITLE NEW PAGE");
        $this->selenium->waitForPageToLoad("30000");
        echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - Goto page url through footer link-</div>";
        $this->assertTrue( $this->selenium->isTextPresent("TITLE NEW PAGE") );

        // delete page
        if( Page::newInstance()->deleteByPrimaryKey($id) ){
            echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - deleted page -</div>";
        } else {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - can't delete page ERROR-</div>";
        }
    }

    private function insertNewPage($intName)
    {
        $aFields = array( 's_internal_name' => $intName
                            , 'b_indelible' => '0');

        $aFieldsDescription = array();
        $aFieldsDescription['en_US']['s_title'] = 'TITLE NEW PAGE';
        $aFieldsDescription['en_US']['s_text'] = 'TEXT<br> TEST PAGE <p>end</p>';

        $mPage = new Page();
        if( $mPage->insert($aFields, $aFieldsDescription) ){
            echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - Inserted ok (NEW PAGE)-</div>";
        } else {
            echo "<div style='background-color: green; color: white;padding-left:15px;'>FRONTEND - Failed insertion ERROR-</div>";
        }

        $page = $mPage->findByInternalName($intName);
        return $page['pk_i_id'];
    }
}
?>
