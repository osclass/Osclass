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

    class CAdminMarket extends AdminSecBaseModel
    {
        function __construct()
        {
            parent::__construct();
        }

        //Business Layer...
        function doModel()
        {
            parent::doModel();
            //specific things for this class
            switch ($this->action) {
                case('plugins'):
                case('themes'):
                case('languages'):
                    $section = $this->action;
                    $title = array(
                        'plugins'    => __('Recommended plugins for You'),
                        'themes'     => __('Recommended themes for You'),
                        'languages'  => __('Languages for this version')
                        );

                    // page number
                    $marketPage     = Params::getParam("mPage");
                    $url_actual     = osc_admin_base_url(true) . '?page=market&action='.$section.'&mPage='.$marketPage;
                    if($marketPage>=1) $marketPage--;

                    // api
                    $url            = osc_market_url($section)."page/".$marketPage.'/length/9/';
                    // default sort
                    $sort_actual    = '';
                    $sort_download  = $url_actual.'&sort=downloads&order=desc';
                    $sort_updated   = $url_actual.'&sort=updated&order=desc';

                    // sorting options (default)
                    $_order         = 'desc';
                    $order_download = $_order;
                    $order_updated  = $_order;

                    $sort           = Params::getParam("sort");
                    $order          = Params::getParam("order");

                    if($sort=='') {
                        $sort = 'updated';
                    }
                    if($order=='') {
                        $order = $_order;
                    }

                    $aux = ($order=='desc')?'asc':'desc';

                    switch ($sort) {
                        case 'downloads':
                            $sort_actual    = '&sort=downloads&order=';
                            $sort_download  = $url_actual.$sort_actual.$aux;
                            $sort_actual   .= $order;
                            $order_download = $order;
                            // market api call
                            $url .= 'order/downloads/'.$order;
                        break;
                        case 'updated':
                            $sort_actual    = '&sort=updated&order=';
                            $sort_updated   = $url_actual.$sort_actual.$aux;
                            $sort_actual   .= $order;
                            $order_updated  = $order;
                            // market api call
                            $url .= 'order/updated/'.$order;
                        break;
                        default:
                        break;
                    }

                    // pageSize or length attribute is hardcoded
                    $out    = osc_file_get_contents($url);
                    $array  = json_decode($out, true);

                    $output_pagination = '';
                    if( is_numeric($array['total']) && $array['total']>0 ) {
                        $totalPages = ceil( $array['total'] / $array['sizePage'] );
                        $pageActual = $array['page'];
                        $params     = array(
                            'total'    => $totalPages,
                            'selected' => $pageActual,
                            'url'      => osc_admin_base_url(true).'?page=market'.'&amp;action='.$section.'&amp;mPage={PAGE}'.$sort_actual,
                            'sides'    => 5
                        );
                        // set pagination
                        $pagination = new Pagination($params);
                        $output_pagination = $pagination->doPagination();
                    } else {
                        $array['total'] = 0;
                    }

                    // export variable to view
                    $this->_exportVariableToView("sort"      , $sort);
                    $this->_exportVariableToView("title"     , $title);
                    $this->_exportVariableToView("section"   , $section);
                    $this->_exportVariableToView("array"     , $array);

                    $this->_exportVariableToView("sort_download"     , $sort_download);
                    $this->_exportVariableToView("sort_updated"      , $sort_updated);

                    $this->_exportVariableToView("order_download"     , $order_download);
                    $this->_exportVariableToView("order_updated"      , $order_updated);


                    $this->_exportVariableToView('pagination', $output_pagination);

                    $this->doView("market/section.php");
                break;
                default:
                    $aPlugins       = array();
                    $aThemes        = array();
                    $aLanguages     = array();

                    $out_plugin     = osc_file_get_contents(osc_market_featured_url('plugins', 6) );
                    $array_plugins  = json_decode($out_plugin, true);
                    if(isset($array_plugins)) {
                        $aPlugins = $array_plugins['plugins'];
                    }

                    $out_themes     = osc_file_get_contents(osc_market_featured_url('themes', 6) );
                    $array_themes   = json_decode($out_themes, true);
                    if(isset($array_themes)) {
                        $aThemes    = $array_themes['themes'];
                    }

                    $out_languages   = osc_file_get_contents(osc_market_featured_url('languages', 6) );
                    $array_languages = json_decode($out_languages, true);
                    if(isset($array_languages)) {
                        $aLanguages  = $array_languages['languages'];
                    }

                    $count = json_decode( osc_file_get_contents(osc_market_count_url()), true);
                    if( !isset($count['pluginsTotal']) ) {
                        $count['pluginsTotal'] = 0;
                    }
                    if( !isset($count['themesTotal']) ) {
                        $count['themesTotal'] = 0;
                    }
                    if( !isset($count['languagesTotal']) ) {
                        $count['languagesTotal'] = 0;
                    }

                    $this->_exportVariableToView("count"        , $count);
                    $this->_exportVariableToView("aPlugins"     , $aPlugins);
                    $this->_exportVariableToView("aThemes"      , $aThemes);
                    $this->_exportVariableToView("aLanguages"   , $aLanguages);

                    $this->doView("market/index.php");
                break;
            }
        }

        //hopefully generic...
        function doView($file)
        {
            osc_run_hook("before_admin_html");
            osc_current_admin_theme_path($file);
            Session::newInstance()->_clearVariables();
            osc_run_hook("after_admin_html");
        }
    }

    /* file end: ./oc-admin/market.php */
?>