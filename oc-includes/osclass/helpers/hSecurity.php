<?php

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    if(!defined('BCRYPT_COST')) { define('BCRYPT_COST', 15); };

    /**
    * Helper Security
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Creates a random password.
     * @param int password $length. Default to 8.
     * @return string
     */
    function osc_genRandomPassword($length = 8) {
        $dict = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        shuffle($dict);

        $pass = '';
        for($i = 0; $i < $length; $i++)
            $pass .= $dict[rand(0, count($dict) - 1)];

        return $pass;
    }

    /**
     * Create a CSRF token to be placed in a form
     *
     * @since 3.1
     * @return string
     */
    function osc_csrf_token_form() {
        list($name, $token) = osc_csrfguard_generate_token();
        return "<input type='hidden' name='CSRFName' value='".$name."' />
        <input type='hidden' name='CSRFToken' value='".$token."' />";
    }

    /**
     * Create a CSRF token to be placed in a url
     *
     * @since 3.1
     * @return string
     */
    function osc_csrf_token_url() {
        list($name, $token) = osc_csrfguard_generate_token();
        return "CSRFName=".$name."&CSRFToken=".$token;
    }

    /**
     * Check is CSRF token is valid, die in other case
     *
     * @since 3.1
     */

    function osc_csrf_check() {
        $error      = false;
        $str_error  = '';
        if(Params::getParam('CSRFName')=='' || Params::getParam('CSRFToken')=='') {
            $str_error = _m('Probable invalid request.') ;
            $error = true;
        } else {
            $name   = Params::getParam('CSRFName');
            $token  = Params::getParam('CSRFToken');
            if (!osc_csrfguard_validate_token($name, $token)) {
                $str_error = _m('Invalid CSRF token.');
                $error = true;
            }
        }

        if( defined('IS_AJAX') ) {
            if($error && IS_AJAX === true ) {
                echo json_encode(array(
                    'error' => 1,
                    'msg'   => $str_error
                ));
                exit;
            }
        }

        // ¿ check if is ajax request ?
        if($error) {
            if(OC_ADMIN) {
                osc_add_flash_error_message($str_error, 'admin');
            } else {
                osc_add_flash_error_message($str_error);
            }

            $url = osc_get_http_referer();
            // be sure that drop session referer
            Session::newInstance()->_dropReferer();
            if($url!='') {
                osc_redirect_to($url);
            }

            if(OC_ADMIN) {
                osc_redirect_to( osc_admin_base_url(true) );
            } else {
                osc_redirect_to( osc_base_url(true) );
            }
        }
    }

    /**
     * Check is an email and IP are banned
     *
     * @param string $email
     * @param string $ip
     * @since 3.1
     * @return int 0: not banned, 1: email is banned, 2: IP is banned
     */
    function osc_is_banned($email = '', $ip = null) {
        if($ip==null) {
            $ip = Params::getServerParam('REMOTE_ADDR');
        }
        $rules = BanRule::newInstance()->listAll();
        if(!osc_is_ip_banned($ip, $rules)) {
            if($email!='') {
                return osc_is_email_banned($email, $rules)?1:0; // 1:Email is banned, 0:not banned
            }
            return 0;
        }
        return 2; //IP is banned
    }

    /**
     * Check is an email and IP are banned
     *
     * @param string $ip
     * @param string $rules (optional, to savetime and resources)
     * @since 3.1
     * @return boolean
     */
    function osc_is_ip_banned($ip, $rules = null) {
        if($rules==null) {
            $rules = BanRule::newInstance()->listAll();
        }
        $ip_blocks = explode(".", $ip);
        if(count($ip_blocks)==4) {
            foreach($rules as $rule) {
                if($rule['s_ip']!='') {
                    $blocks = explode(".", $rule['s_ip']);
                    if(count($blocks)==4) {
                        $matched = true;
                        for($k=0;$k<4;$k++) {
                            if(preg_match('|([0-9]+)-([0-9]+)|', $blocks[$k], $match)) {
                                if($ip_blocks[$k]<$match[1] || $ip_blocks[$k]>$match[2]) {
                                    $matched = false;
                                    break;
                                }
                            } else if($blocks[$k]!="*" && $blocks[$k]!=$ip_blocks[$k]) {
                                $matched = false;
                                break;
                            }
                        }
                        if($matched) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Check is an email and IP are banned
     *
     * @param string $email
     * @param string $rules (optional, to savetime and resources)
     * @since 3.1
     * @return boolean
     */
    function osc_is_email_banned($email, $rules = null) {
        if($rules==null) {
            $rules = BanRule::newInstance()->listAll();
        }
        $email = strtolower($email);
        foreach($rules as $rule) {
            $rule = str_replace("*", ".*", str_replace(".", "\.", strtolower($rule['s_email'])));
            if($rule!='') {
                if(substr($rule,0,1)=="!") {
                    $rule = '|^((?'.$rule.').*)$|';
                } else {
                    $rule = '|^'.$rule.'$|';
                }
                if(preg_match($rule, $email)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Check is an username is blacklisted
     *
     * @param string $username
     * @since 3.1
     * @return boolean
     */
    function osc_is_username_blacklisted($username) {
        // Avoid numbers only usernames, this will collide with future users leaving the username field empty
        if(preg_replace('|(\d+)|', '', $username)=='') {
         return true;
        }
        $blacklist = explode(",", osc_username_blacklist());
        foreach($blacklist as $bl) {
            if(stripos($username, $bl)!==false) {
                return true;
            }
        }
        return false;
    }

    /*
     * Verify an user's password
     *
     * @param $password plain-text
     * @hash bcrypt/sha1
     * @since 3.3
     * @return boolean
     */
    function osc_verify_password($password, $hash) {
        if(version_compare(PHP_VERSION, '5.3.7')>=0) {
            return password_verify($password, $hash)?true:(sha1($password)==$hash);
        }

        require_once LIB_PATH . 'Bcrypt.php';
        if(CRYPT_BLOWFISH==1) {
            $bcrypt = new Bcrypt(BCRYPT_COST);
            return $bcrypt->verify($password, $hash)?true:(sha1($password)==$hash);
        }
        return (sha1($password)==$hash);
    }

    /*
     * Hash a password in available method (bcrypt/sha1)
     *
     * @param $password plain-text
     * @since 3.3
     * @return string hashed password
     */
    function osc_hash_password($password) {
        if(version_compare(PHP_VERSION, '5.3.7')>=0) {
            $options = array('cost' => BCRYPT_COST);
            return password_hash($password, PASSWORD_BCRYPT, $options);
        }

        require_once LIB_PATH . 'Bcrypt.php';
        if(CRYPT_BLOWFISH==1) {
            $bcrypt = new Bcrypt(BCRYPT_COST);
            return $bcrypt->hash($password);
        }
        return sha1($password);
    }

    function osc_encrypt_alert($alert) {
        $string = osc_genRandomPassword(32) . $alert;
        osc_set_alert_private_key(); // renew private key and
        osc_set_alert_public_key();  // public key
        $key = hash("sha256", osc_get_alert_private_key(), true);
        if(function_exists('mcrypt_module_open')) {
            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
            $cipherText = '';
            if (mcrypt_generic_init($cipher, $key, $key) != -1) {
                $cipherText = mcrypt_generic($cipher, $string);
                mcrypt_generic_deinit($cipher);
            }
            return $cipherText;
        };

        while (strlen($string) % 32 != 0) {
            $string .= "\0";
        }
        require_once LIB_PATH . 'phpseclib/Crypt/Rijndael.php';
        $cipher = new Crypt_Rijndael(CRYPT_RIJNDAEL_MODE_CBC);
        $cipher->disablePadding();
        $cipher->setBlockLength(256);
        $cipher->setKey($key);
        $cipher->setIV($key);
        return $cipher->encrypt($string);
    }

    function osc_decrypt_alert($string) {
        $key = hash("sha256", osc_get_alert_private_key(), true);
        if(function_exists('mcrypt_module_open')) {
            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', MCRYPT_MODE_CBC, '');
            $cipherText = '';
            if (mcrypt_generic_init($cipher, $key, $key) != -1) {
                $cipherText = mdecrypt_generic($cipher, $string);
                mcrypt_generic_deinit($cipher);
            }
            return trim(substr($cipherText, 32));
        };
        require_once LIB_PATH . 'phpseclib/Crypt/Rijndael.php';
        $cipher = new Crypt_Rijndael(CRYPT_RIJNDAEL_MODE_CBC);
        $cipher->disablePadding();
        $cipher->setBlockLength(256);
        $cipher->setKey($key);
        $cipher->setIV($key);
        return trim(substr($cipher->decrypt($string), 32));
    }

    function osc_set_alert_public_key() {
        if(!View::newInstance()->_exists('alert_public_key')) {
            Session::newInstance()->_set('alert_public_key', osc_random_string(32) );
        }
    }

    function osc_get_alert_public_key() {
        return Session::newInstance()->_get('alert_public_key');
    }

    function osc_set_alert_private_key() {
        if(!View::newInstance()->_exists('alert_private_key')) {
            Session::newInstance()->_set('alert_private_key', osc_random_string(32) );
        }
    }

    function osc_get_alert_private_key() {
        return Session::newInstance()->_get('alert_private_key');
    }

    function osc_random_string($length) {
        $buffer = '';
        $buffer_valid = false;
        if (function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ($buffer) {
                $buffer_valid = true;
            }
        }
        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($length);
            if ($buffer) {
                $buffer_valid = true;
            }
        }
        if (!$buffer_valid && is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $length) {
                $buffer .= fread($f, $length - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $length) {
                $buffer_valid = true;
            }
        }
        if (!$buffer_valid || strlen($buffer) < $length) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $length; $i++) {
                if ($i < $bl) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }
        if(!$buffer_valid) {
            $buffer = osc_genRandomPassword(2*$length);
        }
        return substr(str_replace('+', '.', base64_encode($buffer)), 0, $length);
    }
