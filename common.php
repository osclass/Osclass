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

    /* SOME STUFF FOR OC-ADMIN */
    AdminThemes::newInstance()->setCurrentTheme("modern") ;

    /*function osc_renderAdminSection($file, $title = null, $subTitle = null) {
        global $adminTheme;

        extract($GLOBALS);
        require 'themes/' . $adminTheme . '/header.php';

        if(!is_null($title)) {
            $header = $title;
            if(!is_null($subTitle))
                $header .= ': ' . $subTitle;
            echo '<div class="Header">', $header, '</div>';
        }

        require 'themes/' . $adminTheme . '/' . $file;
        require 'themes/' . $adminTheme . '/footer.php';
    }*/

    function osc_renderPluginSection($file) {
        $file = '../../..'.str_replace(ABS_PATH , '', $file);
        osc_renderAdminSection($file, __('Plugins'), __('Configuration'));
    }

    function osc_renderPluginView($file) {
        global $adminTheme;

        extract($GLOBALS);
        require 'themes/' . $adminTheme . '/header.php';

        $header = __('Plugins').': '.__('Configuration');
        echo '<div class="Header">', $header, '</div>';
        require 'themes/' . $adminTheme . '/plugins/view.php';
        require 'themes/' . $adminTheme . '/footer.php';
    }

?>