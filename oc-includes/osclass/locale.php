<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
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

require_once ABS_PATH . 'oc-includes/php-gettext/streams.php';
require_once ABS_PATH . 'oc-includes/php-gettext/gettext.php';

require_once ABS_PATH . 'oc-includes/osclass/locales.php';
require_once ABS_PATH . 'oc-includes/osclass/session.php';

$locale = osc_getDefaultLanguage();
if(defined('OC_ADMIN')) {
	if(isset($_SESSION['adminLocale']))
		$locale = $_SESSION['adminLocale'];
} else {
	if(isset($_SESSION['locale']))
		$locale = $_SESSION['locale'];
}

$streamer = new FileReader(ABS_PATH . 'oc-includes/translations/' . $locale . '/messages.mo');
$gt['default'] = new gettext_reader($streamer);

/**
 * @return string the default application language
 */
function osc_getDefaultLanguage() {
    return osc_language() ;
}

function __($key, $domain = 'default') {
    global $gt;
    if(!isset($gt[$domain])) {
        return $key;
    }
    return $gt[$domain]->translate($key);
}

function _e($key, $domain = 'default') {
    global $gt;
    if(!isset($gt[$domain])) {
        echo $key;
        return true;
    }
    echo $gt[$domain]->translate($key);
    return true;
}

function osc_loadTranslation($dir = '', $domain = null) {
    global $gt, $locale;

    if(is_null($domain)) {
        return false;
    }

    if(file_exists($dir.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$locale.'.mo')) {
        $file = $dir.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$locale.'.mo';
    } else if(file_exists($dir.DIRECTORY_SEPARATOR.'locales'.DIRECTORY_SEPARATOR.$locale.'.mo')) {
        $file = $dir.DIRECTORY_SEPARATOR.'locales'.DIRECTORY_SEPARATOR.$locale.'.mo';
    } else if(file_exists($dir.DIRECTORY_SEPARATOR.$locale.'.mo')) {
        $file = $dir.DIRECTORY_SEPARATOR.$locale.'.mo';
    } else {
        return false;
    }
    
    $streamer = new FileReader($file);
    if(!isset($gt[$domain])) {
        $gt[$domain] = new gettext_reader($streamer);
    }
}


?>
