<?php
/*
 *DG with EC
 */
 
/*
 *cancel.php
 *
 *This page will handle the GetECDetails, and DoECPayment API Calls
 */
 //set include
require_once "../../../oc-load.php";
require_once osc_plugins_path().osc_plugin_folder(__FILE__).'functions.php';
//set GET var's to local vars:
$token = $_GET['token'];
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
  $ENDPOINT     = "https://api-3t.sandbox.paypal.com/nvp";
  $VERSION      = "65.1"; //must be >= 65.1
  
//Build the Credential String:
  $cred_str = "USER=" . $APIUSERNAME . "&PWD=" . $APIPASSWORD . "&SIGNATURE=" . $APISIGNATURE . "&VERSION=" . $VERSION;
//Build NVP String for GetExpressCheckoutDetails
  $nvp_str = "&METHOD=GetExpressCheckoutDetails&TOKEN=" . urldecode($token);
 
//combine the two strings and make the API Call
$req_str = $cred_str . $nvp_str;
$response = PPHttpPost($ENDPOINT, $req_str);
//based on the API Response from GetExpressCheckoutDetails
  $doec_str  = $cred_str . "&METHOD=DoExpressCheckoutPayment" 
   . "&TOKEN=" . $token
   . "&PAYERID=" . $payerid
            . "&PAYMENTREQUEST_0_CURRENCYCODE=".urldecode($response['PAYMENTREQUEST_0_CURRENCYCODE'])
      . "&PAYMENTREQUEST_0_AMT=".urldecode($response['PAYMENTREQUEST_0_AMT'])
   . "&PAYMENTREQUEST_0_ITEMAMT=".urldecode($response['PAYMENTREQUEST_0_ITEMAMT'])
   . "&PAYMENTREQUEST_0_TAXAMT=".urldecode($response['PAYMENTREQUEST_0_TAXAMT'])
   . "&PAYMENTREQUEST_0_DESC=".urldecode($response['PAYMENTREQUEST_0_DESC'])
   . "&PAYMENTREQUEST_0_PAYMENTACTION=Sale"
   . "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=".urldecode($response['L_PAYMENTREQUEST_0_ITEMCATEGORY0'])
   . "&L_PAYMENTREQUEST_0_NAME0=".urldecode($response['L_PAYMENTREQUEST_0_NAME0'])
   . "&L_PAYMENTREQUEST_0_NUMBER0=".urldecode($response['L_PAYMENTREQUEST_0_NUMBER0'])
   . "&L_PAYMENTREQUEST_0_QTY0=".urldecode($response['L_PAYMENTREQUEST_0_QTY0'])
   . "&L_PAYMENTREQUEST_0_TAXAMT0=".urldecode($response['L_PAYMENTREQUEST_0_TAXAMT0'])
   . "&L_PAYMENTREQUEST_0_AMT0=".urldecode($response['L_PAYMENTREQUEST_0_AMT0'])
   . "&L_PAYMENTREQUEST_0_DESC0=".urldecode($response['L_PAYMENTREQUEST_0_DESC0'])
   . "&NOTIFYURL=";

    //make the DoEC Call:
    $doresponse = PPHttpPost($ENDPOINT, $doec_str);

    //check Response
    if($doresponse['ACK'] == "Success" || $doresponse['ACK'] == "SuccessWithWarning") {
        //Save transaction to DB
        $rpl = explode("|", Params::getParam("rpl"));
        $paypal_id = paypal_save_log(urldecode($response['L_PAYMENTREQUEST_0_NAME0']), urldecode($doresponse['PAYMENTINFO_0_TRANSACTIONID']), urldecode($doresponse['PAYMENTINFO_0_AMT']), urldecode($doresponse['PAYMENTINFO_0_CURRENCYCODE']),$rpl[2], $rpl[0], $rpl[1],urldecode($response['L_PAYMENTREQUEST_0_NUMBER0']),"PAYPAL");
        $produt_type = explode("x", urldecode($response['L_PAYMENTREQUEST_0_NUMBER0']));
        if($produt_type[0]=="101") {
            // PUBLISH FEE
            $conn = getConnection();
            $conn->osc_dbExec("UPDATE %st_paypal_publish SET `dt_date` = '%s', `b_paid` =  '1', `fk_i_paypal_id` = '%d' WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, date('Y-m-d H:i:s'), $paypal_id, $rpl[1]);
        } else if($produt_type[0]=="201") {
            // PREMIUM FEE

        } else {
            // THIS SHOULD NEVER HAPPEN (YET)
            // PUBLISH PACKS
        }
        
        
        echo "Your Payment Has Completed! click <a href='#'>HERE</a> to download your goods";
        //place in logic to make digital goods available
    
        //$response = PPHttpPost($ENDPOINT, $req_str);
        header("Location :".osc_base_url()."oc-content/plugins/".osc_plugin_folder(__FILE__)."return.php?".$_SERVER['QUERY_STRING']);
        //print_r($_SERVER);

    } else if($doresponse['ACK'] == "Failure" || $doresponse['ACK'] == "FailureWithWarning") {
        header("Location :".osc_render_file_url(osc_plugin_folder(__FILE__)."cancel.php?".$_SERVER['QUERY_STRING']."&failed=1"));
    }
?>