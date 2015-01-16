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

    class CAdminSettingsCurrencies extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch( Params::getParam('type') ) {
                case('add'):
                    // calling add currency view
                    $aCurrency = array(
                        'pk_c_code'     => '',
                        's_name'        => '',
                        's_description' => '',
                    );
                    $this->_exportVariableToView('aCurrency', $aCurrency);
                    $this->_exportVariableToView('typeForm', 'add_post');

                    $this->doView('settings/currency_form.php');
                break;
                case('add_post'):
                    // adding a new currency
                    osc_csrf_check();
                    $currencyCode        = Params::getParam('pk_c_code');
                    $currencyName        = Params::getParam('s_name');
                    $currencyDescription = Params::getParam('s_description');

                    // cleaning parameters
                    $currencyName        = trim(strip_tags($currencyName));
                    $currencyDescription = trim(strip_tags($currencyDescription));
                    $currencyCode        = trim(strip_tags($currencyCode));

                    if( !preg_match('/^.{1,3}$/', $currencyCode) ) {
                        osc_add_flash_error_message( _m('The currency code is not in the correct format'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                    }

                    $fields = array(
                        'pk_c_code'     => $currencyCode,
                        's_name'        => $currencyName,
                        's_description' => $currencyDescription,
                    );

                    $isInserted = Currency::newInstance()->insert($fields);

                    if( $isInserted ) {
                        osc_add_flash_ok_message( _m('Currency added'), 'admin');
                    } else {
                        osc_add_flash_error_message( _m("Currency couldn't be added"), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                break;
                case('edit'):
                    // calling edit currency view
                    $currencyCode = Params::getParam('code');
                    $currencyCode = trim(strip_tags($currencyCode));

                    if( $currencyCode == '' ) {
                        osc_add_flash_warning_message( sprintf( _m("The currency code '%s' doesn't exist"), $currencyCode ), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                    }

                    $aCurrency = Currency::newInstance()->findByPrimaryKey($currencyCode);

                    if( !       $aCurrency ) {
                        osc_add_flash_warning_message( sprintf( _m("The currency code '%s' doesn't exist"), $currencyCode ), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                    }

                    $this->_exportVariableToView('aCurrency', $aCurrency);
                    $this->_exportVariableToView('typeForm', 'edit_post');

                    $this->doView('settings/currency_form.php');
                break;
                case('edit_post'):
                    // updating currency
                    osc_csrf_check();
                    $currencyName        = Params::getParam('s_name');
                    $currencyDescription = Params::getParam('s_description');
                    $currencyCode        = Params::getParam('pk_c_code');

                    // cleaning parameters
                    $currencyName        = trim(strip_tags($currencyName));
                    $currencyDescription = trim(strip_tags($currencyDescription));
                    $currencyCode        = trim(strip_tags($currencyCode));

                    if(!preg_match('/.{1,3}/', $currencyCode)) {
                        osc_add_flash_error_message( _m('Error: the currency code is not in the correct format'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                    }

                    $updated = Currency::newInstance()->update(
                            array(
                                's_name'        => $currencyName,
                                's_description' => $currencyDescription
                            ),
                            array('pk_c_code'   => $currencyCode)
                    );

                    if($updated == 1) {
                        osc_add_flash_ok_message( _m('Currency updated'), 'admin');
                    } else {
                        osc_add_flash_info_message( _m('No changes were made'), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                break;
                case('delete'):
                    // deleting a currency
                    osc_csrf_check();
                    $rowChanged    = 0;
                    $aCurrencyCode = Params::getParam('code');

                    if( !is_array($aCurrencyCode) ) {
                        $aCurrencyCode = array($aCurrencyCode);
                    }

                    $msg_current = '';
                    foreach($aCurrencyCode as $currencyCode) {
                        if( preg_match('/.{1,3}/', $currencyCode) && $currencyCode != osc_currency() ) {
                            $rowChanged += Currency::newInstance()->delete( array('pk_c_code' => $currencyCode) );
                        }

                        // foreign key error
                        if( Currency::newInstance()->getErrorLevel() == '1451' ) {
                            $msg_current .= sprintf('</p><p>' . _m("%s couldn't be deleted because it has listings associated to it"), $currencyCode);
                        } else if( $currencyCode == osc_currency() ) {
                            $msg_current .= sprintf('</p><p>' . _m("%s couldn't be deleted because it's the default currency"), $currencyCode);
                        }
                    }

                    $msg    = '';
                    $status = '';
                    switch($rowChanged) {
                        case('0'):
                                    $msg    = _m('No currencies have been deleted');
                                    $status = 'error';
                        break;
                        case('1'):
                                    $msg    = _m('One currency has been deleted');
                                    $status = 'ok';
                        break;
                        default:
                                    $msg    = sprintf( _m('%s currencies have been deleted'), $rowChanged);
                                    $status = 'ok';
                        break;
                    }

                    if( $status == 'ok' && $msg_current != '' ) {
                        $status = 'warning';
                    }

                    switch($status) {
                        case('error'):      osc_add_flash_error_message($msg . $msg_current, 'admin');
                        break;
                        case('warning'):    osc_add_flash_warning_message($msg . $msg_current, 'admin');
                        break;
                        case('ok'):         osc_add_flash_ok_message($msg, 'admin');
                        break;
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=currencies');
                break;
                default:
                    // calling the currencies view
                    $aCurrencies = Currency::newInstance()->listAll();
                    $this->_exportVariableToView('aCurrencies', $aCurrencies);

                    $this->doView('settings/currencies.php');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/currencies.php
