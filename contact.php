<?php

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    class CWebContact extends BaseModel
    {

        function __construct() {
            parent::__construct() ;
        }

        //Business Layer...
        function doModel() {
            switch($this->action) {
                case('contact_post'):   //contact_post
                                        $yourName = Params::getParam('yourName') ;
                                        $yourEmail = Params::getParam('yourEmail') ;
                                        $subject = Params::getParam('subject') ;
                                        $message = Params::getParam('message') ;

                                        $params = array(
                                            'from' => $yourEmail
                                            ,'from_name' => $yourName
                                            ,'subject' => __('Contact form') . ': ' . $subject
                                            ,'to' => osc_contact_email()
                                            ,'to_name' => __('Administrator')
                                            ,'body' => $message
                                            ,'alt_body' => $message
                                        );
                                        osc_sendMail($params) ;

                                        osc_add_flash_message( _m('Your e-mail has been sent properly. Thank your for contacting us!') ) ;

                                        $this->redirectTo( osc_base_url() ) ;
                break;
                default:                //contact
                                        $this->doView('contact.php') ;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_web_theme_path($file) ;
        }
    }

?>
