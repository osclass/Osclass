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

    class CAdminSettingsSubdomain extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            switch($this->action) {
                case('subdomain'):
                    $bulk_options = array(
                        array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                        array('value' => 'delete_subdomain', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected subdomains?'), strtolower(__('Delete'))),
                            'label' => __('Delete'))
                    );

                    $bulk_options = osc_apply_filter("subdomain_bulk_filter", $bulk_options);
                    $this->_exportVariableToView('bulk_options', $bulk_options);
                    $this->_exportVariableToView('subdomains', osc_unserialize(osc_get_preference('subdomains')));
                    $this->doView('settings/subdomain.php');
                break;
                case('subdomain_post'):
                    osc_csrf_check();
                    $this->redirectTo(osc_admin_base_url(true) . '?page=settings&action=subdomains');
                break;
            }
        }
    }

    // EOF: ./oc-admin/controller/settings/media.php