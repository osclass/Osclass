<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class Frontend_page extends FrontendTest {
    
    /*
     * Insert new page (HARDCODED)
     * Visit new page from direct link and footer link
     * Delete new page (HARDCODED)
     */

    function testPage()
    {
        // insert page HARDCODED
        $aFields = array( 's_internal_name' => 'internal name'
                            , 'b_indelible' => 0
                            , 'b_link' => 1);

        $aFieldsDescription = array();
        $aFieldsDescription['en_US']['s_title'] = 'TITLE NEW PAGE';
        $aFieldsDescription['en_US']['s_text'] = 'TEXT<br> TEST PAGE <p>end</p>';

        $mPage = new Page();
        if( $mPage->insert($aFields, $aFieldsDescription) ){
            $page = $mPage->findByInternalName('internal name');
            $pageId =  $page['pk_i_id'];

            // go directly with url
            View::newInstance()->_exportVariableToView('page', $page);
            $url_page = osc_static_page_url();
            $this->selenium->open($url_page);
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue( $this->selenium->isTextPresent("TITLE NEW PAGE") , "Visit page, directly from url.");

            // go through footer
            $this->selenium->open( osc_base_url() );
            $this->selenium->click("link=TITLE NEW PAGE");
            $this->selenium->waitForPageToLoad("30000");
            $this->assertTrue( $this->selenium->isTextPresent("TITLE NEW PAGE") , "Visit page, through footer link");

            // delete page
            if( Page::newInstance()->deleteByPrimaryKey($pageId) ){
                $this->assertTrue( true , "Delete page.");
            } else {
                $this->assertTrue( false , "Delete page.");
            }
        } else {
           $this->assertTrue( false , "Insert new page.");
        }
    }
}
?>
