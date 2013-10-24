<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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

    class CAdminSettingsMain extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('check_updates'):
                    osc_admin_toolbar_update_themes(true);
                    osc_admin_toolbar_update_plugins(true);

                    osc_add_flash_ok_message( _m('Last check') . ':   ' . date("Y-m-d H:i") , 'admin');

                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                break;
                case('update'):
                    // update index view
                    osc_csrf_check();
                    $iUpdated          = 0;
                    $sPageTitle        = Params::getParam('pageTitle');
                    $sPageDesc         = Params::getParam('pageDesc');
                    $sContactEmail     = Params::getParam('contactEmail');
                    $sLanguage         = Params::getParam('language');
                    $sDateFormat       = Params::getParam('dateFormat');
                    $sCurrency         = Params::getParam('currency');
                    $sWeekStart        = Params::getParam('weekStart');
                    $sTimeFormat       = Params::getParam('timeFormat');
                    $sTimezone         = Params::getParam('timezone');
                    $sNumRssItems      = Params::getParam('num_rss_items');
                    $maxLatestItems    = Params::getParam('max_latest_items_at_home');
                    $numItemsSearch    = Params::getParam('default_results_per_page');
                    $contactAttachment = Params::getParam('enabled_attachment');
                    $selectableParent  = Params::getParam('selectable_parent_categories');
                    $bAutoCron         = Params::getParam('auto_cron');
                    $bMarketSources    = (Params::getParam('market_external_sources') != '' ? true: false);

                    // preparing parameters
                    $sPageTitle        = strip_tags($sPageTitle);
                    $sPageDesc         = strip_tags($sPageDesc);
                    $sContactEmail     = strip_tags($sContactEmail);
                    $sLanguage         = strip_tags($sLanguage);
                    $sDateFormat       = strip_tags($sDateFormat);
                    $sCurrency         = strip_tags($sCurrency);
                    $sWeekStart        = strip_tags($sWeekStart);
                    $sTimeFormat       = strip_tags($sTimeFormat);
                    $sNumRssItems      = (int) strip_tags($sNumRssItems);
                    $maxLatestItems    = (int) strip_tags($maxLatestItems);
                    $numItemsSearch    = (int) $numItemsSearch;
                    $contactAttachment = ($contactAttachment != '' ? true : false);
                    $bAutoCron         = ($bAutoCron != '' ? true : false);
                    $error = "";

                    $msg = '';
                    if(!osc_validate_text($sPageTitle)) {
                        $msg .= _m("Page title field is required")."<br/>";
                    }
                    if(!osc_validate_text($sContactEmail)) {
                        $msg .= _m("Contact email field is required")."<br/>";
                    }
                    if(!osc_validate_int($sNumRssItems)) {
                        $msg .= _m("Number of listings in the RSS has to be a numeric value")."<br/>";
                    }
                    if(!osc_validate_int($maxLatestItems)) {
                        $msg .= _m("Max latest listings has to be a numeric value")."<br/>";
                    }
                    if(!osc_validate_int($numItemsSearch)) {
                        $msg .= _m("Number of listings on search has to be a numeric value")."<br/>";
                    }
                    if($msg!='') {
                        osc_add_flash_error_message( $msg, 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                    }

                    $iUpdated += osc_set_preference('pageTitle', $sPageTitle);
                    $iUpdated += osc_set_preference('pageDesc', $sPageDesc);

                    if( !defined('DEMO') ) {
                        $iUpdated += osc_set_preference('contactEmail', $sContactEmail);
                    }
                    $iUpdated += osc_set_preference('language', $sLanguage);
                    $iUpdated += osc_set_preference('dateFormat', $sDateFormat);
                    $iUpdated += osc_set_preference('currency', $sCurrency);
                    $iUpdated += osc_set_preference('weekStart', $sWeekStart);
                    $iUpdated += osc_set_preference('timeFormat', $sTimeFormat);
                    $iUpdated += osc_set_preference('timezone', $sTimezone);
                    $iUpdated += osc_set_preference('marketAllowExternalSources', $bMarketSources);
                    if(is_int($sNumRssItems)) {
                        $iUpdated += osc_set_preference('num_rss_items', $sNumRssItems);
                    } else {
                        if($error != '') $error .= "</p><p>";
                        $error .= _m('Number of listings in the RSS must be an integer');
                    }

                    if(is_int($maxLatestItems)) {
                        $iUpdated += osc_set_preference('maxLatestItems@home', $maxLatestItems);
                    } else {
                        if($error != '') $error .= "</p><p>";
                        $error .= _m('Number of recent listings displayed at home must be an integer');
                    }

                    $iUpdated += osc_set_preference('defaultResultsPerPage@search', $numItemsSearch);
                    $iUpdated += osc_set_preference('contact_attachment', $contactAttachment);
                    $iUpdated += osc_set_preference('auto_cron', $bAutoCron);
                    $iUpdated += osc_set_preference('selectable_parent_categories', $selectableParent);

                    if( $iUpdated > 0 ) {
                        if( $error != '' ) {
                            osc_add_flash_error_message( $error . "</p><p>" . _m('General settings have been updated'), 'admin');
                        } else {
                            osc_add_flash_ok_message( _m('General settings have been updated'), 'admin');
                        }
                    } else if($error != '') {
                        osc_add_flash_error_message( $error , 'admin');
                    }

                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings');
                break;
                default:
                    // calling the view
                    $aLanguages = OSCLocale::newInstance()->listAllEnabled();
                    $aCurrencies = Currency::newInstance()->listAll();

                    $this->_exportVariableToView('aLanguages', $aLanguages);
                    $this->_exportVariableToView('aCurrencies', $aCurrencies);

                    $this->doView('settings/index.php');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/main.php