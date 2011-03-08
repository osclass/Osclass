<?php

    require_once('../../autorun.php');
    require_once('../../web_tester.php');
    require_once('../../reporter.php');

    // LOAD OSCLASS
    require_once '../../../../oc-load.php';

class TestOfAdminAccount extends WebTestCase {
    
    function testAdminAccount() {
        // LOAD SOME DATA (Registration form uses some JS magic, so we can not test it with simpletest)
        // Instead, we create an user "by hand"
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        // TEST WRONG PASSWORD
        $this->get(osc_admin_base_url());
        $this->setField('user', 'testadmin');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Visit website');
        
        // TEST WRONG USER
        $this->get(osc_admin_base_url());
        $this->setField('user', 'wrong_testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertNoText('Visit website');
        
        // TEST WRONG USER & PASSWORD
        $this->get(osc_admin_base_url());
        $this->setField('user', 'wrong_testadmin');
        $this->setField('password', 'wrong_password');
        $this->click('Log in');
        $this->assertNoText('Visit website');
        
        // TEST CORRECT LOGIN
        $this->get(osc_admin_base_url());
        $this->setField('user', 'testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Visit website');
        
        // TEST LOAD LIST OF ADMINS
        $this->assertTrue($this->get(osc_admin_base_url(true).'?page=admins'));
        
        // TEST ADD ADMIN
        $this->get(osc_admin_base_url(true).'?page=admins&action=add');
        $this->setField('s_name', 'New Administrator');
        $this->setField('s_email', 'new_testadmin@test.net');
        $this->setField('s_password', 'password');
        $this->setField('s_username', 'newtestadmin');
        $this->click('Create');
            // LOGOUT TO TEST IT
            $this->get(osc_admin_base_url(true).'?action=logout');
            // TRY TO LOG IN WITH THE NEW ADMIN
                    // TEST WRONG PASSWORD
                    $this->get(osc_admin_base_url());
                    $this->setField('user', 'newtestadmin');
                    $this->setField('password', 'wrong_password');
                    $this->click('Log in');
                    $this->assertNoText('Visit website');
                    
                    // TEST WRONG USER
                    $this->get(osc_admin_base_url());
                    $this->setField('user', 'wrong_newtestadmin');
                    $this->setField('password', 'password');
                    $this->click('Log in');
                    $this->assertNoText('Visit website');
                    
                    // TEST WRONG USER & PASSWORD
                    $this->get(osc_admin_base_url());
                    $this->setField('user', 'wrong_newtestadmin');
                    $this->setField('password', 'wrong_password');
                    $this->click('Log in');
                    $this->assertNoText('Visit website');
                    
                    // TEST CORRECT LOGIN
                    $this->get(osc_admin_base_url());
                    $this->setField('user', 'newtestadmin');
                    $this->setField('password', 'password');
                    $this->click('Log in');
                    $this->assertText('Visit website');
                // LOG OUT
                $this->get(osc_admin_base_url(true).'?action=logout');

                    // TEST CORRECT LOGIN WITH THE CORRECT ADMIN
                    $this->get(osc_admin_base_url());
                    $this->setField('user', 'testadmin');
                    $this->setField('password', 'password');
                    $this->click('Log in');
                    $this->assertText('Visit website');

                // DELETE THE NEW TEST ADMIN
                $admin = Admin::newInstance()->findByEmail('new_testadmin@test.net');
                $this->assertTrue($this->get(osc_admin_base_url(true).'?page=admins&action=delete&id[]='.$admin['pk_i_id']));
                
                
        // EDIT OUR ADMIN PROFILE
        // This is the same form as to edit any other admin, so it should work on everyone
        $this->get(osc_admin_base_url().'?page=admins&action=edit');
            // PUT OLD WRONG PASSWORD
            $this->setField('s_name', 'Test Administrator Edited');
            $this->setField('s_email', 'edited_testadmin@test.net');
            $this->setField('s_username', 'newtestadmin');
            $this->setField('old_password', 'wrong_password');
            $this->setField('s_password', 'new_password');
            $this->setField('s_password2', 'new_password');
            $this->click('Update');
            // LOGOUT AND TRY TO LOG IN WITH NEW PASSWORD
            $this->get(osc_admin_base_url(true).'?action=logout');
            $this->get(osc_admin_base_url());
            $this->setField('user', 'newtestadmin');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Visit website');

        // LOGIN WITH THE CORRECT ADMIN
        $this->get(osc_admin_base_url());
        $this->setField('user', 'testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Visit website');
        $this->get(osc_admin_base_url().'?page=admins&action=edit');
            // PUT NEW PASSWORDS DONT MATCH
            $this->setField('s_name', 'Test Administrator Edited');
            $this->setField('s_email', 'edited_testadmin@test.net');
            $this->setField('s_username', 'newtestadmin');
            $this->setField('old_password', 'password');
            $this->setField('s_password', 'new_password');
            $this->setField('s_password2', 'wrong_new_password');
            $this->click('Update');
            // LOGOUT AND TRY TO LOG IN WITH NEW PASSWORD
            $this->get(osc_admin_base_url(true).'?action=logout');
            $this->get(osc_admin_base_url());
            $this->setField('user', 'newtestadmin');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertNoText('Visit website');

        // LOGIN WITH THE CORRECT ADMIN
        $this->get(osc_admin_base_url());
        $this->setField('user', 'testadmin');
        $this->setField('password', 'password');
        $this->click('Log in');
        $this->assertText('Visit website');
        $this->get(osc_admin_base_url().'?page=admins&action=edit');
            // PUT CORRECT DATA
            $this->setField('s_name', 'Test Administrator Edited');
            $this->setField('s_email', 'edited_testadmin@test.net');
            $this->setField('s_username', 'newtestadmin');
            $this->setField('old_password', 'password');
            $this->setField('s_password', 'new_password');
            $this->setField('s_password2', 'new_password');
            $this->click('Update');
            // LOGOUT AND TRY TO LOG IN WITH NEW PASSWORD
            $this->get(osc_admin_base_url(true).'?action=logout');
            $this->get(osc_admin_base_url());
            $this->setField('user', 'newtestadmin');
            $this->setField('password', 'new_password');
            $this->click('Log in');
            $this->assertText('Visit website');

        // CHANGE BACK TO NORMAL, MAYBE WE NEED TO LOG OUT/IN SOME MORE TIME
        $this->get(osc_admin_base_url().'?page=admins&action=edit');
        // PUT CORRECT DATA
        $this->setField('s_name', 'Test Administrator');
        $this->setField('s_email', 'testadmin@test.net');
        $this->setField('s_username', 'testadmin');
        $this->setField('old_password', 'new_password');
        $this->setField('s_password', 'password');
        $this->setField('s_password2', 'password');
        $this->click('Update');
            // LOGOUT AND TRY TO LOG IN WITH NEW DATA
            $this->get(osc_admin_base_url(true).'?action=logout');
            $this->get(osc_admin_base_url());
            $this->setField('user', 'testadmin');
            $this->setField('password', 'password');
            $this->click('Log in');
            $this->assertText('Visit website');
            
            
        
        
        // We did our tests, lets get back to normal
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
    }        
    
}


?>
