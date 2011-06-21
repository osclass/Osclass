<?php
    /*
     * return.php
     *
     * This page will handle the GetECDetails, and DoECPayment API Calls
     */
    
    //set include
    define('ABS_PATH', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
    require_once ABS_PATH . 'oc-load.php';
    require_once osc_plugins_path() . osc_plugin_folder(__FILE__) . 'functions.php';
    
    //set GET var's to local vars:
    $token   = $_GET['token'];
    $payerid = $_GET['PayerID'];
    //set API Creds, Version, and endpoint:
    //**************************************************//
    // This is where you would set your API Credentials //
    // Please note this is not considered "SECURE" this // 
    // is an example only. It is NOT Recommended to use //
    // this method in production........................//
    //**************************************************//
    $APIUSERNAME  = osc_get_preference('api_username', 'paypal');
    $APIPASSWORD  = osc_get_preference('api_password', 'paypal');
    $APISIGNATURE = osc_get_preference('api_signature', 'paypal');
    $ENDPOINT     = 'https://api-3t.paypal.com/nvp';
    if( defined('PAYPAL_SANDBOX') ) {
        $ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
    }
    
    $VERSION  = '65.1'; //must be >= 65.1
    //Build the Credential String:
    $cred_str = 'USER=' . $APIUSERNAME . '&PWD=' . $APIPASSWORD . '&SIGNATURE=' . $APISIGNATURE . '&VERSION=' . $VERSION;
    //Build NVP String for GetExpressCheckoutDetails
    $nvp_str  = '&METHOD=GetExpressCheckoutDetails&TOKEN='. urldecode($token);

    //combine the two strings and make the API Call
    $req_str  = $cred_str . $nvp_str;
    $response = PPHttpPost($ENDPOINT, $req_str);
    //based on the API Response from GetExpressCheckoutDetails
    $doec_str = $cred_str . '&METHOD=DoExpressCheckoutPayment'
            . '&TOKEN=' . $token
            . '&PAYERID=' . $payerid
            . '&PAYMENTREQUEST_0_CURRENCYCODE=' . urldecode($response['PAYMENTREQUEST_0_CURRENCYCODE'])
            . '&PAYMENTREQUEST_0_AMT=' . urldecode($response['PAYMENTREQUEST_0_AMT'])
            . '&PAYMENTREQUEST_0_ITEMAMT=' . urldecode($response['PAYMENTREQUEST_0_ITEMAMT'])
            . '&PAYMENTREQUEST_0_TAXAMT=' . urldecode($response['PAYMENTREQUEST_0_TAXAMT'])
            . '&PAYMENTREQUEST_0_DESC=' . urldecode($response['PAYMENTREQUEST_0_DESC'])
            . '&PAYMENTREQUEST_0_PAYMENTACTION=Sale'
            . '&L_PAYMENTREQUEST_0_ITEMCATEGORY0=' . urldecode($response['L_PAYMENTREQUEST_0_ITEMCATEGORY0'])
            . '&L_PAYMENTREQUEST_0_NAME0=' . urldecode($response['L_PAYMENTREQUEST_0_NAME0'])
            . '&L_PAYMENTREQUEST_0_NUMBER0=' . urldecode($response['L_PAYMENTREQUEST_0_NUMBER0'])
            . '&L_PAYMENTREQUEST_0_QTY0=' . urldecode($response['L_PAYMENTREQUEST_0_QTY0'])
            . '&L_PAYMENTREQUEST_0_TAXAMT0=' . urldecode($response['L_PAYMENTREQUEST_0_TAXAMT0'])
            . '&L_PAYMENTREQUEST_0_AMT0=' . urldecode($response['L_PAYMENTREQUEST_0_AMT0'])
            . '&L_PAYMENTREQUEST_0_DESC0=' . urldecode($response['L_PAYMENTREQUEST_0_DESC0'])
            . '&NOTIFYURL=';

    //make the DoEC Call:
    $doresponse = PPHttpPost($ENDPOINT, $doec_str);

    //check Response
    if ($doresponse['ACK'] == 'Success' || $doresponse['ACK'] == 'SuccessWithWarning') {
        //Save transaction to DB
        $rpl = explode('|', Params::getParam('rpl'));
        $product_type = explode('x', urldecode($response['L_PAYMENTREQUEST_0_NUMBER0']));
        $paypal_id    = paypal_save_log(urldecode($response['L_PAYMENTREQUEST_0_NAME0']), urldecode($doresponse['PAYMENTINFO_0_TRANSACTIONID']), urldecode($doresponse['PAYMENTINFO_0_AMT']), urldecode($doresponse['PAYMENTINFO_0_CURRENCYCODE']), isset($response['EMAIL']) ? urldecode($response['EMAIL']) : '', $rpl[0], $rpl[1], $produt_type[0], 'PAYPAL');
        if ($product_type[0] == '101') {
            // PUBLISH FEE
            $conn = getConnection();
            $conn->osc_dbExec("UPDATE %st_paypal_publish SET dt_date = '%s', b_paid =  '1', fk_i_paypal_id = '%d' WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, date('Y-m-d H:i:s'), $paypal_id, $rpl[1]);

            $item     = Item::newInstance()->findByPrimaryKey($rpl[1]);
            $category = Category::newInstance()->findByPrimaryKey($item['fk_i_item_id']);
            View::newInstance()->_exportVariableToView('category', $category);
            $html = '<p>' . __('Payment processed correctly', 'paypal') . ' <a href="' . osc_search_category_url() . '">' . __('Click here to continue', 'paypal') . '</a></p>';
        } else if ($product_type[0] == '201') {
            // PREMIUM FEE
            $conn = getConnection();
            $paid = $conn->osc_dbFetchResult("SELECT * FROM %st_paypal_premium WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $rpl[1]);
            if ($paid) {
                $conn->osc_dbExec("UPDATE %st_paypal_premium SET dt_date = '%s', fk_i_paypal_id = '%d' WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, date('Y-m-d H:i:s'), $paypal_id, $rpl[1]);
            } else {
                $conn->osc_dbExec("INSERT INTO  %st_paypal_premium (`fk_i_item_id, dt_date, fk_i_paypal_id) VALUES ('%d',  '%s',  '%s')", DB_TABLE_PREFIX, $rpl[1], date('Y-m-d H:i:s'), $paypal_id);
            }

            $html = '<p>' . __('Payment processed correctly', 'paypal') . ' <a href="' . osc_render_file_url(osc_plugin_folder(__FILE__) . 'user_menu.php') . '">' . __("Click here to continue", 'paypal') . '</a></p>';
        } else {
            // THIS SHOULD NEVER HAPPEN (YET)
            // PUBLISH/PREMIUM PACKS
        }

        osc_add_flash_ok_message(__("Payment processed correctly","paypal"));
    } else if($doresponse['ACK'] == "Failure" || $doresponse['ACK'] == "FailureWithWarning") {
        $rpl = explode("|", Params::getParam("rpl"));
        $item = Item::newInstance()->findByPrimaryKey($rpl[1]);
        $category = Category::newInstance()->findByPrimaryKey($item['fk_i_item_id']);
        View::newInstance()->_exportVariableToView('category', $category);
        $html = '<p>'.__("There was a problem processing your payment. Please contact the administrators and","paypal").' <a href="'.osc_search_category_url().'">'.__("Click here to continue", "paypal").'</a></p>';

        osc_add_flash_error_message(__("There was a problem processing your payment. Please contact the administrators","paypal"));
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script type="text/javascript" src="https://www.paypalobjects.com/js/external/dg.js"></script>
        <title><?php echo osc_page_title(); ?></title>
    </head>
    <body>
        <script type="text/javascript">
            top.rd.innerHTML = '<?php echo $html; ?>';
            //top.dg.closeFlow();
        </script>
    </body>
</html>