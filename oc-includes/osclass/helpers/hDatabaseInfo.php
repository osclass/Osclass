<?php

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

    /**
    * Helper Database Info
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Gets database name
     *
     * @return string
     */
    function osc_db_name() {
        return getSiteInfo('s_db_name', DB_NAME);
    }

    /**
     * Gets database host
     *
     * @return string
     */
    function osc_db_host() {
        return getSiteInfo('s_db_host', DB_HOST);
    }

    /**
     * Gets database user
     *
     * @return string
     */
    function osc_db_user() {
        return getSiteInfo('s_db_user', DB_USER);
    }

    /**
     * Gets database password
     *
     * @return string
     */
    function osc_db_password() {
        return getSiteInfo('s_db_password', DB_PASSWORD);
    }

    /**
     * Gets multisite url
     *
     * @return string
     */
    function osc_multisite_url() {
        return getSiteInfo('s_site', '');
    }

    /**
     * Gets multisite url
     *
     * @return string
     */
    function osc_multisite_upload_path() {
        return getSiteInfo('s_upload_path', '');
    }

    //PRIVATE FUNCTION FOR GETTING NO BOOLEAN INFORMATION (if there was a class :P)
    /**
     * Gets site info
     *
     * @param string $key
     * @param string $default_value
     * @return string
     */
    function getSiteInfo($key, $default_value) {
        if (MULTISITE) {
            $_P = SiteInfo::newInstance();
            return($_P->get($key));
        }

        return $default_value;
    }
?>
