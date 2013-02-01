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

function osc_listLocales() {
    $languages = array();

    $codes = osc_listLanguageCodes();
    foreach($codes as $code) {
        $path = sprintf('%s%s/index.php', osc_translations_path(), $code);
        $fxName = sprintf('locale_%s_info', $code);
        if(file_exists($path)) {
            require_once $path;
            if(function_exists($fxName)) {
                $languages[$code] = call_user_func($fxName);
                $languages[$code]['code'] = $code;
            }
        }
    }

    return $languages;
}

function osc_checkLocales() {
    $locales = osc_listLocales();

    foreach($locales as $locale) {
        $data = OSCLocale::newInstance()->findByPrimaryKey($locale['code']);
        if( !is_array($data) ) {
            $values = array(
                'pk_c_code'         => $locale['code'],
                's_name'            => $locale['name'],
                's_short_name'      => $locale['short_name'],
                's_description'     => $locale['description'],
                's_version'         => $locale['version'],
                's_author_name'     => $locale['author_name'],
                's_author_url'      => $locale['author_url'],
                's_currency_format' => $locale['currency_format'],
                's_date_format'     => $locale['date_format'],
                's_stop_words'      => $locale['stop_words'],
                'b_enabled'         => 0,
                'b_enabled_bo'      => 1
            );
            $result = OSCLocale::newInstance()->insert($values);

            if( !$result ) {
                return false;
            }

            // if it's a demo, we don't import any sql
            if( defined('DEMO') ) {
                return true;
            }

            // inserting e-mail translations
            $path = sprintf( '%s%s/mail.sql', osc_translations_path(), $locale['code'] );
            if( file_exists($path) ) {
                $sql  = file_get_contents($path);
                $conn = DBConnectionClass::newInstance();
                $c_db = $conn->getOsclassDb();
                $comm = new DBCommandClass( $c_db );
                $result = $comm->importSQL( $sql );
                if( !$result ) {
                    return false;
                }
            }
        } else {
            // update language version
            OSCLocale::newInstance()->update(
                    array('s_version' => $locale['version']),
                    array('pk_c_code' => $locale['code'])
                    );
        }
    }

    return true;
}

function osc_listLanguageCodes() {
    $codes = array();

    $dir = opendir(osc_translations_path());
    while($file = readdir($dir)) {
        if(preg_match('/^[a-z_]+$/i', $file)) {
                    $codes[] = $file;
        }
    }
    closedir($dir);

    return $codes;
}

?>