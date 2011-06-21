<?php
    /*
     * functions.php
     *
     * holds functions for EC for index.php and return.php for Digital Goods EC Calls
     */

    //Function PPHttpPost
    //Makes an API call using an NVP String and an Endpoint
    function PPHttpPost($my_endpoint, $my_api_str) {
        // setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $my_endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        // turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        // setting the NVP $my_api_str as POST FIELD to curl
        curl_setopt($ch, CURLOPT_POSTFIELDS, $my_api_str);
        // getting response from server
        $httpResponse = curl_exec($ch);
        if (!$httpResponse) {
            $response = "$API_method failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')';
            return $response;
        }
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            $response = "Invalid HTTP Response for POST request($my_api_str) to $API_Endpoint.";
            return $response;
        }

        return $httpParsedResponseAr;
    }

    /**
     * Create a record on the DB for the paypal transaction
     * 
     * @param string $concept
     * @param string $code
     * @param float $amount
     * @param string $currency
     * @param string $email
     * @param integer $user
     * @param integer $item
     * @param string $product_type (publish fee, premium, pack and which category)
     * @param string $source
     * @return integer $last_id
     */
    function paypal_save_log($concept, $code, $amount, $currency, $email, $user, $item, $product_type, $source) {

        $conn = getConnection();
        $conn->osc_dbExec("INSERT INTO %st_paypal_log (s_concept, dt_date, s_code, f_amount, s_currency_code, s_email, fk_i_user_id, fk_i_item_id, s_source, i_product_type) VALUES 
                          ('" . $concept . "',"
                        . "'" . date("Y-m-d H:i:s") . "',"
                        . "'" . $code . "',"
                        . "'" . $amount . "',"
                        . "'" . $currency . "',"
                        . "'" . $email . "',"
                        . "'" . $user . "',"
                        . "'" . $item . "',"
                        . "'" . $product_type . "',"
                        . "'" . $source . "'"
                        . ")", DB_TABLE_PREFIX);
        return $conn->get_last_id();
    }

    /**
     * Know if the ad is paid
     * 
     * @param integer $itemId
     * @return boolean
     */
    function paypal_is_paid($itemId) {
        $conn = getConnection();
        $paid = $conn->osc_dbFetchResult("SELECT b_paid FROM %st_paypal_publish WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, $itemId);
        if (isset($paid) && $paid['b_paid'] == 1) {
            return true;
        }
        return false;
    }

    /**
     * Know if the ad is marked as premium (and paid)
     * 
     * @param integer $itemId
     * @return boolean
     */
    function paypal_is_premium($itemId) {
        $conn = getConnection();
        $paid = $conn->osc_dbFetchResult("SELECT dt_date FROM %st_paypal_premium WHERE fk_i_item_id = %d AND TIMESTAMPDIFF(DAY,dt_date,NOW()) < %d", DB_TABLE_PREFIX, $itemId, osc_get_preference("premium_days", "paypal"));
        if ($paid) {
            return true;
        }
        return false;
    }

?>