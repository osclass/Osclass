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
        if( $this->is_admin || !$has_to_validate) {
            $active = 'ACTIVE';
        }

        $contactName    = $aItem['contactName'];
        $contactEmail   = $aItem['contactEmail'];

        if( ($contactName == '') || ($contactEmail == '') || $contactName==null || $contactEmail==null ) {
            osc_add_flash_message( _m('You need to input your name and email to be able to publish a new item.'));
            $success = false;
        } else {
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

            // If the Google Maps plugin is well configured, we can try to geodecode the address
            if (osc_google_maps_key()) {
                $this->geocodeAddress($aItem['address'],$aItem['regionName'], $aItem['cityName'], $itemId);
            }
            // OJO
            if ( $this->is_admin || !$has_to_validate) {
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
                $this->sendEmails($aItem);
            }

            osc_run_hook('after_item_post') ;


            if($this->is_admin) {
                osc_add_flash_message( _m('A new item has been added')) ;
            } else {
                if( osc_item_validation_enabled() ) {
                    osc_add_flash_message( _m('Great! You\'ll receive an e-mail to activate your item.')) ;
                } else {
                    osc_add_flash_message( _m('Great! We\'ve just published your item.')) ;
                }

            }
        }
        return $success;
    }

    function edit()
    {
        $aItem = $this->prepareData(false);

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

        // If the Google Maps plugin is well configured, we can try to geodecode the address
        if (osc_google_maps_key()) {
            $this->geocodeAddress( $aItem['address'],$aItem['regionName'], $aItem['cityName'], $aItem['idItem'] );
        }

        $contactName    = $aItem['contactName'] ;
        $contactEmail   = $aItem['contactEmail'] ;

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

        osc_run_hook('item_edit_post');
        
        return $result;
    }
    
    /**
     * Activetes an item
     * @param <type> $secret
     * @param <type> $itemId
     * @return boolean
     */
    public function activate( $id, $secret )
    {
        $item   = $this->manager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s' AND i.fk_i_user_id IS NULL ", $secret, $id);
        $result = $this->manager->update(
            array('e_status' => 'ACTIVE'),
            array('s_secret' => $secret)
        );
        osc_run_hook( 'activate_item', $this->manager->findByPrimaryKey($itemId) );
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
        $this->manager->delete(array('pk_i_id' => $itemId, 's_secret' => $secret));
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
        $item_url   = osc_item_url($item);

        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_send_friend');
        $locale = osc_get_user_locale();

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

        $from = osc_contact_email();
        if( $yourEmail != '' ){
            $from = $yourEmail;
        }

        $from_name = $aItem['yourName'];

        if (osc_notify_contact_friends()) {
            $add_bbc = osc_contact_email() ;
        }

        $params = array(
                    'add_bcc'    => $add_bbc
                    ,'from'      => $from
                    ,'from_name' => $from_name
                    ,'subject'   => $title
                    ,'to'        => $aItem['friendEmail']
                    ,'to_name'   => $aItem['friendName']
                    ,'body'      => $body
                    ,'alt_body'  => $body
                  ) ;

        Params::setParam( 'item_url', $item_url );

        if(osc_sendMail($params)) {
            osc_add_flash_message( _m('We just send your message to ') . $aItem['friendName'] . ".") ;
        } else {
            osc_add_flash_message( _m('We are very sorry but we could not deliver your message to your friend. Try again later.')) ;
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

        $mPages = new Page();
        $aPage = $mPages->findByInternalName('email_item_inquiry');
        $locale = osc_get_user_locale() ;

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
                         $phoneNumber, osc_base_url(), $item['s_title'], osc_item_url($item), $message );

        $title = osc_mailBeauty($content['s_title'], $words);
        $body = osc_mailBeauty($content['s_text'], $words);

        $from = osc_contact_email() ;
        $from_name = osc_page_title() ;
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
                osc_add_flash_message( _m('There has been some erro sending the message')) ;
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
        
        $authorName     = $aItem['authorName'] ;
        $authorEmail    = $aItem['authorEmail'] ;
        $body           = $aItem['body'] ;
        $title          = $aItem['title'] ;
        $itemId         = $aItem['id'] ;

        $item = $this->manager->findByPrimaryKey($itemId) ;

        $itemURL = osc_item_url($item) ;
        
        Params::setParam('itemURL', $itemURL);

        if (osc_moderate_comments()) {
            $status = 'INACTIVE' ;
        } else {
            $status = 'ACTIVE' ;
        }
        if (osc_akismet_key()) {
            require_once LIB_PATH . 'Akismet.class.php' ;
            $akismet = new Akismet(osc_base_url(), osc_akismet_key()) ;
            $akismet->setCommentAuthor($authorName) ;
            $akismet->setCommentAuthorEmail($authorEmail) ;
            $akismet->setCommentContent($body) ;
            $akismet->setPermalink($itemURL) ;

            $status = $akismet->isCommentSpam() ? 'SPAM' : $status ;
        }

        $mComments = new Comment() ;
        $aComment  = array(
                        'dt_pub_date'    => DB_FUNC_NOW
                        ,'fk_i_item_id'   => $itemId
                        ,'s_author_name'  => $authorName
                        ,'s_author_email' => $authorEmail
                        ,'s_title'        => $title
                        ,'s_body'         => $body
                        ,'e_status'       => $status
                    );

        if( $mComments->insert($aComment) ){

            $notify = osc_notify_new_comment() ;
            $admin_email = osc_contact_email() ;
            $prefLocale = osc_language;

            //Notify admin
            if ($notify) {
                $mPages = new Page() ;
                $aPage = $mPages->findByInternalName('email_new_comment_admin') ;
                $locale = osc_get_user_locale() ;

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
                $from_name = osc_page_title ;
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
        }else{
            osc_add_flash_message( _m('We are very sorry but could not save your comment. Try again later.')) ;
        }
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
                
                $aItem['yourName']      = Params::getParam('yourName');
                $aItem['yourEmail']     = Params::getParam('yourEmail');

                $aItem['friendName']    = Params::getParam('friendName');
                $aItem['friendEmail']   = Params::getParam('friendEmail');  

                $aItem['s_title']       = Params::getParam('s_title');
                $aItem['message']       = Params::getParam('message');
            break;
            case 'contact':

                $aItem['id']            = Params::getParam('id') ;
                $aItem['yourEmail']     = Params::getParam('yourEmail') ;
                $aItem['yourName']      = Params::getParam('yourName') ;
                $aItem['message']       = Params::getParam('message') ;
                $aItem['phoneNumber']   = Params::getParam('phoneNumber') ;
            break;
            case 'add_comment':

                $aItem['authorName']     = Params::getParam('authorName') ;
                $aItem['authorEmail']    = Params::getParam('authorEmail') ;
                $aItem['body']           = Params::getParam('body') ;
                $aItem['title']          = Params::getParam('title') ;
                $aItem['id']             = Params::getParam('id') ;


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

            if($this->is_admin){
                $userId = Session::newInstance()->_get('adminId');
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
                    if( Params::getParam('contactName') != '' && Params::getParam('contactEmail') != '' ) {
                        $data['s_name']     = Params::getParam('contactName');
                        $data['s_email']    = Params::getParam('contactEmail');
                    }else{
                        $data = Admin::newInstance()->findByPrimaryKey($userId);
                    }
                    $userId = null;
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


        }else{          // EDIT
            $aItem['secret']    = Params::getParam('secret');
            $aItem['idItem']    = Params::getParam('id');
            // get input hidden name=fk_location_id ?
        }

        // get params
        $aItem['active']        = $active;
        $aItem['userId']        = $userId;
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
        $aItem['showEmail']     = Params::getParam('showEmail');        // OK
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

    public function geocodeAddress($address,$regionName, $cityName, $itemId)
    {
        $locationManager = ItemLocation::newInstance();

        $key = osc_google_maps_key() ;
        $address = sprintf('%s, %s %s', $address, $regionName, $cityName);
        $temp = osc_file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
        $temp = json_decode($temp);
        if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
            $coord = $temp->Placemark[0]->Point->coordinates;
            $locationManager->update (
                    array(
                        'd_coord_lat' => $coord[1]
                        ,'d_coord_long' => $coord[0]
                    )
                    ,array('fk_i_item_id' => $itemId)
            );
        }
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
        require_once osc_base_path() . 'recaptchalib.php';
        if ( Params::getFiles("recaptcha_challenge_field") != '') {
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

        $mPages = new Page();
        $locale = osc_get_user_locale();
        
        if ( osc_item_validation_enabled() ) {
            $aPage = $mPages->findByInternalName('email_item_validation') ;

            $content = array();
            if(isset($aPage['locale'][$locale]['s_title'])) {
                $content = $aPage['locale'][$locale];
            } else {
                $content = current($aPage['locale']);
            }

            $item_url = osc_item_url($item);

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

            $item_url = osc_item_url($item) ;

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

















//
//$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null ;
//switch ($action) {
//    case 'item_edit_post':
//    case 'editItemPost':
//        import_request_variables('p', 'P');
//
//        $country = Country::newInstance()->findByCode($_POST['countryId']);
//        if (count($country) > 0) {
//            $countryId = $country['pk_c_code'];
//            $countryName = $country['s_name'];
//        } else {
//            $countryId = null;
//            $countryName = null;
//        }
//
//        if (isset($_POST['regionId'])) {
//            if (intval($_POST['regionId'])) {
//                $region = Region::newInstance()->findByPrimaryKey($_POST['regionId']);
//                if (count($region) > 0) {
//                    $regionId = $region['pk_i_id'];
//                    $regionName = $region['s_name'];
//                }
//            }
//        } else {
//            $regionId = null;
//            $regionName = $_POST['region'];
//        }
//
//        if (isset($_POST['cityId'])) {
//            if (intval($_POST['cityId'])) {
//                $city = City::newInstance()->findByPrimaryKey($_POST['cityId']);
//                if (count($city) > 0) {
//                    $cityId = $city['pk_i_id'];
//                    $cityName = $city['s_name'];
//                }
//            }
//        } else {
//            $cityId = null;
//            $cityName = $_POST['city'];
//        }
//
//        $location = array(
//            'fk_c_country_code' => $countryId,
//            's_country' => $countryName,
//            'fk_i_region_id' => $regionId,
//            's_region' => $regionName,
//            'fk_i_city_id' => $cityId,
//            's_city' => $cityName,
//            's_city_area' => $_POST['cityArea'],
//            's_address' => $_POST['address']
//        );
//
//        $locationManager = ItemLocation::newInstance();
//        $locationManager->update($location, array('fk_i_item_id' => $itemId));
//
//        // If the Google Maps plugin is well configured, we can try to geodecode the address
//        if (osc_google_maps_key()) {
//            $key = osc_google_maps_key() ;
//            $address = sprintf('%s, %s %s', $_POST['address'], $regionName, $cityName);
//            $temp = osc_file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
//            $temp = json_decode($temp);
//            if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
//                $coord = $temp->Placemark[0]->Point->coordinates;
//                $locationManager->update (
//                        array(
//                            'd_coord_lat' => $coord[1]
//                            ,'d_coord_long' => $coord[0]
//                        )
//                        ,array('fk_i_item_id' => $Pid)
//                );
//            }
//        }
//
//        // Update category numbers
//        $old_item = Item::newInstance()->findByPrimaryKey($Pid) ;
//        if($old_item['fk_i_category_id'] != $PcatId) {
//            CategoryStats::newInstance()->increaseNumItems($PcatId) ;
//            CategoryStats::newInstance()->decreaseNumItems($old_item['fk_i_category_id']) ;
//        }
//        unset($old_item) ;
//
//
//        Item::newInstance()->update (
//                                array(
//                                    'dt_pub_date' => DB_FUNC_NOW
//                                    ,'fk_i_category_id' => $PcatId
//                                    ,'f_price' => $Pprice
//                                    ,'fk_c_currency_code' => $Pcurrency
//                                )
//                                ,array(
//                                    'pk_i_id' => $Pid
//                                    ,'s_secret' => $Psecret
//                            )
//        ) ;
//
//        $data = array() ;
//        foreach ($_REQUEST as $k => $v) {
//            if (preg_match('|(.+?)#(.+)|', $k, $m)) {
//                $data[$m[1]][$m[2]] = $v ;
//            }
//        }
//        foreach ($data as $k => $_data) {
//            Item::newInstance()->updateLocaleForce($Pid, $k, $_data['s_title'], $_data['s_description']) ;
//        }
//
//        $filePhotos = Params::getFiles('photos') ;
//        if($filePhotos != '')
//        {
//            $dao_itemResource = new ItemResource() ;
//            foreach ($_FILES['photos']['error'] as $key => $error) {
//                if ($error == UPLOAD_ERR_OK) {
//                    $resourceName = $_FILES['photos']['name'][$key];
//                    $tmpName = $_FILES['photos']['tmp_name'][$key];
//                    $resourceType = $_FILES['photos']['type'][$key];
//
//                    $dao_itemResource->insert(array(
//                        'fk_i_item_id' => $Pid,
//                        's_name' => $resourceName,
//                        's_content_type' => $resourceType
//                    ));
//                    $resourceId = $dao_itemResource->getConnection()->get_last_id() ;
//
//                    // Create thumbnail
//                    $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
//                    $size = explode('x', osc_thumbnail_dimensions()) ;
//                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;
//
//                    // Create normal size
//                    $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '.png' ;
//                    $size = explode('x', osc_normal_dimensions()) ;
//                    ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath);
//
//                    if(osc_keep_original_image()) {
//                        $path = ABS_PATH . 'oc-content/uploads/' . $resourceId.'_original.png' ;
//                        move_uploaded_file($tmpName, $path) ;
//                    }
//
//                    $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png';
//                    $dao_itemResource->update(
//                                            array('s_path' => $s_path)
//                                            ,array('pk_i_id' => $resourceId, 'fk_i_item_id' => $Pid)
//                    ) ;
//                }
//            }
//            unset($dao_itemResource) ;
//        }
//
//        $_POST['pk_i_id'] = $_POST['id'];
//        osc_run_hook('item_edit_post');
//
//        osc_add_flash_message( _m('Great! We\'ve just update your item.'));
//        break;

//    case 'post_item':
//        $success = true;
//        $is_admin = false;
//        $active = 'INACTIVE';
//
//        if(isset($_SESSION['adminId'])) {
//            $is_admin = true;
//        }
//
//        import_request_variables('p', 'P');
//
//        if(isset($admin) && $admin==TRUE) {
//            if(isset($_REQUEST['userId']) && $_REQUEST['userId']!='') {
//                $userId = $_REQUEST['userId'];
//            } else {
//                $userId = $_SESSION['adminId'];
//            }
//        } else {
//            $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;
//
//            if (osc_reg_user_post()) {
//                if ($userId == null) {
//                    osc_add_flash_message( _m('You new to log-in in order to post a new item.')) ;
//                    osc_redirectTo(osc_login_url()) ;
//                    break ;
//                }
//            }
//
//            if (osc_recaptcha_private_key()) {
//                require_once LIB_PATH . 'recaptchalib.php';
//                if (!empty($_POST["recaptcha_challenge_field"])) {
//                    $resp = recaptcha_check_answer (
//                        osc_recaptcha_private_key()
//                        ,$_SERVER["REMOTE_ADDR"]
//                        ,$_POST["recaptcha_challenge_field"]
//                        ,$_POST["recaptcha_response_field"]
//                    );
//                    if (!$resp->is_valid) {
//                        die(__("The reCAPTCHA wasn't entered correctly. Go back and try it again. (reCAPTCHA said: ") . $resp->error . ")") ;
//                    }
//                }
//            }
//        }
//
//        // first of all, insert the item
//        $code = osc_genRandomPassword();
//
//        $has_to_validate = false ;
//        if(osc_enabled_item_validation()) {
//            $has_to_validate = true ;
//        }
//
//        if($is_admin || !$has_to_validate) {
//            $active = 'ACTIVE';
//        }
//
//        $show_email = isset($_POST['showEmail']) ? $_POST['showEmail'] : '0';
//
//        if ($userId != null) {
//            if(isset($_POST['userId']) && (int) $_POST['userId'] > 0) {
//                $mUser = new User();
//                $data = $mUser->findByPrimaryKey((int)$_POST['userId']);
//                $userId = $_POST['userId'];
//            } else if(isset($admin) && $admin==TRUE) {
//                if(isset($_REQUEST['contactName']) && $_REQUEST['contactName']!='' && isset($_REQUEST['contactEmail']) && $_REQUEST['contactEmail']!='') {
//                    $data['s_name'] = $_REQUEST['contactName'];
//                    $data['s_email'] = $_REQUEST['contactEmail'];
//                } else {
//                    $data = Admin::newInstance()->findByPrimaryKey($userId);
//                    $userId = null;
//                }
//            } else {
//                $data = User::newInstance()->findByPrimaryKey($userId);
//            }
//            $PcontactName = $data['s_name'];
//            $PcontactEmail = $data['s_email'];
//        }
//
//        $Pprice = Params::getParam('price');
//        if($Pprice == '') $Pprice = null;
//
//        if(!isset($PcontactName) || !isset($PcontactEmail) || $PcontactName==null || $PcontactEmail==null || $PcontactName=='' || $PcontactEmail=='') {
//            osc_add_flash_message( _m('You need to input your name and email to be able to publish a new item.'));
//            $success = false;
//        } else {
//            $dao_item = new Item() ;
//            $dao_item->insert(array(
//                'fk_i_user_id' => $userId,
//                'dt_pub_date' => DB_FUNC_NOW,
//                'fk_i_category_id' => $PcatId,
//                'f_price' => $Pprice,
//                'fk_c_currency_code' => $Pcurrency,
//                's_contact_name' => $PcontactName,
//                's_contact_email' => $PcontactEmail,
//                's_secret' => $code,
//                'e_status' => $active,
//                'b_show_email' => $show_email
//            ));
//            $itemId = $dao_item->getConnection()->get_last_id() ;
//
//            // prepare locales
//            $data = array();
//            foreach ($_REQUEST as $k => $v) {
//                if (preg_match('|(.+?)#(.+)|', $k, $m)) {
//                    $data[$m[1]][$m[2]] = $v;
//                }
//            }
//
//            // insert locales
//            foreach ($data as $k => $_data) {
//                $dao_item->insertLocale($itemId, $k, $_data['s_title'], $_data['s_description'], $_data['s_title'] . " " . $_data['s_description']);
//            }
//            unset($dao_item) ;
//
//            // insert location
//            $country = Country::newInstance()->findByCode($_POST['countryId']);
//            if(count($country) > 0) {
//                $countryId = $country['pk_c_code'];
//                $countryName = $country['s_name'];
//            } else {
//                $countryId = null;
//                $countryName = null;
//            }
//
//            if( isset($_POST['regionId']) ) {
//                if( intval($_POST['regionId']) ) {
//                    $region = Region::newInstance()->findByPrimaryKey($_POST['regionId']);
//                    if( count($region) > 0 ) {
//                        $regionId = $region['pk_i_id'];
//                        $regionName = $region['s_name'];
//                    }
//                }
//            } else {
//                $regionId = null;
//                $regionName = $_POST['region'];
//            }
//
//            if( isset($_POST['cityId']) ) {
//                if( intval($_POST['cityId']) ) {
//                    $city = City::newInstance()->findByPrimaryKey($_POST['cityId']);
//                    if( count($city) > 0 ) {
//                        $cityId = $city['pk_i_id'];
//                        $cityName = $city['s_name'];
//                    }
//                }
//            } else {
//                $cityId = null;
//                $cityName = $_POST['city'];
//            }
//
//            if( empty($_POST['cityArea']) ) {
//                $_POST['cityArea'] = null;
//            }
//
//            if( empty($_POST['address']) ) {
//                $_POST['address'] = null;
//            }
//
//            $location = array('fk_i_item_id'      => $itemId,
//                              'fk_c_country_code' => $countryId,
//                              's_country'         => $countryName,
//                              'fk_i_region_id'    => $regionId,
//                              's_region'          => $regionName,
//                              'fk_i_city_id'      => $cityId,
//                              's_city'            => $cityName,
//                              's_city_area'       => $_POST['cityArea'],
//                              's_address'         => $_POST['address']);
//            $locationManager = ItemLocation::newInstance();
//            $locationManager->insert($location);
//
//            // If the Google Maps plugin is well configured, we can try to geodecode the address
//            if (osc_google_maps_key()) {
//                $key = osc_google_maps_key() ;
//                $address = sprintf('%s, %s %s', $_POST['address'], $regionName, $cityName);
//                $temp = file_get_contents(sprintf('http://maps.google.com/maps/geo?q=%s&output=json&sensor=false&key=%s', urlencode($address), $key));
//                $temp = json_decode($temp);
//                if (isset($temp->Placemark) && count($temp->Placemark[0]) > 0) {
//                    $coord = $temp->Placemark[0]->Point->coordinates;
//                    $locationManager->update(
//                            array(
//                                'd_coord_lat' => $coord[1],
//                                'd_coord_long' => $coord[0]
//                            ),
//                            array('fk_i_item_id' => $itemId)
//                    );
//                }
//            }
//
//            if ($is_admin || !$has_to_validate) {
//                CategoryStats::newInstance()->increaseNumItems($PcatId);
//            }
//
//            $filePhotos = Params::getFiles('photos');
//            if($filePhotos != '')
//            {
//                $dao_itemResource = new ItemResource() ;
//
//                foreach ($filePhotos['error'] as $key => $error) {
//                    if ($error == UPLOAD_ERR_OK) {
//                        $resourceName = $filePhotos['name'][$key];
//                        $tmpName = $filePhotos['tmp_name'][$key];
//                        $resourceType = $filePhotos['type'][$key];
//
//                        $dao_itemResource->insert(array(
//                            'fk_i_item_id' => $itemId,
//                            's_name' => $resourceName,
//                            's_content_type' => $resourceType
//                        ));
//                        $resourceId = $dao_itemResource->getConnection()->get_last_id();
//
//                        // Create thumbnail
//                        $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
//                        $size = explode('x', osc_thumbnail_dimensions()) ;
//                        ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;
//
//                        // Create normal size
//                        $thumbnailPath = ABS_PATH . 'oc-content/uploads/' . $resourceId . '.png' ;
//                        $size = explode('x', osc_normal_dimensions()) ;
//                        ImageResizer::fromFile($tmpName)->resizeTo($size[0], $size[1])->saveToFile($thumbnailPath) ;
//
//                        if(osc_keep_original_image()) {
//                            $path = ABS_PATH . 'oc-content/uploads/' . $resourceId.'_original.png' ;
//                            move_uploaded_file($tmpName, $path) ;
//                        }
//
//                        $s_path = 'oc-content/uploads/' . $resourceId . '_thumbnail.png' ;
//                        $dao_itemResource->update(
//                                                array('s_path' => $s_path)
//                                                ,array(
//                                                    'pk_i_id'      => $resourceId
//                                                    ,'fk_i_item_id' => $itemId
//                                                )
//                                           ) ;
//                    }
//                }
//                unset($dao_itemResource) ;
//            }
//
//            if (!isset($_REQUEST['catId'])) {
//                $_REQUEST['catId'] = "" ;
//            }
//
//            osc_run_hook('item_form_post', $_REQUEST['catId'], array('id' => $itemId));
//
//            $item = $manager->findByPrimaryKey($itemId);
//
//            $mPages = new Page();
//            $locale = osc_get_user_locale() ;
//
//            // send an e-mail to the admin with the data of the new item
//            if(!$is_admin) {
//                if (osc_enabled_item_validation()) {
//                    $aPage = $mPages->findByInternalName('email_item_validation') ;
//
//                    $content = array();
//                    if(isset($aPage['locale'][$locale]['s_title'])) {
//                        $content = $aPage['locale'][$locale];
//                    } else {
//                        $content = current($aPage['locale']);
//                    }
//
//                    $item_url = osc_create_item_url($item);
//
//                    $all = '';
//
//                    if (isset($item['locale'])) {
//                        foreach ($item['locale'] as $locale => $data) {
//                            $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'");
//                            $all .= '<br/>';
//                            if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
//                                $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
//                            } else {
//                                $all .= __('Language') . ': ' . $locale . '<br/>';
//                            }
//                            $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
//                            $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
//                            $all .= '<br/>';
//                        }
//                    } else {
//                        $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
//                        $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
//                    }
//
//                    $words   = array();
//                    $words[] = array('{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}', '{ITEM_COUNTRY}',
//                                     '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}', '{USER_NAME}',
//                                     '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}', '{WEB_TITLE}',
//                                     '{VALIDATION_LINK}');
//                    $words[] = array($all, $item['s_description'], $item['s_country'], $item['f_price'],
//                                     $item['s_region'], $item['s_city'], $item['pk_i_id'], $item['s_contact_name'],
//                                     $item['s_contact_email'], osc_base_url(), $item['s_title'], $item_url,
//                                     osc_page_title(), '<a href="' . osc_base_url() .
//                                     'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' .
//                                     $item['s_secret'] . '" >' . osc_base_url() . 'item.php?action=activate&id=' .
//                                     $item['pk_i_id'] . '&secret=' . $item['s_secret'] . '</a>' );
//                    $title = osc_mailBeauty($content['s_title'], $words);
//                    $body = osc_mailBeauty($content['s_text'], $words);
//
//                    $emailParams =  array (
//                                        'subject'  => $title
//                                        ,'to'       => $PcontactEmail
//                                        ,'to_name'  => $PcontactName
//                                        ,'body'     => $body
//                                        ,'alt_body' => $body
//                                    );
//                    osc_sendMail($emailParams) ;
//                }
//
//                if (osc_notify_new_item()) {
//                    $aPage = $mPages->findByInternalName('email_admin_new_item') ;
//
//                    $content = array();
//                    if(isset($aPage['locale'][$locale]['s_title'])) {
//                        $content = $aPage['locale'][$locale] ;
//                    } else {
//                        $content = current($aPage['locale']) ;
//                    }
//
//                    $item_url = osc_create_item_url($item) ;
//
//                    $all = '' ;
//
//                    if (isset($item['locale'])) {
//                        foreach ($item['locale'] as $locale => $data) {
//                            $locale_name = Locale::newInstance()->listWhere("pk_c_code = '" . $locale . "'") ;
//                            $all .= '<br/>';
//                            if (isset($locale_name[0]) && isset($locale_name[0]['s_name'])) {
//                                $all .= __('Language') . ': ' . $locale_name[0]['s_name'] . '<br/>';
//                            } else {
//                                $all .= __('Language') . ': ' . $locale . '<br/>';
//                            }
//                            $all .= __('Title') . ': ' . $data['s_title'] . '<br/>';
//                            $all .= __('Description') . ': ' . $data['s_description'] . '<br/>';
//                            $all .= '<br/>';
//                        }
//                    } else {
//                        $all .= __('Title') . ': ' . $item['s_title'] . '<br/>';
//                        $all .= __('Description') . ': ' . $item['s_description'] . '<br/>';
//                    }
//
//
//                    $words   = array();
//                    $words[] = array('{EDIT_LINK}', '{ITEM_DESCRIPTION_ALL_LANGUAGES}', '{ITEM_DESCRIPTION}',
//                                     '{ITEM_COUNTRY}', '{ITEM_PRICE}', '{ITEM_REGION}', '{ITEM_CITY}', '{ITEM_ID}',
//                                     '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}',
//                                     '{WEB_TITLE}', '{VALIDATION_LINK}');
//                    $words[] = array('<a href="' . osc_base_url() . 'oc-admin/items.php?action=editItem&id=' .
//                                     $item['pk_i_id'] . '" >' . osc_base_url() . 'oc-admin/items.php?action=editItem&id=' .
//                                     $item['pk_i_id'] . '</a>', $all, $item['s_description'], $item['s_country'],
//                                     $item['f_price'], $item['s_region'], $item['s_city'], $item['pk_i_id'],
//                                     $item['s_contact_name'], $item['s_contact_email'], osc_base_url(), $item['s_title'],
//                                     $item_url, osc_page_title(), '<a href="' .
//                                     osc_base_url() . 'item.php?action=activate&id=' . $item['pk_i_id'] .
//                                     '&secret=' . $item['s_secret'] . '" >' . osc_base_url() .
//                                     'item.php?action=activate&id=' . $item['pk_i_id'] . '&secret=' .
//                                     $item['s_secret'] . '</a>' );
//                    $title = osc_mailBeauty($content['s_title'], $words);
//                    $body = osc_mailBeauty($content['s_text'], $words);
//
//                    $emailParams = array(
//                                        'subject'  => $title
//                                        ,'to'       => osc_contact_email()
//                                        ,'to_name'  => 'admin'
//                                        ,'body'     => $body
//                                        ,'alt_body' => $body
//                    ) ;
//                    osc_sendMail($emailParams) ;
//                }
//
//            }
//
//            osc_run_hook('after_item_post') ;
//
//            if($is_admin) {
//                osc_add_flash_message( _m('A new item has been added')) ;
//            } else {
//                if(osc_enabled_item_validation()) {
//                    osc_add_flash_message( _m('Great! You\'ll receive an e-mail to activate your item.')) ;
//                } else {
//                    osc_add_flash_message( _m('Great! We\'ve just published your item.')) ;
//                }
//            }
//        }
//
//    break;
//    default:
//    break;
//}

?>
