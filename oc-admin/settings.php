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

    class CAdminSettings extends AdminSecBaseModel
    {
        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('advanced'):
                case('advanced_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/advanced.php');
                    $do = new CAdminSettingsAdvanced();
                break;
                case('comments'):
                case('comments_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/comments.php');
                    $do = new CAdminSettingsComments();
                break;
                case ('locations'):
                    require_once(osc_admin_base_path() . 'controller/settings/locations.php');
                    $do = new CAdminSettingsLocations();
                break;
                case('permalinks'):
                case('permalinks_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/permalinks.php');
                    $do = new CAdminSettingsPermalinks();
                break;
                case('spamNbots'):
                case('akismet_post'):
                case('recaptcha_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/spamnbots.php');
                    $do = new CAdminSettingsSpamnBots();
                break;
                case('currencies'):
                    require_once(osc_admin_base_path() . 'controller/settings/currencies.php');
                    $do = new CAdminSettingsCurrencies();
                break;
                case('mailserver'):
                case('mailserver_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/mailserver.php');
                    $do = new CAdminSettingsMailserver();
                break;
                case('media'):
                case('media_post'):
                case('images_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/media.php');
                    $do = new CAdminSettingsMedia();
                break;
                case('latestsearches'):
                case('latestsearches_post'):
                    require_once(osc_admin_base_path() . 'controller/settings/latestsearches.php');
                    $do = new CAdminSettingsLatestSearches();
                break;
                case('update'):
                case('check_updates'):
                default:
                    require_once(osc_admin_base_path() . 'controller/settings/main.php');
                    $do = new CAdminSettingsMain();
                break;
            }

            $do->doModel();
        }
    }

    /* file end: ./oc-admin/settings.php */
