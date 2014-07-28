<?php
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
                <h1><?php echo $title; ?></h1>
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
