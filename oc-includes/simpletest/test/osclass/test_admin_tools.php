<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminTools extends WebTestCase {

    private $selenium;

    function setUp()
    {
        $conn = getConnection();
        $conn->osc_dbExec(sprintf("INSERT INTO `%st_admin` (`s_name` ,`s_username` ,`s_password` ,`s_secret` ,`s_email`) VALUES ('Test Admin','testadmin','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8','mvqdnrpt','testadmin@test.net')", DB_TABLE_PREFIX));

        echo "<br><div style='background-color: Wheat; color: black;'>init test</div>";

        $this->selenium = new Testing_Selenium("*firefox", "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed("150");
    }

    function tearDown()
    {
        $this->selenium->stop();
        $admin = Admin::newInstance()->findByEmail('testadmin@test.net');
        Admin::newInstance()->delete(array('pk_i_id' =>$admin['pk_i_id']));
        echo "<div style='background-color: Wheat; color: black;'>end test</div>";
        flush();
    }
    /*           TESTS          */
//    function testImportData()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testImportData</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testImportData - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testImportData - IMPORTING DATA</div>";
//        $this->importData();
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testImportData - IMPORTING DATA FOR RESTORE</div>";
//        $this->importDataRestore();
//        flush();
//    }
//
//    function testImportDataFail()
//    {
//        echo "<div style='background-color: green; color: white;'><h2>testImportDataFail</h2></div>";
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testImportDataFail - LOGIN </div>";
//        $this->loginCorrect() ;
//        flush();
//        echo "<div style='background-color: green; color: white;padding-left:15px;'>testImportDataFail - IMPORTING DATA FAIL</div>";
//        $this->importDataError();
//        flush();
//    }

    function testBackupSql()
    {
        echo "<div style='background-color: green; color: white;'><h2>testBackup</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testBackup - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testBackup - BACKUP SQL</div>";
        $this->backupSql();
        flush();
    }

    function testBackupZip()
    {
        echo "<div style='background-color: green; color: white;'><h2>testBackup</h2></div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testBackup - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>testBackup - BACKUP ZIP</div>";
        $this->backupOsclassZip();
        flush();
    }

    // » Upgrade OSClass

    // » Regenerate thumbnails

    /*      PRIVATE FUNCTIONS       */
    private function loginCorrect()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->waitForPageToLoad(10000);

        // if you are logged fo log out
        if( $this->selenium->isTextPresent('Log Out') ){
            $this->selenium->click('Log Out');
            $this->selenium->waitForPageToLoad(1000);
        }

        $this->selenium->type('user', 'testadmin');
        $this->selenium->type('password', 'password');
        $this->selenium->click('submit');
        $this->selenium->waitForPageToLoad(1000);

        if( !$this->selenium->isTextPresent('Log in') ){
            $this->assertTrue("todo bien");
        } else {
            $this->assertFalse("can't loggin");
        }
    }

    private function importData()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("sql", LIB_PATH."simpletest/test/osclass/test.sql");

        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Import complete"), "Cant import a sql file! ERROR");
    }

    private function importDataError()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("sql", LIB_PATH."simpletest/test/osclass/img_test1.gif");

        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("There was a problem importing data to the database"), "Cant import image as sql! ERROR");
    }

    private function importDataRestore()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Import data");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type("sql", LIB_PATH."simpletest/test/osclass/test_restore.sql");

        $this->selenium->click("xpath=//input[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->assertTrue($this->selenium->isTextPresent("Import complete"), "Cant import a sql file! ERROR");
    }

    private function backupSql()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Backup data");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//p[2]/button");
        $this->selenium->waitForPageToLoad("300000");

        $this->assertTrue($this->selenium->isTextPresent("Backup has been done properly"), "Backup sql! ERROR");
    }

    private function backupOsclassZip()
    {
        $this->selenium->open( osc_admin_base_url(true) );
        $this->selenium->click("link=Tools");
        $this->selenium->click("link=» Backup data");
        $this->selenium->waitForPageToLoad("30000");

        $this->selenium->click("xpath=//p[3]/button");
        $this->selenium->waitForPageToLoad("3000000");

        sleep(2);
        $this->assertTrue($this->selenium->isTextPresent("Archiving successful!"), "Backup zip! ERROR");
    }
}
?>
