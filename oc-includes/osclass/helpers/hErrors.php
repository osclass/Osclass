<?php
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    /**
    * Helper Error
    * @package Osclass
    * @subpackage Helpers
    * @author Osclass
    */

    /**
     * Kill Osclass with an error message
     *
     * @since 1.2
     *
     * @param string $message Error message
     * @param string $title Error title
     */
    function osc_die($title, $message) {
        ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title><?php echo $title; ?></title>
                <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_get_absolute_url(); ?>oc-includes/osclass/installer/install.css" />
            </head>
            <body class="page-error">
                <p><?php echo $message; ?></p>
            </body>
        </html>
        <?php die(); ?>
    <?php }

    function osc_get_absolute_url() {
        $protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . preg_replace('/((oc-admin)|(oc-includes)|(oc-content)|([a-z]+\.php)|(\?.*)).*/i', '', $_SERVER['REQUEST_URI']);
    }

?>