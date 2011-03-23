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

Class ItemActions
{
    private $manager = null;
    var $is_admin ;

    function __construct($is_admin) {
        $this->is_admin = $is_admin ;
        $this->manager = Item::newInstance() ;
    }

    /**
     * @return boolean
     */
    public function add()
    {
        $success = true;
        $aItem = $this->prepareData(true);

        // first of all, insert the item
        $code = osc_genRandomPassword();


        $has_to_validate = false ;
        if( osc_item_validation_enabled() ) {
            $has_to_validate = true ;
        }

        // set params from array
        $active         = $aItem['active'];
        if( $this->is_admin || !$has_to_validate || (osc_logged_user_item_validation() && osc_is_web_user_logged_in())) {
            $active = 'ACTIVE';
        }

        $contactName    = $aItem['contactName'];
        $contactEmail   = $aItem['contactEmail'];
        
        // Anonymous
        if(!preg_match("/([a-z][^a-z]*){2}/i",$contactName)) {
            $contactName = __("Anonymous");
        }

        if( $this->validate( reset($aItem['title']), reset($aItem['description']), $contactEmail, $aItem['catId'], $aItem['photos']) ) {
            $this->manager->insert(array(
                'fk_i_user_id'          => $aItem['userId'],
                'dt_pub_date'           => DB_FUNC_NOW,
                'fk_i_category_id'      => $aItem['catId'],
                'f_price'               => $aItem['price'],
                'fk_c_currency_code'    => $aItem['currency'],
                's_contact_name'        => $contactName,
                's_contact_email'       => $contactEmail,
                's_secret'              => $code,
                'e_status'              => $active,
                'b_show_email'          => $aItem['showEmail']
            ));

            $itemId = $this->manager->getConnection()->get_last_id();

            Params::setParam('itemId', $itemId);

            // INSERT title and description locales
            $this->insertItemLocales('ADD', $aItem['title'], $aItem['description'], $itemId );
            // INSERT location item
            $location = array(
                'fk_i_item_id'      => $itemId,
                'fk_c_country_code' => $aItem['countryId'],
                's_country'         => $aItem['countryName'],
                'fk_i_region_id'    => $aItem['regionId'],
                's_region'          => $aItem['regionName'],
                'fk_i_city_id'      => $aItem['cityId'],
                's_city'            => $aItem['cityName'],
                's_city_area'       => $aItem['cityArea'],
                's_address'         => $aItem['address']
            );

            $locationManager = ItemLocation::newInstance();
            $locationManager->insert($location);

            // OJO
            if ( $this->is_admin || !$has_to_validate || (osc_logged_user_item_validation() && osc_is_web_user_logged_in())) {
                CategoryStats::newInstance()->increaseNumItems($aItem['catId']);
            }

            //uploading resources from the input form
            $this->uploadItemResources( $aItem['photos'] , $itemId ) ;

            osc_run_hook('item_form_post', $aItem['catId'], $itemId);

            $item = $this->manager->findByPrimaryKey($itemId);
            $aItem['item'] = $item;

            // send an e-mail to the admin with the data of the new item
            // and send an e-email to admin to validate the item if configured to do so
            if( !$this->is_admin ) {
                // Stop publishing items in less than a minute
                Session::newInstance()->_set('last_publish_time', time());
                $this->sendEmails($aItem);
            }

            osc_run_hook('after_item_post') ;

            if($this->is_admin) {
                osc_add_flash_message( _m('A new item has been added')) ;
            } else {
                if( !$has_to_validate || (osc_logged_user_item_validation() && osc_is_web_user_logged_in())) {
                    osc_add_flash_message( _m('Great! We\'ve just published your item')) ;
                } else {
                    osc_add_flash_message( _m('Great! You\'ll receive an e-mail to activate your item')) ;
                }

            }
        } else {
            $success = false;
        }
        
        return $success;
    }

    function edit()
    {
        $aItem = $this->prepareData(false);

        // QUICK FIX $contactmMail
        $contactEmail   = "valid@mail.com" ;
        // Validate
        
        if( $this->validate( reset($aItem['title']), reset($aItem['description']), $contactEmail, $aItem['catId'], $aItem['photos']) ) {
        
            $location = array(
                'fk_c_country_code' => $aItem['countryId'],
                's_country'         => $aItem['countryName'],
                'fk_i_region_id'    => $aItem['regionId'],
                's_region'          => $aItem['regionName'],
                'fk_i_city_id'      => $aItem['cityId'],
                's_city'            => $aItem['cityName'],
                's_city_area'       => $aItem['cityArea'],
                's_address'         => $aItem['address']
            );

            $locationManager = ItemLocation::newInstance();
            $locationManager->update( $location, array( 'fk_i_item_id' => $aItem['idItem'] ) );

            // Update category numbers
            $old_item = $this->manager->findByPrimaryKey( $aItem['idItem'] ) ;
            if($old_item['fk_i_category_id'] != $aItem['catId']) {
                CategoryStats::newInstance()->increaseNumItems($aItem['catId']) ;
                CategoryStats::newInstance()->decreaseNumItems($old_item['fk_i_category_id']) ;
            }
            unset($old_item) ;

            $result = $this->manager->update (
                                    array(
                                        'dt_pub_date'           => DB_FUNC_NOW
                                        ,'fk_i_category_id'     => $aItem['catId']
                                        ,'f_price'              => $aItem['price']
                                        ,'fk_c_currency_code'   => $aItem['currency']
                                    )
                                    ,array(
                                        'pk_i_id'   => $aItem['idItem']
                                        ,'s_secret' => $aItem['secret']
                                )
            ) ;
            // UPDATE title and description locales
            $this->insertItemLocales( 'EDIT', $aItem['title'], $aItem['description'], $aItem['idItem'] );
            // UPLOAD item resources
            $this->uploadItemResources( $aItem['photos'], $aItem['idItem'] ) ;

            osc_run_hook('item_edit_post', $aItem['catId'], $aItem['idItem']);

            return $result;
        } else {
            return false;
        }
    }
    
    /**
     * Activetes an item
     * @param <type> $secret
     * @param <type> $id
     * @return boolean
     */
    public function activate( $id, $secret )
    {
        $item   = $this->manager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL ", $secret, $id);
        $result = $this->manager->update(
            array('e_status' => 'ACTIVE'),
            array('s_secret' => $secret)
        );
        osc_run_hook( 'activate_item', $this->manager->findByPrimaryKey($id) );
        CategoryStats::newInstance()->increaseNumItems($item[0]['fk_i_category_id']);

        return $result;
    }
    
    /**
     *
     * @param <type> $secret
     * @param <type> $itemId
     */
    public function delete( $secret, $itemId )
    {
        $item = $this->manager->findByPrimaryKey($itemId);
        $this->deleteResourcesFromHD($itemId);
        $this->manager->delete(array('pk_i_id' => $itemId, 's_secret' => $secret));
        CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
    }

    /**
     * Delete resources from the hard drive
     * @param <type> $itemId
     */
    public function deleteResourcesFromHD( $itemId )
    {
        $resources = ItemResource::newInstance()->getAllResources($itemId);
        foreach($resources as $resource) {
            osc_deleteResource($resource['pk_i_id']);
        }
    }
    
    /**
     * Mark an item
     * @param <type> $id
     * @param <type> $as
     */
    public function mark( $id, $as )
    {
        switch ($as) {
            case 'spam':
                $column = 'i_num_spam';
            break;
            case 'badcat':
                $column = 'i_num_bad_classified';
            break;
            case 'offensive':
                $column = 'i_num_offensive';
            break;
            case 'repeated':
                $column = 'i_num_repeated';
            break;
            case 'expired':
                $column = 'i_num_expired';
            break;
        }
        
        ItemStats::newInstance()->increase( $column, $id ) ;
    }

    public function send_friend()
    {
        // get data for this function
        $aItem = $this->prepareDataForFunction( 'send_friend' );

        $item       = $aItem['item'];
        $s_title    = $aItem['s_title'];
        View::newInstance()->_exportVariableToView('item', $item);
        $item_url   = osc_item_url();

        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_send_friend');
        $locale = osc_current_user_locale();

        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }

        $words   = array() ;
        $words[] = array(
                '{FRIEND_NAME}'
                ,'{USER_NAME}'
                ,'{USER_EMAIL}'
                ,'{FRIEND_EMAIL}'
                ,'{WEB_URL}'
                ,'{ITEM_NAME}'
                ,'{COMMENT}'
                ,'{ITEM_URL}'
                ,'{WEB_TITLE}'
        ) ;
        $words[] = array(
                $aItem['friendName']
                ,$aItem['yourName']
                ,$aItem['yourEmail']
                ,$aItem['friendEmail']
                ,osc_base_url()
                ,$aItem['s_title']
                ,$aItem['message']
                ,$item_url
                ,osc_page_title()
        ) ;
        $title = osc_mailBeauty($content['s_title'], $words) ;
        $body  = osc_mailBeauty($content['s_text'], $words) ;

        if (osc_notify_contact_friends()) {
            $add_bbc = osc_contact_email() ;
        }

        $params = array(
                    'add_bcc'    => $add_bbc
                    ,'from'      => $aItem['yourEmail']
                    ,'from_name' => $aItem['yourName']
                    ,'subject'   => $title
                    ,'to'        => $aItem['friendEmail']
                    ,'to_name'   => $aItem['friendName']
                    ,'body'      => $body
                    ,'alt_body'  => $body
                  ) ;

        Params::setParam('item_url', $item_url );

        if(osc_sendMail($params)) {
            osc_add_flash_message( _m('We just send your message to ') . $aItem['friendName'] . ".") ;
        } else {
            osc_add_flash_message( _m('We are very sorry but we could not deliver your message to your friend. Try again later')) ;
        }
    }

    public function contact()
    {
        $aItem = $this->prepareDataForFunction( 'contact' );

        $id         = $aItem['id'];
        $yourEmail  = $aItem['yourEmail'];
        $yourName   = $aItem['yourName'];
        $phoneNumber= $aItem['phoneNumber'];
        $message    = $aItem['message'];

        $path = NULL;
        $item = $this->manager->findByPrimaryKey( $id ) ;
        View::newInstance()->_exportVariableToView('item', $item);

        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_item_inquiry');
        $locale = osc_current_user_locale() ;

        $content = array();
        if(isset($aPage['locale'][$locale]['s_title'])) {
            $content = $aPage['locale'][$locale];
        } else {
            $content = current($aPage['locale']);
        }

        $words   = array();
        $words[] = array('{CONTACT_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{USER_PHONE}',
                         '{WEB_URL}', '{ITEM_NAME}','{ITEM_URL}', '{COMMENT}');

        $words[] = array($item['s_contact_name'], $yourName, $yourEmail,
                         $phoneNumber, osc_base_url(), $item['s_title'], osc_item_url(), $message );

        $title = osc_mailBeauty($content['s_title'], $words);
        $body = osc_mailBeauty($content['s_text'], $words);

        $from = osc_contact_email() ;
        $from_name = osc_page_title() ;

        $add_bbc = '';
        if (osc_notify_contact_item()) {
            $add_bbc = osc_contact_email() ;
        }

        $emailParams = array (
                            'add_bcc'   => $add_bbc
                            ,'from'      => $from
                            ,'from_name' => $from_name
                            ,'subject'   => $title
                            ,'to'        => $item['s_contact_email']
                            ,'to_name'   => $item['s_contact_name']
                            ,'body'      => $body
                            ,'alt_body'  => $body
                            ,'reply_to'  => $yourEmail
                        ) ;
                        
                        
        if(osc_item_attachment()) {
            $attachment = Params::getFiles('attachment');
            $resourceName = $attachment['name'] ;
            $tmpName = $attachment['tmp_name'] ;
            $resourceType = $attachment['type'] ;

            $path = osc_base_path() . 'oc-content/uploads/' . time() . '_' . $resourceName ;

            if(!is_writable(osc_base_path() . 'oc-content/uploads/')) {
                osc_add_flash_message( _m('There has been some errors sending the message')) ;
                $this->redirectTo( osc_base_url() );
            }

            if(!move_uploaded_file($tmpName, $path)){
                unset($path) ;
            }
        }

        if(isset($path)) {
            $emailParams['attachment'] = $path ;
        }
        osc_sendMail($emailParams);

        @unlink($path) ;
    }

    /*
     *
     */
    public function add_comment()
    {
        $aItem  = $this->prepareDataForFunction('add_comment');

        $authorName     = trim($aItem['authorName']);
        $authorName     = strip_tags($authorName);
        $authorEmail    = trim($aItem['authorEmail']);
        $authorEmail    = strip_tags($authorEmail);
        $body           = trim($aItem['body']);
        $body           = strip_tags($body);
        $title          = $aItem['title'] ;
        $itemId         = $aItem['id'] ;
        $userId         = $aItem['userId'] ;
        $status_num     = -1;
        
        $item = $this->manager->findByPrimaryKey($itemId) ;
        $itemURL = osc_item_url() ;
        Params::setParam('itemURL', $itemURL);

        if( $authorName == '' || !preg_match('|^.*?@.{2,}\..{2,3}$|', $authorEmail)) {
            return 3;
        }
        
        if( ($body == '') ) {
            return 4;
        }

        $num_moderate_comments = osc_moderate_comments();
        if($userId==null) {
            $num_comments = 0;
        } else {
            $num_comments = count(ItemComment::newInstance()->findByAuthorID($userId));
        }

        if ($num_moderate_comments == -1 || ($num_moderate_comments != 0 && $num_comments >= $num_moderate_comments)) {
            $status = 'ACTIVE';
            $status_num = 2;
        } else {
            $status = 'INACTIVE';
            $status_num = 1;
        }
        
        if (osc_akismet_key()) {
            require_once LIB_PATH . 'Akismet.class.php' ;
            $akismet = new Akismet(osc_base_url(), osc_akismet_key()) ;
            $akismet->setCommentAuthor($authorName) ;
            $akismet->setCommentAuthorEmail($authorEmail) ;
            $akismet->setCommentContent($body) ;
            $akismet->setPermalink($itemURL) ;

            $status = $akismet->isCommentSpam() ? 'SPAM' : $status ;
            if($status == 'SPAM') {
                $status_num = 5;
            }
        }

        $mComments = ItemComment::newInstance();
        $aComment  = array('dt_pub_date'    => DB_FUNC_NOW
                          ,'fk_i_item_id'   => $itemId
                          ,'s_author_name'  => $authorName
                          ,'s_author_email' => $authorEmail
                          ,'s_title'        => $title
                          ,'s_body'         => $body
                          ,'e_status'       => $status
                          ,'fk_i_user_id'   => $userId);

        if( $mComments->insert($aComment) ){
            $notify = osc_notify_new_comment() ;
            $admin_email = osc_contact_email() ;
            $prefLocale = osc_language() ;

            //Notify admin
            if ($notify) {
                $mPages = new Page() ;
                $aPage = $mPages->findByInternalName('email_new_comment_admin') ;
                $locale = osc_current_user_locale() ;

                $content = array();
                if(isset($aPage['locale'][$locale]['s_title'])) {
                    $content = $aPage['locale'][$locale];
                } else {
                    $content = current($aPage['locale']);
                }

                $words   = array();
                $words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}', '{COMMENT_TITLE}',
                                 '{COMMENT_TEXT}', '{ITEM_NAME}', '{ITEM_ID}', '{ITEM_URL}');
                $words[] = array($authorName, $authorEmail, $title, $body, $item['s_title'], $itemId, $itemURL);
                $title_email = osc_mailBeauty($content['s_title'], $words);
                $body_email = osc_mailBeauty($content['s_text'], $words);

                $from = osc_contact_email() ;
                $from_name = osc_page_title() ;
                if (osc_notify_contact_item()) {
                    $add_bbc = osc_contact_email() ;
                }

                $emailParams = array(
                                'from'      => $admin_email
                                ,'from_name' => __('Admin mail system')
                                ,'subject'   => $title_email
                                ,'to'        => $admin_email
                                ,'to_name'   => __('Admin mail system')
                                ,'body'      => $body_email
                                ,'alt_body'  => $body_email
                                );
                osc_sendMail($emailParams) ;
            }
            osc_run_hook('add_comment', $item);
            return $status_num;
        }
        
        return -1;
    }
    
    /**
     * Validate some things previous to add or edit
     * @param <string> $title
     * @param <string> $description
     * @param <string> $contactEmail
     * @param <string> $idCat
     * @param <array> $aPhotos
     * @return boolean 
     */
    private function validate( $title, $description, $contactEmail, $idCat, $aPhotos )
    {
        $success = true;
        if ( !preg_match("/([a-z][^a-z]*){3}/i", $title ) ||
            !preg_match("/([a-z][^a-z]*){8}/i", $description ) ||
            !preg_match("/^[_a-z0-9-\+]+(\.[_a-z0-9-\+]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/",$contactEmail)  ||
            !preg_match("/^[0-9]+$/", $idCat )) {
            osc_add_flash_message( _m('Some fields were too short. Try again!') );
            $success = false;
        }
        
        if ( !$this->checkAllowedExt($aPhotos) ) {
            $success = false;
        }

        if ( !$this->checkSize($aPhotos) ) {
            $success = false;
        }
        
        return $success;
    }

    /**
     * Return an array with all data necessary for do the action
     * @param <type> $action
     */
    private function prepareDataForFunction( $action )
    {
        $aItem = array();

        switch ( $action ){
            case 'send_friend':
                $item = $this->manager->findByPrimaryKey( Params::getParam('id') );

                $aItem['item']          = $item;
                View::newInstance()->_exportVariableToView('item', $aItem['item']);
                $aItem['yourName']      = Params::getParam('yourName');
                $aItem['yourEmail']     = Params::getParam('yourEmail');

                $aItem['friendName']    = Params::getParam('friendName');
                $aItem['friendEmail']   = Params::getParam('friendEmail');  

                $aItem['s_title']       = Params::getParam('s_title');
                $aItem['message']       = Params::getParam('message');
            break;
            case 'contact':
                $item = $this->manager->findByPrimaryKey( Params::getParam('id') );

                $aItem['item']          = $item;
                View::newInstance()->_exportVariableToView('item', $aItem['item']);
                $aItem['id']            = Params::getParam('id') ;
                $aItem['yourEmail']     = Params::getParam('yourEmail') ;
                $aItem['yourName']      = Params::getParam('yourName') ;
                $aItem['message']       = Params::getParam('message') ;
                $aItem['phoneNumber']   = Params::getParam('phoneNumber') ;
            break;
            case 'add_comment':
                $item = $this->manager->findByPrimaryKey( Params::getParam('id') );

                $aItem['item']          = $item;
                View::newInstance()->_exportVariableToView('item', $aItem['item']);
                $aItem['authorName']     = Params::getParam('authorName') ;
                $aItem['authorEmail']    = Params::getParam('authorEmail') ;
                $aItem['body']           = Params::getParam('body') ;
                $aItem['title']          = Params::getParam('title') ;
                $aItem['id']             = Params::getParam('id') ;
                $aItem['userId']         = Session::newInstance()->_get('userId');
                if($aItem['userId'] == ''){
                    $aItem['userId'] = NULL;
                }

            break;
            default:
        }
        return $aItem;
    }

    /**
     * Return an array with all data necessary for do the action (ADD OR EDIT)
     * @param <type> $is_add
     * @return array
     */
    private function prepareData( $is_add )
    {
        $aItem = array();
        
        if( $is_add ) {   // ADD

            $userId = null;
            if($this->is_admin){
                if(Params::getParam('userId') != '') {
                    $userId = Params::getParam('userId');
                }
            }else{
                $userId = Session::newInstance()->_get('userId');
                if($userId == ''){
                    $userId = NULL;
                }
                // to be tested
                if (osc_recaptcha_private_key()) {
                    $this->recaptcha();
                }
            }
            
            $show_email = 0;
            $active = 'INACTIVE';
            if( !osc_item_validation_enabled() ){
                $active = 'ACTIVE';
            }
            if( Params::getParam('showEmail') != '' ){
                $show_email = (int) Params::getParam('showEmail');
            }
            
            $aItem['active'] = $active;
            $aItem['show_email'] = $show_email;

            if ($userId != null) {
                if( $this->is_admin ) {
                    $data = User::newInstance()->findByPrimaryKey($userId);
                } else {
                    $data = User::newInstance()->findByPrimaryKey($userId);
                }
                $aItem['contactName']   = $data['s_name'];
                $aItem['contactEmail']  = $data['s_email'];
                Params::setParam('contactName', $data['s_name']);
                Params::setParam('contactEmail', $data['s_email']);
            }else{
                $aItem['contactName']   = Params::getParam('contactName');
                $aItem['contactEmail']  = Params::getParam('contactEmail');
            }


            $aItem['active']        = $active;
            $aItem['userId']        = $userId;

        }else{          // EDIT
            $aItem['secret']    = Params::getParam('secret');
            $aItem['idItem']    = Params::getParam('id');

            $userId = Params::getParam('userId');
            if ($userId != null) {
                $data = User::newInstance()->findByPrimaryKey($userId);
                $aItem['contactName']   = $data['s_name'];
                $aItem['contactEmail']  = $data['s_email'];
                Params::setParam('contactName', $data['s_name']);
                Params::setParam('contactEmail', $data['s_email']);
            }else{
                $aItem['contactName']   = Params::getParam('contactName');
                $aItem['contactEmail']  = Params::getParam('contactEmail');
            }
        }
        // get params
        $aItem['catId']         = Params::getParam('catId');            // OK
        $aItem['region']        = Params::getParam('region');           // OK
        $aItem['city']          = Params::getParam('city');             // OK
        $aItem['regionId']      = Params::getParam('regionId');         // OK
        $aItem['cityId']        = Params::getParam('cityId');           // OK
        $aItem['price']         = Params::getParam('price');            // OK
        $aItem['countryId']     = Params::getParam('countryId');        // OK
        $aItem['cityArea']      = Params::getParam('cityArea');         // OK
        $aItem['address']       = Params::getParam('address');          // OK
        $aItem['currency']      = Params::getParam('currency');         // OK
        $aItem['showEmail']     = (Params::getParam('showEmail')!='')?((int)Params::getParam('showEmail')):0;        // OK
        $aItem['title']         = Params::getParam('title');
        $aItem['description']   = Params::getParam('description');
        $aItem['photos']        = Params::getFiles('photos');

        // check params
        // ---------
        $country = Country::newInstance()->findByCode($aItem['countryId']);
        if( count($country) > 0 ) {
            $countryId = $country['pk_c_code'];
            $countryName = $country['s_name'];
        } else {
            $countryId = null;
            $countryName = null;
        }
        $aItem['countryId']   = $countryId;
        $aItem['countryName']   = $countryName;

        if( $aItem['regionId'] != '' ) {
            if( intval($aItem['regionId']) ) {
                $region = Region::newInstance()->findByPrimaryKey($aItem['regionId']);
                if( count($region) > 0 ) {
                    $regionId = $region['pk_i_id'];
                    $regionName = $region['s_name'];
                }
            }
        } else {
            $regionId = null;
            $regionName = $aItem['region'];   // OJO ¿ DE DONDE VIENE ?
        }
        $aItem['regionId']      = $regionId ;
        $aItem['regionName']    = $regionName;

        if( $aItem['cityId'] != '' ) {
            if( intval($aItem['cityId']) ) {
                $city = City::newInstance()->findByPrimaryKey($aItem['cityId']);
                if( count($city) > 0 ) {
                    $cityId = $city['pk_i_id'];
                    $cityName = $city['s_name'];
                }
            }
        } else {
            $cityId = null;
            $cityName = $aItem['city'];
        }

        $aItem['cityId']      = $cityId;
        $aItem['cityName']    = $cityName;

        if( $aItem['cityArea'] == '' ) {
            $aItem['cityArea'] = null;
        }

        if( $aItem['address'] == '' ) {
            $aItem['address'] = null;
        }

        if( $aItem['price'] != '' ) {
            $aItem['price'] = (int) $aItem['price'];
        }

        if( $aItem['catId'] == ''){
            $aItem['catId'] = 0;
        }

        if( $aItem['currency'] == '' ) {
            $aItem['currency'] = null;
        }

        return $aItem;
    }

    function insertItemLocales($type, $title, $description, $itemId )
    {
        foreach($title as $k => $_data){
            $_title         = $title[$k];
            $_description   = $description[$k];
            if($type == 'ADD'){
                $this->manager->insertLocale($itemId, $k, $_title, $_description, $_title . " " . $_description);
            }else if($type == 'EDIT'){
                $this->manager->updateLocaleForce($itemId, $k, $_title, $_description) ;
            }
        }
    }

    private function checkSize($aResources)
    {
        $success = true;

        if($aResources != '') {
            // get allowedExt
            $maxSize = osc_max_size_kb() * 1024 ;
            foreach ($aResources['error'] as $key => $error) {
                $bool_img = false;
                if ($error == UPLOAD_ERR_OK) {
                    $size = $aResources['size'][$key];
                    //echo "bytes: ".$size." [$size > $maxSize]<br>";
                    if($size > $maxSize){
                        $success = false;
                    }
                }
            }
            if(!$success){
                osc_add_flash_message( _m("One of the files you tried to upload exceeds the maximum size")) ;
            }
        }
        return $success;
    }

    private function checkAllowedExt($aResources)
    {
        $success = true;
        require LIB_PATH . 'osclass/classes/mimes.php';
        if($aResources != '') {
            // get allowedExt
            $aMimesAllowed = array();
            $aExt = explode(',', osc_allowed_extension() );
            foreach($aExt as $ext){
                $mime = $mimes[$ext];
                if( is_array($mime) ){
                    foreach($mime as $aux){
                        if( !in_array($aux, $aMimesAllowed) ) {
                            array_push($aMimesAllowed, $aux );
                        }
                    }
                } else {
                    if( !in_array($mime, $aMimesAllowed) ) {
                        array_push($aMimesAllowed, $mime );
                    }
                }
            }

            foreach ($aResources['error'] as $key => $error) {
                $bool_img = false;
                if ($error == UPLOAD_ERR_OK) {
                    // check mime file
                    $fileMime = $aResources['type'][$key] ;

                    if(in_array($fileMime,$aMimesAllowed)) {
                        $bool_img = true;
                    }
                    if(!$bool_img && $success) {$success = false;}
                }
            }

            if(!$success){
                osc_add_flash_message( _m("The file you tried to upload does not have an allowed extension")) ;
            }
        }
        return $success;
    }
    
    public function uploadItemResources($aResources,$itemId)
    {
        if($aResources != '') {
        
            $itemResourceManager = ItemResource::newInstance() ;
            
            foreach ($aResources['error'] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    
                    $tmpName = $aResources['tmp_name'][$key] ;
                    $itemResourceManager->insert(array(
                        'fk_i_item_id' => $itemId
                    )) ;
                    $resourceId = $itemResourceManager->getConnection()->get_last_id() ;

                    // Create thumbnail
                    $path = osc_base_path() . 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
                    $size = explode('x', osc_thumbnail_dimensions()) ;
                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($path) ;

                    // Create normal size
                    $path = osc_base_path() . 'oc-content/uploads/' . $resourceId . '.png' ;
                    $size = explode('x', osc_normal_dimensions()) ;
                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($path) ;

                    if( osc_keep_original_image() ) {
                        $path = osc_base_path() . 'oc-content/uploads/' . $resourceId.'_original.png' ;
                        move_uploaded_file($tmpName, $path) ;
                    }

                    $s_path = 'oc-content/uploads/' ;
                    $resourceType = 'image/png' ;
                    $itemResourceManager->update(
                                            array(
                                                's_path'            => $s_path
                                                ,'s_name'           => $resourceId
                                                ,'s_extension'      => 'png'
                                                ,'s_content_type'   => $resourceType
                                            )
                                            ,array(
                                                'pk_i_id'       => $resourceId
                                                ,'fk_i_item_id' => $itemId
                                            )
                    ) ;
                }
            }
            unset($itemResourceManager);
        }
    }
    
    public function recaptcha()
    {
        require_once osc_base_path() . 'oc-includes/recaptchalib.php';
        if ( Params::getParam("recaptcha_challenge_field") != '') {
            $resp = recaptcha_check_answer (
                osc_recaptcha_private_key()
                ,$_SERVER["REMOTE_ADDR"]
                ,Params::getParam("recaptcha_challenge_field")
                ,Params::getParam("recaptcha_response_field")
            );
            if (!$resp->is_valid) {
                die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")") ;
            }
        }
    }

    public function sendEmails($aItem){

        $item   = $aItem['item'];
        $title  = $aItem['title'];
        $contactEmail   = $aItem['contactEmail'];
        $contactName    = $aItem['contactName'];
        View::newInstance()->_exportVariableToView('item', $item);
        $mPages = new Page();
        $locale = osc_current_user_locale();
        
        if ( osc_item_validation_enabled() && (!osc_logged_user_item_validation() || !osc_is_web_user_logged_in()) ) {
            $aPage = $mPages->findByInternalName('email_item_validation') ;

            $content = array();
            if(isset($aPage['locale'][$locale]['s_title'])) {
                $content = $aPage['locale'][$locale];
            } else {
                $content = current($aPage['locale']);
            }

            $item_url = osc_item_url();

            $all = '';

            if (isset($item['locale'])) {
                foreach ($item['locale'] as $locale => $data) {
                    $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'");
                    $all .= '<br/>';
                    if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                        $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                    } else {
                        $all .= __('Language') . ': ' . $locale . '<br/>';
                    }
                    $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                    $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                    $all .= '<br/>';
                }
            } else {
                $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
            }

            $words   = array();
            $words[] = array('{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}', '{ITEM_COUNTRY}',
                             '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}', '{USER_NAME}',
                             '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}', '{WEB_TITLE}',
                             '{VALIDATION_LINK}');
            $words[] = array($all, $item['s_description'], $item['s_country'], $item['f_price'],
                             $item['s_region'], $item['s_city'], $item['pk_i_id'], $item['s_contact_name'],
                             $item['s_contact_email'], osc_base_url(), $item['s_title'], $item_url,
                             osc_page_title(), '<a href="' . osc_base_url(true) .
                             '?page=item&action=activate&id=' . $item['pk_i_id'] . '&secret=' .
                             $item['s_secret'] . '" >' . osc_base_url(true) . '?page=item&action=activate&id=' .
                             $item['pk_i_id'] . '&secret=' . $item['s_secret'] . '</a>' );
            $title = osc_mailBeauty($content['s_title'], $words);
            $body = osc_mailBeauty($content['s_text'], $words);

            $emailParams =  array (
                                'subject'  => $title
                                ,'to'       => $contactEmail
                                ,'to_name'  => $contactName
                                ,'body'     => $body
                                ,'alt_body' => $body
                            );
            osc_sendMail($emailParams) ;
        }

        if (osc_notify_new_item()) {
            $aPage = $mPages->findByInternalName('email_admin_new_item') ;

            $content = array();
            if(isset($aPage['locale'][$locale]['s_title'])) {
                $content = $aPage['locale'][$locale] ;
            } else {
                $content = current($aPage['locale']) ;
            }

            $item_url = osc_item_url() ;

            $all = '' ;

            if (isset($item['locale'])) {
                foreach ($item['locale'] as $locale => $data) {
                    $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'") ;
                    $all .= '<br/>';
                    if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
                        $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
                    } else {
                        $all .= __('Language') . ': ' . $locale . '<br/>';
                    }
                    $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
                    $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
                    $all .= '<br/>';
                }
            } else {
                $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
                $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
            }


            $words   = array();
            $words[] = array('{EDIT_LINK}', '{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}',
                             '{ITEM_COUNTRY}', '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}',
                             '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}',
                             '{WEB_TITLE}', '{VALIDATION_LINK}');
            $words[] = array('<a href="' . osc_admin_base_url(true) . '?page=items&action=item_edit&id=' .
                             $item['pk_i_id'] . '" >' . osc_admin_base_url(true) . '?page=items&action=item_edit&id=' .
                             $item['pk_i_id'] . '</a>', $all, $item['s_description'], $item['s_country'],
                             $item['f_price'], $item['s_region'], $item['s_city'], $item['pk_i_id'],
                             $item['s_contact_name'], $item['s_contact_email'], osc_base_url(), $item['s_title'],
                             $item_url, osc_page_title(), '<a href="' .
                             osc_base_url() . '?page=item&action=activate&id=' . $item['pk_i_id'] .
                             '&secret=' . $item['s_secret'] . '" >' . osc_base_url() .
                             '?page=item&action=activate&id=' . $item['pk_i_id'] . '&secret=' .
                             $item['s_secret'] . '</a>' );
            $title = osc_mailBeauty($content['s_title'], $words);
            $body  = osc_mailBeauty($content['s_text'], $words);

            $emailParams = array(
                                'subject'  => $title
                                ,'to'       => osc_contact_email()
                                ,'to_name'  => 'admin'
                                ,'body'     => $body
                                ,'alt_body' => $body
            ) ;
            osc_sendMail($emailParams) ;
        }
    }


}

?>
