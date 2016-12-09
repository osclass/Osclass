<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebContact extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
            osc_run_hook( 'init_contact' );
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('contact_post'):   //contact_post
                                        osc_csrf_check();
                                        $yourName  = Params::getParam('yourName');
                                        $yourEmail = Params::getParam('yourEmail');
                                        $subject   = Params::getParam('subject');
                                        $message   = Params::getParam('message');

                                        if( (osc_recaptcha_private_key() != '') ) {
                                            if( !osc_check_recaptcha() ) {
                                                osc_add_flash_error_message( _m('The Recaptcha code is wrong'));
                                                Session::newInstance()->_setForm('yourName', $yourName);
                                                Session::newInstance()->_setForm('yourEmail', $yourEmail);
                                                Session::newInstance()->_setForm('subject', $subject);
                                                Session::newInstance()->_setForm('message_body', $message);
                                                $this->redirectTo(osc_contact_url());
                                                return false; // BREAK THE PROCESS, THE RECAPTCHA IS WRONG
                                            }
                                        }

                                        $banned = osc_is_banned($yourEmail);
                                        if($banned==1) {
                                            osc_add_flash_error_message( _m('Your current email is not allowed'));
                                            $this->redirectTo(osc_contact_url());
                                        } else if($banned==2) {
                                            osc_add_flash_error_message( _m('Your current IP is not allowed'));
                                            $this->redirectTo(osc_contact_url());
                                        }

                                        $user = User::newInstance()->findByEmail($yourEmail);
                                        if(isset($user['b_active']) && ($user['b_active']==0 || $user['b_enabled']==0)) {
                                            osc_add_flash_error_message( _m('Your current email is not allowed'));
                                            $this->redirectTo(osc_contact_url());
                                        }

                                        if ( !osc_validate_email($yourEmail, true) ) {
                                            osc_add_flash_error_message( _m('Please enter a correct email') );
                                            Session::newInstance()->_setForm('yourName', $yourName);
                                            Session::newInstance()->_setForm('subject', $subject);
                                            Session::newInstance()->_setForm('message_body', $message);
                                            $this->redirectTo(osc_contact_url());
                                        }

                                        $message_name    = sprintf(__('Name: %s'), $yourName);
                                        $message_email   = sprintf(__('Email: %s'), $yourEmail);
                                        $message_subject = sprintf(__('Subject: %s'), $subject);
                                        $message_body    = sprintf(__('Message: %s'), $message);
                                        $message_date    = sprintf(__('Date: %s at %s'), date('l F d, Y'), date('g:i a'));
                                        $message_IP      = sprintf(__('IP Address: %s'), get_ip());
                                        $message = <<<MESSAGE
{$message_name}
{$message_email}
{$message_subject}
{$message_body}

{$message_date}
{$message_IP}
MESSAGE;

                                        $params = array(
                                            'from'      => osc_contact_email(),
                                            'to'        => osc_contact_email(),
                                            'to_name'   => osc_page_title(),
                                            'reply_to'  => $yourEmail,
                                            'subject'   => '[' . osc_page_title() . '] ' . __('Contact'),
                                            'body'      => nl2br($message)
                                        );


                                        $error = false;
                                        if( osc_contact_attachment() ) {
                                            $attachment   = Params::getFiles('attachment');
                                            if(isset($attachment['error']) && $attachment['error']==UPLOAD_ERR_OK) {
                                                $mime_array = array(
                                                    'text/php',
                                                    'text/x-php',
                                                    'application/php',
                                                    'application/x-php',
                                                    'application/x-httpd-php',
                                                    'application/x-httpd-php-source',
                                                    'application/x-javascript'
                                                );
                                                $resourceName = $attachment['name'];
                                                $tmpName      = $attachment['tmp_name'];
                                                $resourceType = $attachment['type'];

                                                if(function_exists('mime_content_type')){
                                                    $resourceType = mime_content_type($tmpName);
                                                }

                                                if(function_exists('finfo_open')){
                                                    $finfo = finfo_open(FILEINFO_MIME);
                                                    $output = finfo_file($finfo, $tmpName);
                                                    finfo_close($finfo);

                                                    $output = explode("; ",$output);
                                                    if ( is_array($output) ) {
                                                        $output = $output[0];
                                                    }
                                                    $resourceType = $output;
                                                }

                                                // check mime file
                                                if(!in_array($resourceType, $mime_array)) {
                                                    $emailAttachment = array('path' => $tmpName, 'name' => $resourceName);
                                                    $error = false;
                                                } else {
                                                    $error = true;
                                                }
                                                // --- check mime file
                                            } else {
                                                $error = true;
                                            }
                                        }
                                        if(!$error) {
                                            if( isset($emailAttachment) ) {
                                                $params['attachment'] = $emailAttachment;
                                            }

                                            osc_run_hook('pre_contact_post', $params);

                                            osc_sendMail(osc_apply_filter('contact_params', $params));

                                            if( isset($tmpName) ) {
                                                @unlink($tmpName);
                                            }

                                            osc_add_flash_ok_message( _m('Your email has been sent properly. Thank you for contacting us!') );
                                        } else {
                                            osc_add_flash_error_message( _m('The file you tried to upload does not have a valid extension') );
                                        }

                                        $this->redirectTo( osc_contact_url() );
                break;
                default:                //contact
                                        $this->doView('contact.php');
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_html");
            osc_current_web_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_html");
        }
    }

    /* file end: ./contact.php */
?>
