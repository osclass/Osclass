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
            $ip = $_SERVER['REMOTE_ADDR'];
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

