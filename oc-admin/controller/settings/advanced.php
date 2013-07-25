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
                    osc_csrf_check();
                    $subdomain_type = Params::getParam('e_type');
                    if(!in_array($subdomain_type, array('category', 'country', 'region', 'city'))) {
                        $subdomain_type = '';
                    }
                    $iUpdated = Preference::newInstance()->update(array('s_value' => $subdomain_type)
                        ,array('s_name' => 'subdomain_type'));
                    $iUpdated += Preference::newInstance()->update(array('s_value' => Params::getParam('s_host'))
                        ,array('s_name' => 'subdomain_host'));

                    if($iUpdated > 0) {
                        osc_add_flash_ok_message( _m("Advanced settings have been updated"), 'admin');
                    }
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=advanced');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/main.php