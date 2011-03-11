<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfModelPages extends UnitTestCase {

    function setUp()
    {
        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";
        flush();
    }

    function tearDown()
    {
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }

    public function testInsertPage()
    {
        $mPage = new Page();

        // prepare data for insert
        $aFields = array(
             's_internal_name' => "model_test_internal_name"
            ,'b_indelible' => '0');

        $aFieldsDescription = array();
        $aFieldsDescription['en_US']['s_title'] = "test en_US for title";
        $aFieldsDescription['en_US']['s_text']  = "test en_US for text, some text";

        $aFieldsDescription['fr_FR']['s_title'] = "test fr_FR for title";
        $aFieldsDescription['fr_FR']['s_text']  = "test fr_FR for text, some text";

        // insert
        $mPage->insert($aFields, $aFieldsDescription) ;

        // comprobar que se haya insertado

    }

}
?>
