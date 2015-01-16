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

    /**
     * EmailVariables class
     * 
     * @since 3.0
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class EmailVariables
    {
        private static $instance;
        private $variables;

        public function __construct()
        {
            $this->variables = array();
            $this->init();
        }

        public static function newInstance()
        {
            if(!self::$instance instanceof self) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         *  Initialize menu representation.
         */
        public function init()
        {
            $this->variables = array(
                '{USER_NAME}'       => __('User name'),
                '{USER_EMAIL}'      => __('User email'),
                '{VALIDATION_LINK}' => __('Link for account validation.'),
                '{VALIDATION_URL}'  => __('Url for account validation.'),
                '{ADS}'             => __('List of listings, used when send alerts'),
                '{UNSUB_LINK}'      => __('Unsubscribe link.'),
                '{WEB_URL}'         => __('Site home page url.'),
                '{WEB_LINK}'        => __('Site home page link.'),
                '{WEB_TITLE}'       => __('Title of your site'),
                '{CURRENT_DATE}'    => __('Current date'),
                '{HOUR}'            => __('Hour'),
                '{IP_ADDRESS}'      => __('User ip address'),
                '{COMMENT_AUTHOR}'  => __('Comment author name'),
                '{COMMENT_EMAIL}'   => __('Comment author email'),
                '{COMMENT_TITLE}'   => __('Comment title'),
                'COMMENT_TEXT'      => __('Comment text content'),
                '{COMMENT_BODY}'    => __('Comment body'),
                '{ITEM_URL}'        => __('Listing url'),
                '{ITEM_LINK}'       => __('Listing list'),
                '{ITEM_TITLE}'      => __('Listing title'),
                '{ITEM_ID}'         => __('Listing id'),
                '{EDIT_LINK}'       => __('Link for edit listing'),
                '{EDIT_URL}'        => __('Url for edit listing'),
                '{DELETE_LINK}'     => __('Delete listing link'),
                '{DELETE_URL}'      => __('Delete listing url'),
                '{PASSWORD_LINK}'   => __('Change user password link'),
                '{PASSWORD_URL}'    => __('change user password url'),
                '{DATE_TIME}'       => __('Date time'),
                '{FRIEND_NAME}'     => __('Name of the friend who wants send'),
                '{FRIEND_EMAIL}'    => __('Email of the friend who wants send'),
                '{COMMENT}'         => __('Question about your listing'),
                '{CONTACT_NAME}'    => __('Contact name'),
                '{USER_PHONE}'      => __('User phone number'),
                '{ITEM_DESCRIPTION}'=> __('Listing description'),
                '{ITEM_DESCRIPTION_ALL_LANGUAGES}' => __('Listing description in all languages'),
                '{ITEM_PRICE}'        => __('Listing price'), 
                '{ITEM_COUNTRY}'      => __('Listing country'), 
                '{ITEM_REGION}'       => __('Listing region'), 
                '{ITEM_CITY}'         => __('Listing city')
            );
        }

        /**
         * Add new email variable and description
         *
         * @param type $key
         * @param type $description 
         */
        public function add($key, $description)
        {
            $this->variables[$key] = $description;
        }

        /**
         * Remove email variable from the array 
         * 
         * @param type $key
         */
        public function remove( $key ) 
        {
            unset( $this->variables[$key] );
        }
        
        /**
         *
         * @param type $email 
         */
        function getVariables( $email )
        {
            $array = array();
            foreach( $email['locale'] as $a ) {
                if(preg_match_all('/\{[A-Z|_]+\}/', $a['s_title'], $matchesarray) > 0 ) {
                    foreach($matchesarray as $index) {
                        foreach($index as $v) {
                            $array[$v] = @$this->variables[$v];
                        }
                    }
                }
                if(preg_match_all('/\{[A-Z|_]+\}/', $a['s_text'], $matchesarray) > 0 ) {
                    foreach($matchesarray as $index) {
                        foreach($index as $v) {
                            $array[$v] = @$this->variables[$v];
                        }
                    }
                }
            }
            return $array;
        }
        
        /*
         * Empty the variables array
         */
        public function clear_menu( )
        {
            $this->variables = array();
        }
    }

?>