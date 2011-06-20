<?php

    /* * ***************************
     * CONFIGURATION - EDIT THIS *
     * *************************** */

    $sandbox = false; 
    if( defined('PAYPAL_SANDBOX') ) {
        $sandbox = true;
    }
    $email_admin = true;

    /* * ****************************
     * STANDARD PAYPAL NOTIFY URL *
     *    NOT MODIFY BELOW CODE   *
     * **************************** */
    // Read the post from PayPal and add 'cmd'
    $header = '';
    $req    = 'cmd=_notify-validate';
    if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exits = true;
    }
    
    foreach ($_POST as $key => $value) {
        // Handle escape characters, which depends on setting of magic quotes 
        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }

    // Post back to PayPal to validate
    $header .= 'POST /cgi-bin/webscr HTTP/1.0\r\n';
    $header .= 'Content-Type: application/x-www-form-urlencoded\r\n';
    $header .= 'Content-Length: ' . strlen($req) . '\r\n\r\n';

    if ($sandbox) {
        $fp = fsockopen('www.sandbox.paypal.com', 80, $errno, $errstr, 30);
    } else {
        $fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
    }

    // Process validation from PayPal 
    if ($fp) {
        // NO HTTP ERROR
        fputs($fp, $header . $req);
        while (!feof($fp)) {
            $res = fgets($fp, 1024);
            if (strcmp($res, 'VERIFIED') == 0) {
                // TODO:
                // Check that txn_id has not been previously processed
                // Check that receiver_email is your Primary PayPal email
                // Check that payment_amount/payment_currency are correct
                // Process payment
                if ($_REQUEST['payment_status'] == 'Completed') {
                    // Load stuff
                    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
                    require_once ABS_PATH . 'oc-load.php';
                    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';
                    // Have we processed the payment already?
                    $conn    = getConnection();
                    $payment = $conn->osc_dbFetchResult("SELECT * FROM %st_paypal_log WHERE s_code = '%s'", DB_TABLE_PREFIX, Params::getParam('txn_idn'));
                    if (!isset($payment['pk_i_id'])) {
                        $data = explode('x', Params::getParam('item_number1'));
                        paypal_save_log(Params::getParam('item_name1'), Params::getParam('txn_id'), Params::getParam('payment_gros'), Params::getParam('mc_currency'), Params::getParam('payer_email'), '0', $data[2], Params::getParam('item_number1'), 'PAYPAL');

                        if ($data[0] == '101') {
                            // PUBLISH FEE
                            $conn->osc_dbExec("UPDATE %st_paypal_publish SET dt_date = '%s', b_paid = '1', fk_i_paypal_id = '%d' WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, date('Y-m-d H:i:s'), $paypal_id, $rpl[1]);
                        } else if ($data[0] == '201') {
                            // PREMIUM FEE
                            $paid = $conn->osc_dbFetchResult("SELECT * FROM %st_paypal_premium WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $rpl[1]);
                            if ($paid) {
                                $conn->osc_dbExec("UPDATE %st_paypal_premium SET dt_date = '%s', fk_i_paypal_id = '%d' WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, date('Y-m-d H:i:s'), $paypal_id, $rpl[1]);
                            } else {
                                $conn->osc_dbExec("INSERT INTO  %st_paypal_premium (fk_i_item_id, dt_date, fk_i_paypal_id) VALUES ('%d',  '%s',  '%s')", DB_TABLE_PREFIX, $rpl[1], date('Y-m-d H:i:s'), $paypal_id);
                            }
                        }
                    } // ELSE THE PAY IS ALREADY PROCESSED

                    $emailtext = '';
                    foreach ($_REQUEST as $key => $value) {
                        $emailtext .= $key . ' = ' . $value . '\n\n';
                    }
                    mail('juanramon.diaz@gmail.com', 'OSCLASS PAYPAL DEBUG', $emailtext . '\n\n' . $req);
                }
            } else if (strcmp($res, 'INVALID') == 0) {
                // INVALID: Do nothing
            }
        }
        fclose($fp);
    }
    
?>