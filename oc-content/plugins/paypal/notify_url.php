<?php 

/*****************************
 * CONFIGURATION - EDIT THIS *
 *****************************/
$sandbox = true; // true if you're testing your installation, set to false once it's ready for production
$email_admin = true;



/******************************
 * STANDARD PAYPAL NOTIFY URL *
 *    NOT MODIFY BELOW CODE   *
 ******************************/
// Read the post from PayPal and add 'cmd'
$header = "";
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) { $get_magic_quotes_exits = true;};
foreach ($_POST as $key => $value) {
    // Handle escape characters, which depends on setting of magic quotes 
    if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
} 

// Post back to PayPal to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

if($sandbox) {
    $fp = fsockopen ('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
} else {
    $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
}
 
// Process validation from PayPal 
if (!$fp) {
    // HTTP ERROR : Do nothing, may the network be down, Paypal will try again to contact us
} else { 
    // NO HTTP ERROR
    fputs ($fp, $header . $req);
    while (!feof($fp)) {
        $res = fgets ($fp, 1024);
        if (strcmp ($res, "VERIFIED") == 0) {
            // TODO:
            // Check that txn_id has not been previously processed
            // Check that receiver_email is your Primary PayPal email
            // Check that payment_amount/payment_currency are correct
            // Process payment
            if($_REQUEST['payment_status']=="Completed") {
                // Load stuff
                require_once "../../../oc-load.php";
                require_once osc_plugins_path().osc_plugin_folder(__FILE__).'functions.php';
                // Have we processed the payment already?
                $conn = getConnection();
                $conn->osc_dbFetchResult("SELECT * FROM %st_paypal_log WHERE s_code = '%s'", DB_TABLE_PREFIX, Params::getParam('txn_idn'));
                
                $emailtext = "";
                foreach ($_REQUEST as $key => $value){ 
                  $emailtext .= $key . " = " .$value ."\n\n"; 
                } 
                mail('nodani@gmail.com', "OSCLASS PAYPAL DEBUG", $emailtext . "\n\n" . $req); 
            }
        } else if (strcmp ($res, "INVALID") == 0) { 
            // INVALID: Do nothing
        } 
    } 
    fclose ($fp); 
}


?>
