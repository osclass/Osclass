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

    class CAdminSettingsAdvanced extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('advanced'):
                    //calling the advanced settings view
                    $this->doView('settings/advanced.php');
                break;
                case('advanced_post'):
                    // updating advanced settings
                    if( defined('DEMO') ) {
                        osc_add_flash_warning_message( _m("This action can't be done because it's a demo site"), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=advanced');
                    }
                    osc_csrf_check();
                    $subdomain_type = Params::getParam('e_type');
                    if(!in_array($subdomain_type, array('category', 'country', 'region', 'city', 'user'))) {
                        $subdomain_type = '';
                    }
                    $iUpdated = osc_set_preference('subdomain_type', $subdomain_type);
                    $iUpdated += osc_set_preference('subdomain_host', Params::getParam('s_host'));

                    if($iUpdated > 0) {
                        osc_add_flash_ok_message( _m("Advanced settings have been updated"), 'admin');
                    }
                    osc_calculate_location_slug(osc_subdomain_type());
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=advanced');
                break;
                case('advanced_cache_flush'):
                    osc_cache_flush();
                    osc_add_flash_ok_message( _m("Cache flushed correctly"), 'admin');
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=advanced');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/main.php