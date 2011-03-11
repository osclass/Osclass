<?php
require_once('../../autorun.php');
require_once('../../web_tester.php');
require_once('../../reporter.php');

// LOAD OSCLASS
require_once '../../../../oc-load.php';
require_once LIB_PATH . 'Selenium.php';

class TestOfAdminItems extends WebTestCase {

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

    /**
     * insert new item
     * Comprobar:
     *  osc_reg_user_post() => Only allow registered users to post items
     *  osc_users_enabled() => Users not enabled
     *
     * REQUIRE: user logged in
     */
    function testItemInsert()
    {
        echo "<div style='background-color: green; color: white;'>FRONTEND - <h2>Item insert</h2> -</div>";
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Item insert - LOGIN </div>";
        $this->loginCorrect() ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Item insert - CREATE A NEW ITEM</div>";
        $this->createPage('test_page_example') ;
        flush();
        echo "<div style='background-color: green; color: white;padding-left:15px;'>Admin - Item insert - DELETE INSERTED PAGE</div>";
        $this->deletePage('test_page_example') ;
        flush();
    }
}

?>
