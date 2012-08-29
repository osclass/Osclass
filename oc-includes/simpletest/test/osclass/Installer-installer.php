<?php
class Installer_installer extends InstallerTest {
    
    /*           TESTS          */
    function testInstaller1()
    {
        require(dirname(__FILE__).'/config_test.php');
        flush();
        $this->clean();
        
        $config_file = ABS_PATH . "config.php";
        if( !file_exists($config_file) ) {
            $this->can_continue = true;
            
            
            $this->selenium->open( osc_get_absolute_url() . "oc-includes/osclass/install.php" );
            
            // Test locale
            $this->selenium->type("install_locale", "en_US");
            sleep(3);
            $this->assertTrue( $this->selenium->isTextPresent("MySQLi extension for PHP"), "Locale didn't changed correctly - test 2" );
            $this->selenium->type("install_locale", "es_ES");
            sleep(3);
            $this->assertTrue( $this->selenium->isTextPresent("Extensión MySQLi para PHP"), "Locale didn't changed correctly - test 1" );

            // step 1
            $this->selenium->click("css=input.button");
            $this->selenium->waitForPageToLoad("30000");

            // step 2
            $this->assertTrue( $this->selenium->isTextPresent("Información de la base de datos"), "IS NOT STEP 2 ! (databse information)" );
            $this->selenium->type("dbhost", $db_host);
            $this->selenium->type("dbname", $db_name);
            $this->selenium->type("username", $db_user);
            $this->selenium->type("password", $db_pass);
            $this->selenium->click("css=span");
            $this->selenium->click("createdb");
            $this->selenium->type("admin_username", $db_user);
            $this->selenium->type("admin_password", $db_pass);
            $this->selenium->type("tableprefix", "test_");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");

            // step 3
            if( $this->assertFalse($this->selenium->isTextPresent("Hay tablas con el mismo nombre en la base de datos. Cambia el prefijo o la base de datos y prueba de nuevo."), "NEED DROP DATABASE osclass, for continue the installation!") ) {
                $this->can_continue = false;
            }
            $this->assertTrue( $this->selenium->isTextPresent("Localización"), "IS NOT STEP 3 ! (information needed)" );
            $this->selenium->type("s_name", "admin");
            $this->selenium->type("s_passwd", "admin");
            
            $this->selenium->type("webtitle", "test_web_osclass");
            $this->selenium->type("email", $email);

            $this->selenium->type("xpath=//input[@id='t_country']", "spai");
            $this->selenium->keyDown( "xpath=//input[@id='t_country']", "n" ) ;
            $this->selenium->keyPress( "xpath=//input[@id='t_country']", "\\13");
            sleep(2);
            $this->selenium->click("xpath=//div[@id='location']/div[@id='country-box']/div[@id='a_country']/ul/li/a");
            $this->selenium->click("link=Next");
            $this->selenium->waitForPageToLoad("600000");
            // step 4
            $this->assertTrue($this->selenium->isTextPresent("Categorías"), "IS NOT STEP 4 ! (categories)");
            $this->selenium->click("link=Check all");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");
            // step 5
            $this->assertTrue($this->selenium->isTextPresent("OSClass has been installed."), "OSClass has NOT been installed!");
        } else {
            echo "<div style='background-color: red; color: white;padding-left:15px;'>$config_file EXIST, CANNOT INSTALL OSCLASS IF EXIST</div>";
            $this->can_continue = false;
        }
    }
    
    
    
    
    /*           TESTS          */
    function testInstaller2()
    {
        require(dirname(__FILE__).'/config_test.php');
        flush();
        $this->clean();
       
        $config_file = ABS_PATH . "config.php";
        if( !file_exists($config_file) ) {
            $this->can_continue = true;
            
            $this->selenium->open( osc_get_absolute_url() . "oc-includes/osclass/install.php" );
            
            // Test locale
            $this->selenium->type("install_locale", "es_ES");
            sleep(3);
            $this->assertTrue( $this->selenium->isTextPresent("Extensión MySQLi para PHP"), "Locale didn't changed correctly - test 1" );
            $this->selenium->type("install_locale", "en_US");
            sleep(3);
            $this->assertTrue( $this->selenium->isTextPresent("MySQLi extension for PHP"), "Locale didn't changed correctly - test 2" );

            // step 1
            $this->selenium->click("css=input.button");
            $this->selenium->waitForPageToLoad("30000");

            // step 2
            $this->assertTrue( $this->selenium->isTextPresent("Database information"), "IS NOT STEP 2 ! (databse information)" );
            $this->selenium->type("dbhost", $db_host);
            $this->selenium->type("dbname", $db_name);
            $this->selenium->type("username", $db_user);
            $this->selenium->type("password", $db_pass);
            $this->selenium->click("css=span");
            $this->selenium->click("createdb");
            $this->selenium->type("admin_username", $db_user);
            $this->selenium->type("admin_password", $db_pass);
            $this->selenium->type("tableprefix", "test_");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");

            // step 3
            if( $this->assertFalse($this->selenium->isTextPresent("There are tables with the same name in the database. Change the table prefix or the database and try again."), "NEED DROP DATABASE osclass, for continue the installation!") ) {
                $this->can_continue = false;
            }
            $this->assertTrue( $this->selenium->isTextPresent("Information needed"), "IS NOT STEP 3 ! (information needed)" );
            $this->selenium->type("s_name", "admin");
            $this->selenium->type("s_passwd", "admin");
            
            $this->selenium->type("webtitle", "test_web_osclass");
            $this->selenium->type("email", $email);

            $this->selenium->type("xpath=//input[@id='t_country']", "spai");
            $this->selenium->keyDown( "xpath=//input[@id='t_country']", "n" ) ;
            $this->selenium->keyPress( "xpath=//input[@id='t_country']", "\\13");
            sleep(2);
            $this->selenium->click("xpath=//div[@id='location']/div[@id='country-box']/div[@id='a_country']/ul/li/a");
            $this->selenium->click("link=Next");
            $this->selenium->waitForPageToLoad("600000");
            // step 4
            $this->assertTrue($this->selenium->isTextPresent("Categories"), "IS NOT STEP 4 ! (categories)");
            $this->selenium->click("link=Check all");
            $this->selenium->click("submit");
            $this->selenium->waitForPageToLoad("30000");
            // step 5
            $this->assertTrue($this->selenium->isTextPresent("OSClass has been installed."), "OSClass has NOT been installed!");
        } else {
            echo "<div style='background-color: red; color: white;padding-left:15px;'>$config_file EXIST, CANNOT INSTALL OSCLASS IF EXIST</div>";
            $this->can_continue = false;
        }
    }
    
}
?>
