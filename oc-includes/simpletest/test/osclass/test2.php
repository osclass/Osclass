<?php

    require_once(ABS_PATH . 'oc-includes/osclass/db.php');
    require_once(ABS_PATH . 'oc-includes/osclass/classes/DAO.php');
    
    class TestOfDirectoryPermissions extends UnitTestCase {
        function testCorrectPermissionsInUpload() {
            $perm = substr(sprintf('%o', fileperms(ABS_PATH . 'oc-content/uploads')), -4) ;
            echo $perm ;
            $this->assertTrue( ($perm > 0x0644) ) ;
        }
    }

?>