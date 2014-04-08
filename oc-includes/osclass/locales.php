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