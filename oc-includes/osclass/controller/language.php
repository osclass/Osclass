<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class CWebLanguage extends BaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        // business layer...
        function doModel()
        {
            $locale = Params::getParam('locale');

            if(preg_match('/.{2}_.{2}/', $locale)) {
                Session::newinstance()->_set('userLocale', $locale);
            }

            $redirect_url = '';
            if($_SERVER['HTTP_REFERER'] != '') {
                $redirect_url = $_SERVER['HTTP_REFERER'];
            } else {
                $redirect_url = osc_base_url(true);
            }

            $this->redirectTo($redirect_url);
        }

        // hopefully generic...
        function doView($file) { }
    }

    /* file end: ./language.php */
?>