<?php
require_once dirname(__FILE__).'/../../../../oc-load.php';

//require_once('FrontendTest.php');

class OCadmin_moderator extends OCadminTest
{
    /*
     * Edit and email / alert
     */
    function testModeratorMenu()
    {
        // insert new moderator admin
        Admin::newInstance()->insert(array(
            's_name' => 'Test Admin',
            's_username' => 'testmoderator',
            's_password' => sha1($this->_password),
            's_secret' => 'mvqdnrpt',
            's_email' => 'testing+moderator@osclass.org',
            'b_moderator' => 1
        ));

        $this->loginWith( 'testmoderator', $this->_password) ;

        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad("2000");
        // check Admin Menu
        $res = $this->selenium->getXpathCount("//ul[@class='oscmenu']/li");
        $this->assertEqual(4, $res, "4 Menu options");

        $res = $this->selenium->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_dash']/ul/li");
        $this->assertEqual(0, $res, "0 Submenu options under id=menu_dash");

        $res = $this->selenium->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_items']/ul/li");
        $this->assertEqual(5, $res, "5 Submenu options under id=menu_items");

        $res = $this->selenium->getXpathCount("//ul[@class='oscmenu']/li[@id='menu_users']/ul/li");
        $this->assertEqual(4, $res, "4 Submenu options under id=menu_users");

        // try to enter to restricted zone
        $this->selenium->open(osc_admin_base_url(true).'?page=admins');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"), "Don't have enough permissions" ) ;

        $this->selenium->open(osc_admin_base_url(true).'?page=items&action=settings');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"), "Don't have enough permissions" ) ;

        $this->selenium->open(osc_admin_base_url(true).'?page=admins&action=edit');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("Edit admin"), "Don't have enough permissions" ) ;

        $this->selenium->open(osc_admin_base_url(true).'?page=admins&action=add');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertTrue($this->selenium->isTextPresent("You don't have enough permissions"), "Don't have enough permissions" ) ;

        $this->logout();
        $this->selenium->open(osc_admin_base_url(true).'?page=settings');
        $this->selenium->waitForPageToLoad("2000");
        $this->assertFalse($this->selenium->isTextPresent("You don't have enough permissions"), "Don't show the text: 'Don't have enough permissions'" ) ;

        // remove user testmoderator!
        Admin::newInstance()->delete(array('s_username' => 'testmoderator') );
    }
}

?>