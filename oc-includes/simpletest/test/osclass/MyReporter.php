<?php
require_once(dirname(__FILE__).'/../../scorer.php');
/**
 *    Sample minimal test displayer. Generates only
 *    failure messages and a pass count.
 *    @package SimpleTest
 *    @subpackage UnitTester
 */
class MyReporter extends SimpleReporter {
    private $character_set;
    private $fails;
    
    /**
     *    Does nothing yet. The first output will
     *    be sent on the first test start. For use
     *    by a web browser.
     *    @access public
     */
    function __construct($character_set = 'ISO-8859-1') {
        parent::__construct();
        $this->character_set = $character_set;
        if(PHP_SAPI==='cli') {
            $this->fails = "";
        } else {
            $this->fails  = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
            $this->fails .= "<html>\n<head>\n<title></title><style type=\"text/css\"> ".$this->getCss() . "\n</style>\n</head><body>\n";
        }
    }

    /*
     * append to $fail string
     */
    function addFail($str)
    {
        $this->fails .= $str;
    }
    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    function paintHeader($test_name) {
        if(PHP_SAPI==='cli') {
            print "** START TEST ** ".$test_name. " ** START TEST **".PHP_EOL;
            flush();
        } else {
            $this->sendNoCacheHeaders();
            print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
            print "<html>\n<head>\n<title>$test_name</title>\n";
            print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=" .
                    $this->character_set . "\">\n";
            print "<style type=\"text/css\">\n";
            print $this->getCss() . "\n";
            print "</style>\n";
            print "</head>\n<body>\n";
            print "<h1>$test_name</h1>\n";
            flush();
        }
    }

    /**
     *    Send the headers necessary to ensure the page is
     *    reloaded on every request. Otherwise you could be
     *    scratching your head over out of date test data.
     *    @access public
     */
    static function sendNoCacheHeaders() {
        if (! headers_sent()) {
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
        }
    }

    /**
     *    Paints the CSS. Add additional styles here.
     *    @return string            CSS code as text.
     *    @access protected
     */
    protected function getCss() {
        return ".fail { background-color: inherit; color: red; }" .
                ".pass { background-color: inherit; color: green; }" .
                " pre { background-color: lightgray; color: inherit; }";
    }

    /**
     *    Paints the end of the test with a summary of
     *    the passes and failures.
     *    @param string $test_name        Name class of test.
     *    @access public
     */
    function paintFooter($test_name) {
        
        // add end </html>
        global $test_str;
        
        if(PHP_SAPI==='cli') {
            print " * * *  REPORTER  * * * ".PHP_EOL;
            print "[".$test_name."] ".$this->getTestCaseProgress() . "/" . $this->getTestCaseCount().PHP_EOL;
            print "\033[1;32m".$this->getPassCount()." ~~ PASSES\033[0m".PHP_EOL;
            print "\033[1;31m".$this->getFailCount()." ## FAILS\033[0m".PHP_EOL;
            print "\033[1;33m".$this->getExceptionCount()." %% EXCEPTIONS\033[0m".PHP_EOL;
        } else {
            $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "red" : "green");
            print "<div style=\"";
            print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
            print "\">";
            print "[$test_name]<br>";
            print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
            print " test cases complete:\n";
            print "<strong>" . $this->getPassCount() . "</strong> passes, ";
            print "<strong>" . $this->getFailCount() . "</strong> fails and ";
            print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
            print "</div>\n";
            print "</body>\n</html>\n";
        };
        
        if($this->fails!='') {
            $subject = '[ERROR] Test results';
        } else {
            $subject = '[OK] Test results';
        }
        $body = $test_str.$this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
        $body .= " test cases complete:\n";
        $body .= "*" . $this->getPassCount() . "* passes, ";
        $body .= "*" . $this->getFailCount() . "* fails and ";
        $body .= "*" . $this->getExceptionCount() . "* exceptions.\r\n\r";
        $talker_text = $body;
        $body .= "<br/>";
        
        $this->fails .= "</body></html>";
        $body .= $this->fails;
        
        
        $cabeceras  = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        mail("testing@osclass.org", $subject." _mail_", $body, $cabeceras);
        
        require(dirname(__FILE__)."/config_test.php");
        if($talker_room!='' && $talker_token!='') {
            require(dirname(__FILE__)."/../../../talkerplugin/class.talker.php");
            $talker = new Talker();
            $talker->connect($talker_room, $talker_token);
            $talker->send_message($talker_text);
        }
    }

    /**
     *    Paints the test failure with a breadcrumbs
     *    trail of the nesting test suites below the
     *    top level test.
     *    @param string $message    Failure message displayed in
     *                              the context of the other tests.
     */
    function paintFail($message) {
        parent::paintFail($message);
        $fail = "<span class=\"fail\">Fail</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        $fail .= implode(" -&gt; ", $breadcrumb);
        $fail .= " -&gt; " . $this->htmlEntities($message);
        $fail .= "<br />\n";
        $this->fails .= $fail;
        if(PHP_SAPI==='cli') {
            array_shift($breadcrumb);
            $failcli = implode(" > ", $breadcrumb);
            $failcli .= " > " . $this->htmlEntities($message);
            print "\033[1;31m## FAIL ##\033[0m ".$failcli.PHP_EOL;
            flush();
        } else {
            print $fail;
        }
    }

    function  paintPass($message) {
        parent::paintPass($message);
        if(PHP_SAPI==='cli') {
            print "\033[1;32m~~ PASS ~~ \033[0m".$this->htmlEntities($message).PHP_EOL;
            flush();
        } else {
            print "<span class=\"pass\">Pass</span>: ";
            $message = preg_replace('/(at\s\[)/', 'at <br>         [', $this->htmlEntities($message));
            print " -&gt; " . $message . "<br />\n";
            flush();
        }
    }

    function  paintCaseStart($test_name) {
        parent::paintCaseStart($test_name);
        if(PHP_SAPI==='cli') {
            print " * * [$test_name] * * ".PHP_EOL;
        } else {
            print "<h2>$test_name</h2>";
        }
        flush();
    }

    function  paintMethodStart($test_name) {
        parent::paintMethodStart($test_name);
        if(PHP_SAPI==='cli') {
            print " * $test_name * ".PHP_EOL;
        } else {
            print "<h4>$test_name</h4>";
        }
        flush();
    }
    /**
     *    Paints a PHP error.
     *    @param string $message        Message is ignored.
     *    @access public
     */
    function paintError($message) {
        parent::paintError($message);
        if(PHP_SAPI==='cli') {
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print "\033[1;33m%% EXCEPTION %%\033[0m ".implode(" > ", $breadcrumb)." > ".$this->htmlEntities($message).PHP_EOL;
        } else {
            print "<span class=\"fail\">Exception</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode(" -&gt; ", $breadcrumb);
            print " -&gt; <strong>" . $this->htmlEntities($message) . "</strong><br />\n";
        }
    }

    /**
     *    Paints a PHP exception.
     *    @param Exception $exception        Exception to display.
     *    @access public
     */
    function paintException($exception) {
        parent::paintException($exception);
        if(PHP_SAPI==='cli') {
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            $message = 'Unexpected exception of type [' . get_class($exception) .
                    '] with message ['. $exception->getMessage() .
                    '] in ['. $exception->getFile() .
                    ' line ' . $exception->getLine() . ']';
            print "\033[1;33m%% EXCEPTION %%\033[0m ".implode(" > ", $breadcrumb)." > ".$this->htmlEntities($message).PHP_EOL;
        } else {
            print "<span class=\"fail\">Exception</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode(" -&gt; ", $breadcrumb);
            $message = 'Unexpected exception of type [' . get_class($exception) .
                    '] with message ['. $exception->getMessage() .
                    '] in ['. $exception->getFile() .
                    ' line ' . $exception->getLine() . ']';
            print " -&gt; <strong>" . $this->htmlEntities($message) . "</strong><br />\n";
        }
    }

    /**
     *    Prints the message for skipping tests.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) {
        parent::paintSkip($message);
        if(PHP_SAPI==='cli') {
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print "\033[1;35m%% SKKIPED %%\033[0m ".implode(" > ", $breadcrumb)." > ".$this->htmlEntities($message).PHP_EOL;
        } else {
            print "<span class=\"pass\">Skipped</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode(" -&gt; ", $breadcrumb);
            print " -&gt; " . $this->htmlEntities($message) . "<br />\n";
        }
    }

    /**
     *    Paints formatted text such as dumped privateiables.
     *    @param string $message        Text to show.
     *    @access public
     */
    function paintFormattedMessage($message) {
        print '<pre>' . $this->htmlEntities($message) . '</pre>';
    }

    /**
     *    Character set adjusted entity conversion.
     *    @param string $message    Plain text or Unicode message.
     *    @return string            Browser readable message.
     *    @access protected
     */
    protected function htmlEntities($message) {
        return htmlentities($message, ENT_COMPAT, $this->character_set);
    }
}
?>