<?php
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

    /**
    * Helper Translation
    * @package OSClass
    * @subpackage Helpers
    * @author OSClass
    */

    /**
     * Translate strings
     *
     * @param string $key
     * @param string $domain
     * @return string
     */
    function __($key, $domain = 'core') {
        $gt = Translation::newInstance()->_get($domain);
        
        if(!$gt) {
            return $key;
        }
        return $gt->translate($key);
    }

    /**
     * Translate strings (echo them)
     *
     * @param string $key
     * @param string $domain
     * @return string
     */
    function _e($key, $domain = 'core') {
        $gt = Translation::newInstance()->_get($domain);

        if(!$gt) {
            echo $key;
            return '';
        }
        echo $gt->translate($key);
        return '';
    }

    /**
     * Translate string (flash messages)
     *
     * @param string $key
     * @return string
     */
    function _m($key) {
        return __($key, 'messages');
    }

    /**
     * Retrieve the singular or plural translation of the string.
     *
     * @since 2.2
     *
     * @param string $single_key
     * @param string $plural_key
     * @param int $count
     * @param string $domain
     * @return string
     */
    function _n($single_key, $plural_key, $count, $domain = 'core') {
        $gt = Translation::newInstance()->_get($domain);

        if(!$gt) {
            return $key;
        }
        return $gt->ngettext($single_key, $plural_key, $count);
    }

    /**
     * Retrieve the singular or plural translation of the string.
     *
     * @since 2.2
     *
     * @param string $single_key
     * @param string $plural_key
     * @param int $count
     * @return string
     */
    function _mn($single_key, $plural_key, $count) {
        return _n($single_key, $plural_key, $count, 'messages');
    }

?>