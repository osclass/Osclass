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

    class Breadcrumb
    {
        private $location;
        private $section;
        private $title;
        protected $aLevel;

        public function __construct($lang = array())
        {
            $this->location = Rewrite::newInstance()->get_location();
            $this->section  = Rewrite::newInstance()->get_section();
            $this->aLevel   = array();
            $this->setTitles($lang);
        }

        public function init()
        {
            if( in_array($this->getLocation(), array('item', 'page', 'search', 'login', 'register', 'user', 'contact', 'custom')) ) {
                $l = array(
                    'url'   => osc_base_url(),
                    'title' => osc_page_title()
                );
                $this->addLevel($l);
            }

            switch($this->getLocation()) {
                case('item'):
                    if( $this->getSection() == 'item_add' ) {
                        $l = array('title' => $this->title['item_add']);
                        $this->addLevel($l);
                        break;
                    }

                    $aCategory = osc_get_category('id', osc_item_category_id());
                    // remove
                    View::newInstance()->_erase('categories');
                    View::newInstance()->_erase('subcategories');
                    View::newInstance()->_exportVariableToView('category', $aCategory);

                    $l = array(
                        'url'   => osc_search_category_url(),
                        'title' => osc_category_name()
                    );
                    $this->addLevel($l);

                    switch ($this->getSection()) {
                        case('item_edit'):
                            $l = array('url' => osc_item_url(), 'title' => osc_item_title());
                            $this->addLevel($l);
                            $l = array('title' => $this->title['item_edit']);
                            $this->addLevel($l);
                        break;
                        case('send_friend'):
                            $l = array('url' => osc_item_url(), 'title' => osc_item_title());
                            $this->addLevel($l);
                            $l = array('title' => $this->title['item_send_friend']);
                            $this->addLevel($l);
                        break;
                        case('contact'):
                            $l = array('url' => osc_item_url(), 'title' => osc_item_title());
                            $this->addLevel($l);
                            $l = array('title' => $this->title['item_contact']);
                            $this->addLevel($l);
                        break;
                        case(''):
                            $l = array('title' => osc_item_title());
                            $this->addLevel($l);
                        break;
                        default:
                            $l = array('title' => Rewrite::newInstance()->get_title());
                            $this->addLevel($l);
                            break;
                    }
                break;
                case('search'):
                        $region     = osc_search_region();
                        $city       = osc_search_city();
                        $pattern    = osc_search_pattern();
                        $category   = osc_search_category_id();
                        $category   = ((count($category) == 1) ? $category[0] : '');

                        $b_show_all = ($pattern == '' && $category == '' && $region == '' && $city == '');
                        $b_category = ($category != '');
                        $b_pattern  = ($pattern != '');
                        $b_region   = ($region != '');
                        $b_city     = ($city != '');
                        $b_location = ($b_region || $b_city);

                        // show all
                        if( $b_show_all ) {
                            $l = array('title' => $this->title['search']);
                            $this->addLevel($l);
                            break; 
                        }

                        // category
                        if( $b_category ) {
                            $aCategories = Category::newInstance()->toRootTree($category);
                            foreach( $aCategories as $c ) {
                                View::newInstance()->_erase('categories');
                                View::newInstance()->_erase('subcategories');
                                View::newInstance()->_exportVariableToView('category', $c);

                                $l = array(
                                    'url'   => osc_search_category_url(),
                                    'title' => osc_category_name()
                                );
                                $this->addLevel($l);
                            }
                        }

                        // location
                        if( $b_location ) {
                            $params = array();
                            if( $b_category ) {
                                $params['sCategory'] = $category;
                            }

                            if( $b_city ) {
                                $aCity = City::newInstance()->findByName($city);
                                if( count($aCity) == 0 ) {
                                    $params['sCity'] = $city;
                                    $l = array(
                                        'url'   => osc_search_url($params),
                                        'title' => $city
                                    );
                                    $this->addLevel($l);
                                } else {
                                    $aRegion = Region::newInstance()->findByPrimaryKey($aCity['fk_i_region_id']);

                                    $params['sRegion'] = $aRegion['s_name'];
                                    $l = array(
                                        'url'   => osc_search_url($params),
                                        'title' => $aRegion['s_name']
                                    );
                                    $this->addLevel($l);

                                    $params['sCity'] = $aCity['s_name'];
                                    $l = array(
                                        'url'   => osc_search_url($params),
                                        'title' => $aCity['s_name']
                                    );
                                    $this->addLevel($l);
                                }
                            } else if( $b_region ) {
                                $params['sRegion'] = $region;
                                $l = array(
                                    'url'   => osc_search_url($params),
                                    'title' => $region
                                );
                                $this->addLevel($l);
                            }
                        }

                        // pattern
                        if( $b_pattern ) {
                            $l = array('title' => sprintf($this->title['search_pattern'], $pattern));
                            $this->addLevel($l);
                        }

                        // remove url from the last node
                        $nodes = $this->getaLevel();
                        if( $nodes > 0 ) {
                            if( array_key_exists('url', $nodes[count($nodes) - 1]) ) {
                                unset($nodes[count($nodes) - 1]['url']);
                            }
                        }
                        $this->setaLevel($nodes);
                break;
                case('user'):
                    // use dashboard without url if you're in the dashboards
                    if( $this->getSection() == 'dashboard' ) {
                        $l = array('title' => $this->title['user_dashboard']);
                        $this->addLevel($l);
                        break;
                    }

                    // use dashboard without url if you're in the dashboards
                    if( $this->getSection() == 'pub_profile' ) {
                        $l = array('title' => sprintf($this->title['user_dashboard_profile'], osc_user_name()));
                        $this->addLevel($l);
                        break;
                    }

                    $l = array(
                        'url'   => osc_user_dashboard_url(),
                        'title' => $this->title['user_account']
                    );
                    $this->addLevel($l);

                    switch($this->getSection()) {
                        case('items'):
                            $l = array('title' => $this->title['user_items']);
                            $this->addLevel($l);
                        break;
                        case('alerts'):
                            $l = array('title' => $this->title['user_alerts']);
                            $this->addLevel($l);
                        break;
                        case('profile'):
                            $l = array('title' => $this->title['user_profile']);
                            $this->addLevel($l);
                        break;
                        case('change_email'):
                            $l = array('title' => $this->title['user_change_email']);
                            $this->addLevel($l);
                        break;
                        case('change_password'):
                            $l = array('title' => $this->title['user_change_password']);
                            $this->addLevel($l);
                            break;
                        case('change_username'):
                            $l = array('title' => $this->title['user_change_username']);
                            $this->addLevel($l);
                            break;
                        default:
                            $l = array('title' => Rewrite::newInstance()->get_title());
                            $this->addLevel($l);
                            break;
                    }
                break;
                case('login'):
                    switch($this->getSection()) {
                        case('recover'):
                            $l = array('title' => $this->title['login_recover']);
                            $this->addLevel($l);
                        break;
                        case('forgot'):
                            $l = array('title' => $this->title['login_forgot']);
                            $this->addLevel($l);
                        break;
                        case(''):
                            $l = array('title' => $this->title['login']);
                            $this->addLevel($l);
                        break;
                    }
                break;
                case('register'):
                    $l = array('title' => $this->title['register']);
                    $this->addLevel($l);
                break;
                case('page'):
                    $l = array('title' => osc_static_page_title());
                    $this->addLevel($l);
                break;
                case('contact'):
                    $l = array('title' => $this->title['contact']);
                    $this->addLevel($l);
                break;
                case('custom'):
                    $l = array('title' => Rewrite::newInstance()->get_title());
                    $this->addLevel($l);
                break;
            }
        }

        public function render($separator = '&raquo;')
        {
            if( count($this->aLevel) == 0 ) {
                return '';
            }

            $node = array();
            for($i = 0; $i < count($this->aLevel); $i++) {
                $text = '<li ';
                // set a class style for first and last <li>
                if( $i == 0 ) {
                    $text .= 'class="first-child" ';
                }
                if( ($i == (count($this->aLevel) - 1)) && ($i != 0) ) {
                    $text .= 'class="last-child" ';
                }
                $text .='itemscope itemtype="http://data-vocabulary.org/Breadcrumb" >';
                // set separator
                if( $i > 0 ) {
                    $text .= ' ' . $separator . ' ';
                }
                // create span tag
                $title = '<span itemprop="title">' . $this->aLevel[$i]['title'] . '</span>';
                if( array_key_exists('url', $this->aLevel[$i]) ) {
                    $title = '<a href="' . osc_esc_html($this->aLevel[$i]['url']) . '" itemprop="url">' . $title . '</a>';
                }
                $node[] = $text . $title . '</li>' . PHP_EOL;
            }

            $result  = '<ul class="breadcrumb">' . PHP_EOL;
            $result .= implode(PHP_EOL, $node);
            $result .= '</ul>' . PHP_EOL;

            return $result;
        }

        /**
         * Set the texts for the breadcrumb
         * 
         * @since 3.1
         */
        public function setTitles($lang)
        {
            // default titles
            $this->title['item_add']               = __('Publish a listing');
            $this->title['item_edit']              = __('Edit your listing');
            $this->title['item_send_friend']       = __('Send to a friend');
            $this->title['item_contact']           = __('Contact publisher');
            $this->title['search']                 = __('Search results');
            $this->title['search_pattern']         = __('Search results: %s');
            $this->title['user_dashboard']         = __('Dashboard');
            $this->title['user_dashboard_profile'] = __("%s's profile");
            $this->title['user_account']           = __('Account');
            $this->title['user_items']             = __('My listings');
            $this->title['user_alerts']            = __('My alerts');
            $this->title['user_profile']           = __('Update my profile');
            $this->title['user_change_email']      = __('Change my email');
            $this->title['user_change_username']   = __('Change my username');
            $this->title['user_change_password']   = __('Change my password');
            $this->title['login']                  = __('Login');
            $this->title['login_recover']          = __('Recover your password');
            $this->title['login_forgot']           = __('Change your password');
            $this->title['register']               = __('Create a new account');
            $this->title['contact']                = __('Contact');


            if( !is_array($lang) ) {
                return;
            }

            foreach($lang as $k => $v) {
                if( array_key_exists($k, $this->title) ) {
                    $this->title[$k] = $v;
                }
            }
        }

        public function getaLevel()
        {
            return $this->aLevel;
        }

        public function setaLevel($aLevel)
        {
            $this->aLevel = $aLevel;
        }

        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function getLocation()
        {
            return $this->location;
        }

        public function setSection($section)
        {
            $this->section = $section;
        }

        public function getSection()
        {
            return $this->section;
        }

        public function addLevel($level) {
            if( !is_array($level) ) {
                return;
            }
            $this->aLevel[] = $level;
        }
    }

    /* file end: ./oc-includes/osclass/classes/Breadcrumb.php */
?>