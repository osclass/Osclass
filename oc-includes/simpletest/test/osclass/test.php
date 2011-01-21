<?php

    require_once(ABS_PATH . 'oc-includes/osclass/db.php');
    require_once(ABS_PATH . 'oc-includes/osclass/classes/DAO.php');
    require_once(ABS_PATH . 'oc-includes/osclass/model/City.php');
    
    
    class TestOfModelCategory extends UnitTestCase {
        function testLogCreatesNewFileOnFirstMessage() {
            $city = new City() ;
            $cityTableName = $city->getTableName() ;
            $this->assertEqual($cityTableName, "oc_t_city");
        }
    }

?>
