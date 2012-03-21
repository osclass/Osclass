<?php
require_once('../../scorer.php');
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
        $this->fails = "";
    }

    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    function paintHeader($test_name) {
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
        
        if(($this->getFailCount() + $this->getExceptionCount()) > 0) {
            $subject = '[ERROR] Test results';
        } else {
            $subject = '[OK] Test results';
        }
        $body = $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
        $body .= " test cases complete:\n";
        $body .= "<strong>" . $this->getPassCount() . "</strong> passes, ";
        $body .= "<strong>" . $this->getFailCount() . "</strong> fails and ";
        $body .= "<strong>" . $this->getExceptionCount() . "</strong> exceptions.<br/>\n";
        require_once('../../../osclass/utils.php');
        osc_sendMail(array(
            'to' => 'testing@osclass.org',
            'to_name' => 'OSClass Testing',
            'from' => 'testing@osclass.org',
            'from_name' => 'OSClass Testing',
            'subject' => $subject,
            'body' => $body
        ));
        
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
        print $fail;
    }

    function  paintPass($message) {
        parent::paintPass($message);
        print "<span class=\"pass\">Pass</span>: ";
        $message = preg_replace('/(at\s\[)/', 'at <br>         [', $this->htmlEntities($message));
        print " -&gt; " . $message . "<br />\n";
        flush();
    }

    function  paintCaseStart($test_name) {
        parent::paintCaseStart($test_name);
        print "<h2>$test_name</h2>";
        flush();
    }

    function  paintMethodStart($test_name) {
        parent::paintMethodStart($test_name);
        print "<h4>$test_name</h4>";
    }
    /**
     *    Paints a PHP error.
     *    @param string $message        Message is ignored.
     *    @access public
     */
    function paintError($message) {
        parent::paintError($message);
        print "<span class=\"fail\">Exception</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; <strong>" . $this->htmlEntities($message) . "</strong><br />\n";
    }

    /**
     *    Paints a PHP exception.
     *    @param Exception $exception        Exception to display.
     *    @access public
     */
    function paintException($exception) {
        parent::paintException($exception);
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

    /**
     *    Prints the message for skipping tests.
     *    @param string $message    Text of skip condition.
     *    @access public
     */
    function paintSkip($message) {
        parent::paintSkip($message);
        print "<span class=\"pass\">Skipped</span>: ";
        $breadcrumb = $this->getTestList();
        array_shift($breadcrumb);
        print implode(" -&gt; ", $breadcrumb);
        print " -&gt; " . $this->htmlEntities($message) . "<br />\n";
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