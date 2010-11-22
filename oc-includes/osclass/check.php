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


define('INST_DIR', APP_PATH . '/oc-installer');
define('CONFIG_FILE', APP_PATH . '/config.php');

if (!file_exists(CONFIG_FILE)) {
    if (!is_dir(INST_DIR)) {
        echo '<p>You don\' have installed OSClass.</p>';
        echo '<p>In addition, it\'s missing oc-installer folder too. Check if you have decompressed all the files.</p>';
        die;
    } else {
        header('Location: oc-installer/');
    }
}

require_once CONFIG_FILE;

if (file_exists(INST_DIR) && is_dir(INST_DIR)) {
    if (defined('DB_HOST') && defined('DB_USER') && defined('DB_PASSWORD') && defined('DB_NAME')) {
        echo '<p>To run the OSClass you must remove the folder (' . INST_DIR . ') .</p>';
        echo '<p>If you want to reinstall the software follow this <a href="oc-installer">link</a>.</p>';
        exit;
    } else {
        header('Location: oc-installer/');
    }
}

