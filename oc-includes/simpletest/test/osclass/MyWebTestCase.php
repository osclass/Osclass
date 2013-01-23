<?php
require_once(dirname(__FILE__).'/../../web_tester.php');

class MyWebTestCase extends WebTestCase {

    function __construct($label = false) {
        parent::__construct($label);
    }

    function assert($expectation, $compare, $message = '%s')
    {
        $res = parent::assert( $expectation, $compare, $message );
        
        $bt = debug_backtrace();
        $function = $bt[2]['function'];

        $date = $function."_".time().".png";
        $path = "/var/www/vm-test-osclass.office/subdomains/images_test/httpdocs/img/";
        $img  = $path.$date;
        
        if(!$res) {
            $a = "<a target='_blank' href='http://images_test.vm-test-osclass.office/img/$date'>Image test failed</a><br /><br />";
            $this->reporter->addFail($message . " " . $a);
            $cmd = "DISPLAY=:1 import -window root ".$img;
            system($cmd);
            $this->selenium->captureScreenshot($date);
        }
        
        return $res;
    }
}

?>