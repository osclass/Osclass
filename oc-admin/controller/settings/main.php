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

                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sPageTitle),
                        array('s_section' => 'osclass', 's_name' => 'pageTitle')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sPageDesc),
                        array('s_section' => 'osclass', 's_name' => 'pageDesc')
                    );

                    if( !defined('DEMO') ) {
                        $iUpdated += Preference::newInstance()->update(
                            array('s_value'   => $sContactEmail),
                            array('s_section' => 'osclass', 's_name' => 'contactEmail')
                        );
                    }
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sLanguage),
                        array('s_section' => 'osclass', 's_name' => 'language')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sDateFormat),
                        array('s_section' => 'osclass', 's_name' => 'dateFormat')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sCurrency),
                        array('s_section' => 'osclass', 's_name' => 'currency')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sWeekStart),
                        array('s_section' => 'osclass', 's_name' => 'weekStart')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sTimeFormat),
                        array('s_section' => 'osclass', 's_name' => 'timeFormat')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $sTimezone),
                        array('s_section' => 'osclass', 's_name' => 'timezone')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value'   => $bMarketSources),
                        array('s_section' => 'osclass', 's_name' => 'marketAllowExternalSources')
                    );
                    if(is_int($sNumRssItems)) {
                        $iUpdated += Preference::newInstance()->update(
                            array('s_value'   => $sNumRssItems),
                            array('s_section' => 'osclass', 's_name' => 'num_rss_items')
                        );
                    } else {
                        if($error != '') $error .= "</p><p>";
                        $error .= _m('Number of listings in the RSS must be an integer');
                    }

                    if(is_int($maxLatestItems)) {
                        $iUpdated += Preference::newInstance()->update(
                            array('s_value'   => $maxLatestItems),
                            array('s_section' => 'osclass', 's_name' => 'maxLatestItems@home')
                        );
                    } else {
                        if($error != '') $error .= "</p><p>";
                        $error .= _m('Number of recent listings displayed at home must be an integer');
                    }

                    $iUpdated += Preference::newInstance()->update(
                            array('s_value'   => $numItemsSearch),
                            array('s_section' => 'osclass',
                                  's_name'    => 'defaultResultsPerPage@search')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value' => $contactAttachment),
                        array('s_name'  => 'contact_attachment')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value' => $bAutoCron),
                        array('s_name' => 'auto_cron')
                    );
                    $iUpdated += Preference::newInstance()->update(
                        array('s_value' => $selectableParent),
                        array('s_name'  => 'selectable_parent_categories')
                    );

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