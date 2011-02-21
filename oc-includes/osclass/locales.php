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

function osc_listLocales() {
    $languages = array();

    $codes = osc_listLanguageCodes();
    foreach($codes as $code) {
        $path = sprintf('%soc-includes/translations/%s/index.php', ABS_PATH, $code);
        if(file_exists($path)) {
            require $path;
            $fxName = sprintf('locale_%s_info', $code);
            if(function_exists($fxName)) {
                $lang = call_user_func($fxName);
                $lang['code'] = $code;
                $languages[] = $lang;
            }
        }
    }

    return $languages;
}

function osc_checkLocales() {
    $locales = osc_listLocales();
    foreach($locales as $locale) {
        $data = Locale::newInstance()->findByPrimaryKey($locale['code']);
        if(!is_array($data)) {
            Locale::newInstance()->insert(array('pk_c_code' => $locale['code'], 's_name' => $locale['name'], 's_short_name' => $locale['short_name'], 's_description' => $locale['description'], 's_version' => $locale['version'], 's_author_name' => $locale['author_name'], 's_author_url' => $locale['author_url'], 's_currency_format' => $locale['currency_format'], 's_date_format' => $locale['date_format'], 's_stop_words' => $locale['stop_words'], 'b_enabled' => 0, 'b_enabled_bo' => 1 ));
        }
    }

    $locales_db = Locale::newInstance()->listAll();
    foreach($locales_db as $locale) {
        $found = false;
        foreach($locales as $test) {
            if($test['code']==$locale['pk_c_code']) {
                $found = true; break;
            }
        }
        
        if(!$found) {
            try {
                Locale::newInstance()->deleteLocale($locale['pk_c_code']);
            } catch (Exception $e) {
                if($e->getMessage()=='1451') {
                    osc_add_flash_message( _m('We are missing the ').$locale['pk_c_code'].__(' language pack and it was being. Please reinstall it.'));
                }
            }
        };
    }

}

function osc_listLanguageCodes() {
    $codes = array();

    $dir = opendir(ABS_PATH . 'oc-includes/translations');
    while($file = readdir($dir)) {
        if(preg_match('/^[a-z_]+$/i', $file)) {
                    $codes[] = $file;
        }
    }
    closedir($dir);

    return $codes;
}

/**
 * Get the actual locale of the user.
 *
 * You get the right locale code. If an user is using the website in another language different of the default one, or
 * the user uses the default one, you'll get it.
 *
 * @return string Locale Code
 */
function osc_get_user_locale($typeUser = 'user') {
    $locale = null ;
    if(isset($_SESSION['locale'])) {
        return $_SESSION['locale'] ;
    }

    if ($typeUser == 'user') {
        $locale = osc_language() ;
    } else {
        $locale = osc_admin_language() ;
    }

    return $locale ;
}

?>