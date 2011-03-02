<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    require_once '../../../../oc-load.php';

class TestOfLogin extends WebTestCase {
    
    function testLoginLogout() {
        // MOD_REWRITE DOESN'T WORK, DE-ACTIVATE IT FIRST
        $this->assertTrue($this->get(osc_user_login_url()));
        $this->setField('email', 'nodani@gmail.com');
        $this->setField('password', 'password');
        $this->click('Log in');
        // CHECK IF WE ENTERED THE USER'S ACCOUNT
        $this->assertText('Items from nodani@gmail.com');
        $this->assertTrue($this->get(osc_user_logout_url()));
        $this->assertNoText('Items from nodani@gmail.com');
    }        
    
}

?>
