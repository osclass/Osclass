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

    class CAdminSettingsLatestSearches extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('latestsearches'):
                    //calling the comments settings view
                    $this->doView('settings/searches.php');
                break;
                case('latestsearches_post'):
                    // updating comment
                    osc_csrf_check();
                    if( Params::getParam('save_latest_searches') == 'on' ) {
                        osc_set_preference('save_latest_searches', 1);
                    } else {
                        osc_set_preference('save_latest_searches', 0);
                    }

                    if(Params::getParam('customPurge')=='') {
                        osc_add_flash_error_message(_m('Custom number could not be left empty'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches');
                    } else {
                        osc_set_preference('purge_latest_searches', Params::getParam('customPurge'));

                        osc_add_flash_ok_message( _m('Last search settings have been updated'), 'admin');
                        $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=latestsearches');
                    }
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/latestsearches.php