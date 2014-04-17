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

    class CWebPage extends BaseModel
    {
        var $pageManager;

        function __construct()
        {
            parent::__construct();

            $this->pageManager = Page::newInstance();
        }

        function doModel()
        {
            $id   = Params::getParam('id');
            $page = false;

            if( is_numeric($id) ) {
                $page = $this->pageManager->findByPrimaryKey($id);
            } else {
                $page = $this->pageManager->findByInternalName(Params::getParam('slug'));
            }

            // page not found
            if( $page == false ) {
                $this->do404();
                return;
            }

            // this page shouldn't be shown (i.e.: e-mail templates)
            if( $page['b_indelible'] == 1 ) {
                $this->do404();
                return;
            }

            $kwords = array('{WEB_URL}', '{WEB_TITLE}');
            $rwords = array(osc_base_url(), osc_page_title());
            foreach($page['locale'] as $k => $v) {
                $page['locale'][$k]['s_title'] = str_ireplace($kwords, $rwords, osc_apply_filter('email_description', $v['s_title']));
                $page['locale'][$k]['s_text'] = str_ireplace($kwords, $rwords, osc_apply_filter('email_description', $v['s_text']));
            }

            // export $page content to View
            $this->_exportVariableToView('page', $page);
            if( Params::getParam('lang') != '' ) {
                Session::newInstance()->_set('userLocale', Params::getParam('lang'));
            }

            $meta = json_decode($page['s_meta'], true);

            // load the right template file
            if( file_exists(osc_themes_path() . osc_theme() . '/page-' . $page['s_internal_name'] . '.php') ) {
                $this->doView('page-' . $page['s_internal_name'] . '.php');
            } else if( isset($meta['template']) && file_exists(osc_themes_path() . osc_theme() . '/' . $meta['template']) ) {
                $this->doView($meta['template']);
            } else if( isset($meta['template']) && file_exists(osc_plugins_path() . '/' . $meta['template']) ) {
                osc_run_hook('before_html');
                require osc_plugins_path() . '/' . $meta['template'];
                Session::newInstance()->_clearVariables();
                osc_run_hook('after_html');
            } else {
                $this->doView('page.php');
            }
        }

        function doView($file)
        {
            osc_run_hook('before_html');
            osc_current_web_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook('after_html');
        }
    }

    /* file end: ./page.php */
?>