<?php
require_once dirname(__FILE__) . '/../../../Selenium.php';

require_once(dirname(__FILE__).'/../../simpletest.php');
require_once(dirname(__FILE__).'/MyWebTestCase.php');


abstract class FrontendTest extends MyWebTestCase {

    protected $selenium;
    protected $_email;
    protected $_password;

    function __construct($label = false) {
        parent::__construct($label);
    }

    function setUp()
    {
        include dirname(__FILE__).'/config_test.php';

        $this->_email    = $email;
        $this->_password = $password;

        $this->selenium = new Testing_Selenium( $browser, "http://localhost/");
        $this->selenium->start();
        $this->selenium->setSpeed( $speed );

        $this->selenium->windowMaximize();
    }

    function tearDown()
    {
        $this->selenium->stop();
    }

    /**
     * Do register if exist 'Register for a free account' link
     * @param string $mail
     * @param string $pass
     * @param string $pass2
     */
    function doRegisterUser($mail = NULL, $pass = NULL, $pass2 = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        if( is_null($pass) ) $pass = $this->_password;
        if( is_null($pass2) ) $pass2 = $pass;

        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=Register for a free account");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->type('s_name'      , 'testuser');
        $this->selenium->type('s_password'  , $pass);
        $this->selenium->type('s_password2' , $pass2);
        $this->selenium->type('s_email'     , $mail);

        $this->selenium->click("//button[text()='Create']");
        $this->selenium->waitForPageToLoad("10000");

        $user = User::newInstance()->findByEmail($mail);
        return @$user['pk_i_id'];
    }

    /**
     * Do Login at frontend, via login link at header.
     *
     * @param string $mail
     * @param string $pass
     */
    function loginWith($mail = NULL, $pass = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        if( is_null($pass) ) $pass = $this->_password;

        $this->selenium->open( osc_base_url() );
        $this->selenium->click("login_open");
        $this->selenium->type("email", $mail);
        $this->selenium->type("password", $pass);

        $this->selenium->click("//button[@type='submit']");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->click("link=My account");
        $this->selenium->waitForPageToLoad("10000");
    }

    /**
     * Do logout at frontend, via logout link at header.
     */
    function logout()
    {
        $this->selenium->open( osc_base_url() );
        $this->selenium->click("link=Logout");
        $this->selenium->waitForPageToLoad("10000");
    }

    /**
     * Remove all related with $mail user email
     * @param string $mail
     */
    function removeUserByMail( $mail = NULL )
    {
        if( is_null($mail) ) $mail = $this->_email;
        $user = User::newInstance()->findByEmail($mail);
        User::newInstance()->deleteUser($user['pk_i_id']);
    }

    public function insertItem($parentCat, $cat, $title, $description, $price, $regionId, $cityId, $cityArea, $aPhotos, $user, $email , $logged = 0)
    {
        $this->selenium->open( osc_base_url() );

        $this->selenium->click("link=Publish your ad for free");
        $this->selenium->waitForPageToLoad("10000");

        $this->selenium->select("catId", "label=regexp:\\s*$cat");
        sleep(2);
        $this->selenium->type("title[en_US]", $title);
        $this->selenium->type("description[en_US]", $description);
        $this->selenium->type("price", "12".osc_locale_thousands_sep()."34".osc_locale_thousands_sep()."56".osc_locale_dec_point()."78".osc_locale_dec_point()."90");
        $this->selenium->fireEvent("price", "blur");
        sleep(2);
        $this->assertTrue($this->selenium->getValue("price")=="123456".osc_locale_dec_point()."78", "Check price correction input");
        $this->selenium->type("price", $price);
        $this->selenium->select("currency", "label=Euro €");
        if($regionId!=NULL) {
            $this->selenium->select("countryId", "label=Spain");
            $this->selenium->type('id=region', $regionId);
    //        $this->selenium->click('id=ui-active-menuitem');
            $this->selenium->type('id=city', $cityId);
    //        $this->selenium->click('id=ui-active-menuitem');
        }
        if($cityArea==NULL) {
            $this->selenium->type("cityArea", "my area");
        } else {
            $this->selenium->type("cityArea", $cityArea);
        }
        $this->selenium->type("address", "my address");
        if( count($aPhotos) > 0 ) {
            sleep(2);

            echo LIB_PATH."simpletest/test/osclass/".$aPhotos[0] ;
            echo "<br/>";
            flush();

            $this->selenium->type("photos[]", LIB_PATH."simpletest/test/osclass/".$aPhotos[0]);
            for($k=1;$k<count($aPhotos);$k++) {
                sleep(2);

                echo LIB_PATH."simpletest/test/osclass/".$aPhotos[$k];
                echo "<br/>";
                flush();

                $this->selenium->click("link=Add new photo");
                $this->selenium->type("//div[@id='p-".$k."']/input", LIB_PATH."simpletest/test/osclass/".$aPhotos[$k]);
            }
        }

        $this->selenium->type("contactName" , $user);
        $this->selenium->type("contactEmail", $email);

        $this->selenium->click("//button[text()='Publish']");
        $this->selenium->waitForPageToLoad("10000");
    }

    function _createAlert($email, $success = true)
    {
        // search only items with picture
        $this->selenium->open( osc_search_url() );
        $this->selenium->waitForPageToLoad("10000");

        // create alert invalid email
        $this->selenium->click('alert_email');
        $this->selenium->type('alert_email', $email);
        $this->selenium->click("xpath=//form[@id='sub_alert']/button");
        sleep(2);

        // verify alert
        $aAuxAlert = Alerts::newInstance()->findByEmail($email);
        if( $success ) {
            $this->assertTrue(count($aAuxAlert) == 1, 'Search - create alert');
        } else {
            $this->assertTrue(count($aAuxAlert) == 0, 'Search - create alert');
        }
    }

    function _lastItemId()
    {
        // get last id from t_item.
        $item   = Item::newInstance()->dao->query('select pk_i_id from '.DB_TABLE_PREFIX.'t_item order by pk_i_id DESC limit 0,1');
        $aItem  = $item->result();
        return $aItem[0]['pk_i_id'];
    }
}
?>