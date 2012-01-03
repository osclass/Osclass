<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    class CAdminItems extends AdminSecBaseModel
    {
        //specific for this class
        private $itemManager ;

        function __construct() {
            parent::__construct() ;

            //specific things for this class
            $this->itemManager = Item::newInstance() ;
        }

        //Business Layer...
        function doModel() {
            parent::doModel() ;

            //specific things for this class
            switch ($this->action)
            {
                case 'bulk_actions':
                                        switch ( Params::getParam('bulk_actions') )
                                        {
                                            case 'enable_all':
                                                $id = Params::getParam('id') ;
                                                $value = 1 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_enabled' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            osc_run_hook( 'enable_item', $_id );
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']) ;
                                                        }

                                                        osc_add_flash_ok_message( sprintf(_mn('%d item has been enabled', '%d items have been enabled',$count), $count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'disable_all':
                                                $id = Params::getParam('id') ;
                                                $value = 0;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_enabled' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            osc_run_hook( 'disable_item', $_id );
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']) ;
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d item has been disabled', '%d items have been disabled',$count),$count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'activate_all':
                                                $id = Params::getParam('id') ;
                                                $value = 1 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_active' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            osc_run_hook( 'activate_item', $_id );
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']) ;
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d item has been activated','%d items have been activated',$count), $count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'deactivate_all':
                                                $id = Params::getParam('id') ;
                                                $value = 0;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                    array('b_active' => $value)
                                                                    ,array('pk_i_id' => $_id)
                                                            ) ;
                                                            osc_run_hook( 'deactivate_item', $_id );
                                                            $item = $this->itemManager->findByPrimaryKey($_id) ;
                                                            CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']) ;
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_m('%d item has been deactivated', '%d items have been deactivated',$count), $count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'premium_all':
                                                $id = Params::getParam('id') ;
                                                $value = 1 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        $mItems = new ItemActions(true);
                                                        foreach ($id as $_id) {
                                                            $mItems->premium($_id);
                                                            osc_run_hook( 'item_preium_on', $_id );
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d item has been marked as premium','%d items have been marked as premium', $count), $count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'depremium_all':
                                                $id = Params::getParam('id') ;
                                                $value = 0 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        $mItems = new ItemActions(true);
                                                        foreach ($id as $_id) {
                                                            $mItems->premium($_id,false);
                                                            osc_run_hook( 'item_preium_off', $_id );
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d change has been made', '%d changes have been made',$count) ,$count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'spam_all':
                                                $id = Params::getParam('id') ;
                                                $value = 1 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                array('b_spam' => $value),
                                                                array('pk_i_id' => $_id)
                                                            );
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d item has been marked as spam', '%d items have been marked as spam',$count),$count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'despam_all':
                                                $id = Params::getParam('id') ;
                                                $value = 0 ;
                                                try {
                                                    if ($id) {
                                                        $count = count($id);
                                                        foreach ($id as $_id) {
                                                            $this->itemManager->update(
                                                                array('b_spam' => $value),
                                                                array('pk_i_id' => $_id)
                                                            );
                                                        }
                                                        osc_add_flash_ok_message( sprintf(_mn('%d change have been made', '%d changes have been made', $count), $count), 'admin') ;
                                                    }
                                                } catch (Exception $e) {
                                                    osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin') ;
                                                }
                                            break;
                                            case 'delete_all':
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if( $id != '' ) {
                                                    $count = count($id);
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $item = $this->itemManager->findByPrimaryKey($i) ;
                                                            $mItems = new ItemActions(true);
                                                            $success = $mItems->delete($item['s_secret'], $item['pk_i_id']);
                                                        }
                                                    }
                                                }
                                                if($success) {
                                                    osc_add_flash_ok_message( sprintf(_mn('%d item has been deleted', '%d items have been deleted', $count), $count), 'admin') ;
                                                } else {
                                                    osc_add_flash_error_message( _m('The item couldn\'t be deleted'), 'admin') ;
                                                }
                                                $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                            break;
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'delete':          //delete
                                        $id      = Params::getParam('id') ;
                                        $success = false ;
                                        
                                        foreach( $id as $i ) {
                                            if ( $i ) {
                                                $aItem   = $this->itemManager->findByPrimaryKey( $i ) ;
                                                $mItems  = new ItemActions( true ) ;
                                                $success = $mItems->delete( $aItem['s_secret'], $aItem['pk_i_id'] ) ;
                                            }
                                        }

                                        if( $success ) {
                                            osc_add_flash_ok_message( _m('The item has been deleted'), 'admin') ;
                                        } else {
                                            osc_add_flash_error_message( _m('The item couldn\'t be deleted'), 'admin') ;
                                        }

                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'status':          //status
                                        $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;

                                        if (!in_array($value, array('ACTIVE', 'INACTIVE','ENABLE','DISABLE')))
                                            return false;

                                        try {
                                            $item = $this->itemManager->findByPrimaryKey($id);
                                            switch ($value) {
                                                case 'ACTIVE':
                                                    $this->itemManager->update(
                                                            array('b_active' => 1),
                                                            array('pk_i_id' => $id));
                                                    osc_add_flash_ok_message( _m('The item has been activated'), 'admin');
                                                    if($item['b_enabled']==1 && $item['b_active']==0) {
                                                        CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']);
                                                        if($item['fk_i_user_id']!=null) {
                                                            $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                                                            if($user) {
                                                                User::newInstance()->update(array( 'i_items' => $user['i_items']+1)
                                                                                    ,array( 'pk_i_id' => $user['pk_i_id'] )
                                                                                    ) ;
                                                            }
                                                        }
                                                    }
                                                    break;
                                                case 'INACTIVE':
                                                    $this->itemManager->update(
                                                            array('b_active' => 0),
                                                            array('pk_i_id' => $id));
                                                    osc_add_flash_ok_message( _m('The item has been deactivated'), 'admin');
                                                    if($item['b_enabled']==1 && $item['b_active']==1) {
                                                        CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                                                        if($item['fk_i_user_id']!=null) {
                                                            $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                                                            if($user) {
                                                                User::newInstance()->update(array( 'i_items' => $user['i_items']-1)
                                                                                    ,array( 'pk_i_id' => $user['pk_i_id'] )
                                                                                    ) ;
                                                            }
                                                        }
                                                    }
                                                    break;
                                                case 'ENABLE':
                                                    $this->itemManager->update(
                                                            array('b_enabled' => 1),
                                                            array('pk_i_id' => $id));
                                                    osc_add_flash_ok_message( _m('The item has been enabled'), 'admin');
                                                    if($item['b_enabled']==0 && $item['b_active']==1) {
                                                        CategoryStats::newInstance()->increaseNumItems($item['fk_i_category_id']);
                                                        if($item['fk_i_user_id']!=null) {
                                                            $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                                                            if($user) {
                                                                User::newInstance()->update(array( 'i_items' => $user['i_items']+1)
                                                                                    ,array( 'pk_i_id' => $user['pk_i_id'] )
                                                                                    ) ;
                                                            }
                                                        }
                                                    }
                                                    break;
                                                case 'DISABLE':
                                                    $this->itemManager->update(
                                                            array('b_enabled' => 0),
                                                            array('pk_i_id' => $id));
                                                    osc_add_flash_ok_message( _m('The item has been disabled'), 'admin');
                                                    if($item['b_enabled']==1 && $item['b_active']==1) {
                                                        CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
                                                        if($item['fk_i_user_id']!=null) {
                                                            $user = User::newInstance()->findByPrimaryKey($item['fk_i_user_id']);
                                                            if($user) {
                                                                User::newInstance()->update(array( 'i_items' => $user['i_items']-1)
                                                                                    ,array( 'pk_i_id' => $user['pk_i_id'] )
                                                                                    ) ;
                                                            }
                                                        }
                                                    }
                                                    break;
                                            }

                                        } catch (Exception $e) {
                                            osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'status_premium':  //status premium
                                        $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;

                                        if (!in_array($value, array(0, 1)))
                                            return false;

                                        try {
                                            $mItems = new ItemActions(true);
                                            $mItems->premium($id, $value==1?true:false);
                                            /*$this->itemManager->update(
                                                    array('b_premium' => $value),
                                                    array('pk_i_id' => $id)
                                            );*/
                                            osc_add_flash_ok_message( _m('Changes have been applied'), 'admin');
                                        } catch (Exception $e) {
                                            osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'status_spam':  //status spam
                                        $id = Params::getParam('id') ;
                                        $value = Params::getParam('value') ;

                                        if (!$id)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;

                                        if (!in_array($value, array(0, 1)))
                                            return false;

                                        try {
                                            $this->itemManager->update(
                                                    array('b_spam' => $value),
                                                    array('pk_i_id' => $id)
                                            );
                                            osc_add_flash_ok_message( _m('Changes have been applied'), 'admin');
                                        } catch (Exception $e) {
                                            osc_add_flash_error_message( sprintf(_m('Error: %s'), $e->getMessage()), 'admin');
                                        }
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'clear_stat':
                                        $id     = Params::getParam('id') ;
                                        $stat   = Params::getParam('stat') ;

                                        if (!$id)
                                            return false;

                                        if (!$stat)
                                            return false;

                                        $id = (int) $id;

                                        if (!is_numeric($id))
                                            return false;
                                       
                                        $success = $this->itemManager->clearStat($id , $stat ) ;

                                        if($success) {
                                            osc_add_flash_ok_message( _m('The item has been unmarked as')." $stat", 'admin') ;
                                        } else {
                                            osc_add_flash_error_message( _m('The item hasn\'t been unmarked as')." $stat", 'admin') ;
                                        }

                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items&stat=".$stat ) ;

                break;
                case 'item_edit':       // edit item
                                        $id = Params::getParam('id') ;

                                        $item = Item::newInstance()->findByPrimaryKey($id);
                                        if (count($item) <= 0) {
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        }

                                        $form     = count(Session::newInstance()->_getForm());
                                        $keepForm = count(Session::newInstance()->_getKeepForm());
                                        if($form==0 || $form==$keepForm) {
                                            Session::newInstance()->_dropKeepForm();
                                        }

                                        $this->_exportVariableToView("item", $item);
                                        $this->_exportVariableToView("new_item", FALSE);

                                        $this->doView('items/frm.php') ;
                break;
                case 'item_edit_post':
                                        $mItems = new ItemActions(true);
                    
                                        $mItems->prepareData(false);
                                        // set all parameters into session
                                        foreach( $mItems->data as $key => $value ) {
                                            Session::newInstance()->_setForm($key,$value);
                                        }

                                        $meta = Params::getParam('meta');
                                        if(is_array($meta)) {
                                            foreach( $meta as $key => $value ) {
                                                Session::newInstance()->_setForm('meta_'.$key, $value);
                                                Session::newInstance()->_keepForm('meta_'.$key);
                                            }
                                        }
                    
                                        $success = $mItems->edit();
                                        
                                        if($success==1){
                                            $id = Params::getParam('userId') ;
                                            if($id !='') {
                                                $user = User::newInstance()->findByPrimaryKey( $id );
                                                Item::newInstance()->update(array(
                                                    'fk_i_user_id' => $id,
                                                    's_contact_name' => $user['s_name'],
                                                    's_contact_email' => $user['s_email']
                                                ), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret') ) );
                                            } else {
                                                Item::newInstance()->update(array(
                                                    'fk_i_user_id' => NULL,
                                                    's_contact_name' => Params::getParam('contactName'),
                                                    's_contact_email' => Params::getParam('contactEmail')
                                                ), array('pk_i_id' => Params::getParam('id'), 's_secret' => Params::getParam('secret') ) );
                                            }
                                            osc_add_flash_ok_message( _m('Changes saved correctly'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        } else {
                                            osc_add_flash_error_message( $success , 'admin');
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items&action=item_edit&id=" . Params::getParam('id') );
                                        }
                break;
                case 'deleteResource':  //delete resource
                                        $id = Params::getParam('id') ;
                                        $name = Params::getParam('name') ;
                                        $fkid = Params::getParam('fkid') ;

                                        // delete files
                                        osc_deleteResource($id);

                                        ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name)) ;
                                        osc_add_flash_ok_message( _m('Resource deleted'), 'admin') ;
                                        $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                break;
                case 'post':            // add item
                                        $form     = count(Session::newInstance()->_getForm());
                                        $keepForm = count(Session::newInstance()->_getKeepForm());
                                        if($form == 0 || $form == $keepForm) {
                                            Session::newInstance()->_dropKeepForm();
                                        }

                                        $this->_exportVariableToView("new_item", TRUE);
                                        $this->doView('items/frm.php') ;
                break;
                case 'post_item':       //post item
                                        $mItem = new ItemActions(true);
                    
                                        $mItem->prepareData(true);
                                        // set all parameters into session
                                        foreach( $mItem->data as $key => $value ) {
                                            Session::newInstance()->_setForm($key,$value);
                                        }
                                        
                                        $meta = Params::getParam('meta');

                                        if(is_array($meta)) {
                                            foreach( $meta as $key => $value ) {
                                                Session::newInstance()->_setForm('meta_'.$key, $value);
                                                Session::newInstance()->_keepForm('meta_'.$key);
                                            }
                                        }
                    
                                        $success = $mItem->add();
                                        
                                        if( $success==1 || $success==2 ) {
                                            osc_add_flash_ok_message( _m('A new item has been added'), 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        } else {
                                            osc_add_flash_error_message( $success, 'admin') ;
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items&action=post" ) ;
                                        }
                break;
                case('settings'):          // calling the items settings view
                                        $this->doView('items/settings.php');
                break;
                case('settings_post'):     // update item settings
                                        $iUpdated                   = 0;
                                        $enabledRecaptchaItems      = Params::getParam('enabled_recaptcha_items');
                                        $enabledRecaptchaItems      = (($enabledRecaptchaItems != '') ? true : false);
                                        $moderateItems              = Params::getParam('moderate_items');
                                        $moderateItems              = (($moderateItems != '') ? true : false);
                                        $numModerateItems           = Params::getParam('num_moderate_items');
                                        $itemsWaitTime              = Params::getParam('items_wait_time');
                                        $loggedUserItemValidation   = Params::getParam('logged_user_item_validation');
                                        $loggedUserItemValidation   = (($loggedUserItemValidation != '') ? true : false);
                                        $regUserPost                = Params::getParam('reg_user_post');
                                        $regUserPost                = (($regUserPost != '') ? true : false);
                                        $notifyNewItem              = Params::getParam('notify_new_item');
                                        $notifyNewItem              = (($notifyNewItem != '') ? true : false);
                                        $notifyContactItem          = Params::getParam('notify_contact_item');
                                        $notifyContactItem          = (($notifyContactItem != '') ? true : false);
                                        $notifyContactFriends       = Params::getParam('notify_contact_friends');
                                        $notifyContactFriends       = (($notifyContactFriends != '') ? true : false);
                                        $enabledFieldPriceItems     = Params::getParam('enableField#f_price@items');
                                        $enabledFieldPriceItems     = (($enabledFieldPriceItems != '') ? true : false);
                                        $enabledFieldImagesItems    = Params::getParam('enableField#images@items');
                                        $enabledFieldImagesItems    = (($enabledFieldImagesItems != '') ? true : false);
                                        $numImagesItems             = Params::getParam('numImages@items');
                                        if($numImagesItems=='') { $numImagesItems = 0; }
                                        $regUserCanContact          = Params::getParam('reg_user_can_contact');
                                        $regUserCanContact          = (($regUserCanContact != '') ? true : false);
                                        $contactItemAttachment      = Params::getParam('item_attachment');
                                        $contactItemAttachment      = (($contactItemAttachment != '') ? true : false);

                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $enabledRecaptchaItems)
                                                                                      ,array('s_name'  => 'enabled_recaptcha_items'));
                                        if($moderateItems) {
                                            $iUpdated += Preference::newInstance()->update(array('s_value' => $numModerateItems)
                                                                                          ,array('s_name' => 'moderate_items'));
                                        } else {
                                            $iUpdated += Preference::newInstance()->update(array('s_value' => '-1')
                                                                                          ,array('s_name' => 'moderate_items'));
                                        }
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $loggedUserItemValidation)
                                                                                      ,array('s_name'  => 'logged_user_item_validation'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $regUserPost)
                                                                                      ,array('s_name'  => 'reg_user_post'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $notifyNewItem)
                                                                                      ,array('s_name'  => 'notify_new_item'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $notifyContactItem)
                                                                                      ,array('s_name'  => 'notify_contact_item'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $notifyContactFriends)
                                                                                      ,array('s_name'  => 'notify_contact_friends'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $enabledFieldPriceItems)
                                                                                      ,array('s_name'  => 'enableField#f_price@items'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $enabledFieldImagesItems)
                                                                                      ,array('s_name'  => 'enableField#images@items'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $itemsWaitTime)
                                                                                      ,array('s_name'  => 'items_wait_time'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $numImagesItems)
                                                                                      ,array('s_name'  => 'numImages@items'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $regUserCanContact)
                                                                                      ,array('s_name'  => 'reg_user_can_contact'));
                                        $iUpdated += Preference::newInstance()->update(array('s_value' => $contactItemAttachment)
                                                                                      ,array('s_name'  => 'item_attachment'));

                                        if($iUpdated > 0) {
                                            osc_add_flash_ok_message( _m('Items\' settings have been updated'), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=items&action=settings');
                break;

                default:                //default
                                        $catId = Params::getParam('catId') ;
                    
                                        $countries = Country::newInstance()->listAll() ;
                                        $regions = array() ;
                                        if( count($countries) > 0 ) {
                                            $regions = Region::newInstance()->findByCountry($countries[0]['pk_c_code']) ;
                                        }
                                        $cities = array() ;
                                        if( count($regions) > 0 ) {
                                            $cities = City::newInstance()->findByRegion($regions[0]['pk_i_id']) ;
                                        }
                                        //preparing variables for the view
                                        $this->_exportVariableToView("users", User::newInstance()->listAll());
                                        $this->_exportVariableToView("catId", $catId) ;
                                        $this->_exportVariableToView("stat", Params::getParam('stat')) ;

                                        $this->_exportVariableToView("countries", $countries);
                                        $this->_exportVariableToView("regions", $regions);
                                        $this->_exportVariableToView("cities", $cities);
                                        //calling the view...
                                        $this->doView('items/index.php') ;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
        }
    }

?>
