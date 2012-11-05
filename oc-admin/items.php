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

        function __construct()
        {
            parent::__construct() ;

            //specific things for this class
            $this->itemManager = Item::newInstance() ;
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel() ;

            if(osc_is_moderator() && ($this->action=='settings' || $this->action=='settings_post')) {
                osc_add_flash_error_message(_m("You don't have enough permissions"), "admin");
                $this->redirectTo(osc_admin_base_url()) ;
            }

            //specific things for this class
            switch ($this->action)
            {
                case 'bulk_actions':    $mItems  = new ItemActions( true ) ;
                                        switch ( Params::getParam('bulk_actions') )
                                        {
                                            case 'enable_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->enable($_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been enabled', '%d listings have been enabled',$numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'disable_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->disable((int)$_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been disabled', '%d listings have been disabled',$numSuccess),$numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'activate_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->activate($_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been activated','%d listings have been activated',$numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'deactivate_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->deactivate($_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_m('%d listing has been deactivated', '%d listings have been deactivated',$numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'premium_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->premium($_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been marked as premium','%d listings have been marked as premium', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'depremium_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->premium($_id,false) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d change has been made', '%d changes have been made',$numSuccess) ,$numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'spam_all':
                                                $id = Params::getParam('id') ;
                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->spam($_id) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been marked as spam', '%d listings have been marked as spam',$numSuccess),$numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'despam_all':
                                                $id = Params::getParam('id') ;
                                                if ($id) {
                                                    $numSuccess = 0;
                                                    foreach ($id as $_id) {
                                                        if( $mItems->spam($_id, false) ) {
                                                            $numSuccess++;
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d change has been made', '%d changes have been made', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'delete_all':
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $item = $this->itemManager->findByPrimaryKey($i) ;
                                                            $success = $mItems->delete($item['s_secret'], $item['pk_i_id']);
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been deleted', '%d listings have been deleted', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_spam_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'spam' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked as spam', '%d listings have been unmarked as spam', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_bad_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'bad' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked as missclassified', '%d listings have been unmarked as missclassified', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_dupl_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'duplicated' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked as duplicated', '%d listings have been unmarked as duplicated', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_expi_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'expired' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked as expired', '%d listings have been unmarked as expired', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_offe_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'offensive' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked as offensive', '%d listings have been unmarked as offensive', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                            case 'clear_all';
                                                $id = Params::getParam('id') ;
                                                $success = false;

                                                if($id) {
                                                    $numSuccess = 0;
                                                    foreach($id as $i) {
                                                        if ($i) {
                                                            $success = $this->itemManager->clearStat($i , 'all' ) ;
                                                            if($success) {
                                                                $numSuccess++;
                                                            }
                                                        }
                                                    }
                                                    osc_add_flash_ok_message( sprintf(_mn('%d listing has been unmarked', '%d listings have been unmarked', $numSuccess), $numSuccess), 'admin') ;
                                                }
                                            break;
                                        }
                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
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
                                            osc_add_flash_ok_message( _m('The listing has been deleted'), 'admin') ;
                                        } else {
                                            osc_add_flash_error_message( _m("The listing couldn't be deleted"), 'admin') ;
                                        }

                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
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

                                        $item = $this->itemManager->findByPrimaryKey($id);
                                        $mItems  = new ItemActions( true ) ;

                                        switch ($value) {
                                            case 'ACTIVE':

                                                $success = $mItems->activate( $id ) ;
                                                if( $success && $success > 0 ) {
                                                    osc_add_flash_ok_message( _m('The listing has been activated'), 'admin');
                                                } else if ( !$success ){
                                                    osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                                } else {
                                                    osc_add_flash_error_message( _m("The listing can't be activated because it's blocked"), 'admin');
                                                }

                                                break;
                                            case 'INACTIVE':

                                                $success = $mItems->deactivate( $id ) ;
                                                if( $success && $success > 0 ) {
                                                    osc_add_flash_ok_message( _m('The listing has been deactivated'), 'admin');
                                                } else {
                                                    osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                                }

                                                break;
                                            case 'ENABLE':

                                                $success = $mItems->enable( $id ) ;
                                                if( $success && $success > 0 ) {
                                                    osc_add_flash_ok_message( _m('The listing has been enabled'), 'admin');
                                                } else {
                                                    osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                                }

                                                break;
                                            case 'DISABLE':

                                                $success = $mItems->disable( $id ) ;
                                                if( $success && $success > 0 ) {
                                                    osc_add_flash_ok_message( _m('The listing has been disabled'), 'admin');
                                                } else {
                                                    osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                                }

                                                break;
                                        }

                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
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

                                        $mItems = new ItemActions(true);

                                        if ($mItems->premium($id, $value==1?true:false) ) {
                                            osc_add_flash_ok_message( _m('Changes have been applied'), 'admin');
                                        } else {
                                            osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                        }

                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
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

                                        $mItems = new ItemActions(true);

                                        if( $mItems->spam($id, $value==1?true:false) ){
                                            osc_add_flash_ok_message( _m('Changes have been applied'), 'admin');
                                        } else {
                                            osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                        }

                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
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
                                            osc_add_flash_ok_message( _m('The listing has been unmarked as')." $stat", 'admin') ;
                                        } else {
                                            osc_add_flash_error_message( _m("The listing hasn't been unmarked as")." $stat", 'admin') ;
                                        }

                                        $this->redirectTo( $_SERVER['HTTP_REFERER'] );
                break;
                case 'item_edit':       // edit item
                                        $id = Params::getParam('id') ;

                                        $item = Item::newInstance()->findByPrimaryKey($id);
                                        if (count($item) <= 0) {
                                            $this->redirectTo( osc_admin_base_url(true) . "?page=items" ) ;
                                        }

                                        if( $item['b_active'] ) {
                                            $actions[] = '<a class="btn float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $item['pk_i_id'] . '&amp;value=INACTIVE">' . __('Deactivate') .'</a>' ;
                                        } else {
                                            $actions[] = '<a class="btn btn-red float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $item['pk_i_id'] . '&amp;value=ACTIVE">' . __('Activate') .'</a>' ;
                                        }
                                        if( $item['b_enabled'] ) {
                                            $actions[] = '<a class="btn float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $item['pk_i_id'] . '&amp;value=DISABLE">' . __('Block') .'</a>' ;
                                        } else {
                                            $actions[] = '<a class="btn btn-red float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status&amp;id=' . $item['pk_i_id'] . '&amp;value=ENABLE">' . __('Unblock') .'</a>' ;
                                        }
                                        if( $item['b_premium'] ) {
                                            $actions[] = '<a class="btn float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $item['pk_i_id'] . '&amp;value=0">' . __('Unmark as premium') .'</a>' ;
                                        } else {
                                            $actions[] = '<a class="btn float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_premium&amp;id=' . $item['pk_i_id'] . '&amp;value=1">' . __('Mark as premium') .'</a>' ;
                                        }
                                        if( $item['b_spam'] ) {
                                            $actions[] = '<a class="btn btn-red float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $item['pk_i_id'] . '&amp;value=0">' . __('Unmark as spam') .'</a>' ;
                                        } else {
                                            $actions[] = '<a class="btn float-left" href="' . osc_admin_base_url(true) . '?page=items&amp;action=status_spam&amp;id=' . $item['pk_i_id'] . '&amp;value=1">' . __('Mark as spam') .'</a>' ;
                                        }

                                        $this->_exportVariableToView("actions", $actions);

                                        $form     = count(Session::newInstance()->_getForm());
                                        $keepForm = count(Session::newInstance()->_getKeepForm());

                                        if($form==0 || $form==$keepForm) {
                                            Session::newInstance()->_dropKeepForm();
                                        }

                                        // save referer if belongs to manage items
                                        // redirect only if ManageItems or ReportedListngs
                                        if( isset($_SERVER['HTTP_REFERER']) ) {
                                            $referer = $_SERVER['HTTP_REFERER'] ;
                                            if(preg_match('/page=items/', $referer) ) {
                                                if(preg_match("/action=([\p{L}|_|-]+)/u", $referer, $matches)) {
                                                    if( $matches[1] == 'items_reported' ) {
                                                        Session::newInstance()->_set( 'osc_admin_referer', $referer );
                                                    }
                                                } else {
                                                    // no actions - Manage Listings
                                                    Session::newInstance()->_set( 'osc_admin_referer', $referer );
                                                }
                                            }
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
                                            osc_add_flash_ok_message( _m('Changes saved correctly'), 'admin') ;
                                            $url = osc_admin_base_url(true) . "?page=items" ;
                                            // if Referer is saved that means referer is ManageListings or ReportListings
                                            if(Session::newInstance()->_get('osc_admin_referer')!='') {
                                                $url = Session::newInstance()->_get('osc_admin_referer');
                                            }
                                            Session::newInstance()->_clearVariables();
                                            $this->redirectTo( $url ) ;
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
                                        osc_deleteResource($id, true);
                                        Log::newInstance()->insertLog('items', 'deleteResource', $id, $id, 'admin', osc_logged_admin_id()) ;

                                        $result = ItemResource::newInstance()->delete(array('pk_i_id' => $id, 'fk_i_item_id' => $fkid, 's_name' => $name)) ;
                                        if($result === false) {
                                            osc_add_flash_error_message( _m('An error has occurred'), 'admin');
                                        } else {
                                            osc_add_flash_ok_message( _m('Resource deleted'), 'admin') ;
                                        }
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
                                            $url = osc_admin_base_url(true) . "?page=items" ;
                                            // if Referer is saved that means referer is ManageListings or ReportListings
                                            if(Session::newInstance()->_get('osc_admin_referer')!='') {
                                                Session::newInstance()->_drop('osc_admin_referer');
                                                $url = Session::newInstance()->_get('osc_admin_referer');
                                            }
                                            Session::newInstance()->_clearVariables();
                                            osc_add_flash_ok_message( _m('A new listing has been added'), 'admin') ;
                                            $this->redirectTo( $url ) ;
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
                                        $enabledRecaptchaItems      = (($enabledRecaptchaItems == '1') ? true : false);
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



                                        $msg = '';
                                        if(!osc_validate_int(Params::getParam("items_wait_time"))) {
                                            $msg .= _m("Wait time must only contain numeric characters")."<br/>";
                                        }
                                        if(Params::getParam("num_moderate_items")!='' && !osc_validate_int(Params::getParam("num_moderate_items"))) {
                                            $msg .= _m("Number of moderated listings must only contain numeric characters")."<br/>";
                                        }
                                        if(!osc_validate_int($numImagesItems)) {
                                            $msg .= _m("Images per listing must only contain numeric characters")."<br/>";
                                        }
                                        if($msg!='') {
                                            osc_add_flash_error_message( $msg, 'admin');
                                            $this->redirectTo(osc_admin_base_url(true) . '?page=items&action=settings');
                                        }



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
                                            osc_add_flash_ok_message( _m("Listings' settings have been updated"), 'admin');
                                        }
                                        $this->redirectTo(osc_admin_base_url(true) . '?page=items&action=settings');
                break;
                case('items_reported'):

                                        require_once osc_lib_path()."osclass/classes/datatables/ItemsDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                        // Table header order by related
                                        if( Params::getParam('sort') == '') {
                                            Params::setParam('sort', 'date') ;
                                        }
                                        if( Params::getParam('direction') == '') {
                                            Params::setParam('direction', 'desc');
                                        }

                                        $page  = (int)Params::getParam('iPage');
                                        if($page==0) { $page = 1; };
                                        Params::setParam('iPage', $page);

                                        $params = Params::getParamsAsArray("get") ;

                                        $itemsDataTable = new ItemsDataTable();
                                        $itemsDataTable->tableReported($params);
                                        $aData = $itemsDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] ) ;

                                            $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url) ;
                                                $this->redirectTo($url) ;
                                            }

                                            if($page > 1) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url) ;
                                                $this->redirectTo($url) ;
                                            }
                                        }


                                        $this->_exportVariableToView('aData', $aData) ;
                                        $this->_exportVariableToView('aRawRows', $itemsDataTable->rawRows());

                                        //calling the view...
                                        $this->doView('items/reported.php') ;
                break;
                default:                // default

                                        require_once osc_lib_path()."osclass/classes/datatables/ItemsDataTable.php";

                                        // set default iDisplayLength
                                        if( Params::getParam('iDisplayLength') != '' ) {
                                            Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                                            Cookie::newInstance()->set();
                                        } else {
                                            // set a default value if it's set in the cookie
                                            if( Cookie::newInstance()->get_value('listing_iDisplayLength') != '' ) {
                                                Params::setParam('iDisplayLength', Cookie::newInstance()->get_value('listing_iDisplayLength'));
                                            } else {
                                                Params::setParam('iDisplayLength', 10 );
                                            }
                                        }
                                        $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                                        // Table header order by related
                                        if( Params::getParam('sort') == '') {
                                            Params::setParam('sort', 'date') ;
                                        }
                                        if( Params::getParam('direction') == '') {
                                            Params::setParam('direction', 'desc');
                                        }

                                        $page  = (int)Params::getParam('iPage');
                                        if($page==0) { $page = 1; };
                                        Params::setParam('iPage', $page);

                                        $params = Params::getParamsAsArray("get") ;

                                        $itemsDataTable = new ItemsDataTable();
                                        $itemsDataTable->table($params);
                                        $aData = $itemsDataTable->getData();

                                        if(count($aData['aRows']) == 0 && $page!=1) {
                                            $total = (int)$aData['iTotalDisplayRecords'];
                                            $maxPage = ceil( $total / (int)$aData['iDisplayLength'] ) ;

                                            $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                                            if($maxPage==0) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url) ;
                                                $this->redirectTo($url) ;
                                            }

                                            if($page > 1) {
                                                $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url) ;
                                                $this->redirectTo($url) ;
                                            }
                                        }


                                        $this->_exportVariableToView('aData', $aData) ;
                                        $this->_exportVariableToView('withFilters', $itemsDataTable->withFilters());
                                        $this->_exportVariableToView('aRawRows', $itemsDataTable->rawRows());

                                        //calling the view...
                                        $this->doView('items/index.php') ;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file) ;
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/items.php */
?>
