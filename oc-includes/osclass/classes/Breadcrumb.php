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

    class Breadcrumb
    {
        private $location;
        private $section;
        protected $aLevel;

        public function __construct()
        {
            $this->location = Rewrite::newInstance()->get_location();
            $this->section  = Rewrite::newInstance()->get_section();
            $this->aLevel   = array();
        }

        public function init()
        {
            if( in_array($this->getLocation(), array('item', 'page', 'search', 'login', 'register', 'user', 'contact')) ) {
                $l = array(
                    'url'   => osc_base_url(),
                    'title' => osc_page_title()
                );
                $this->addLevel($l);
            }

            switch($this->getLocation()) {
                case('item'):
                    if( $this->getSection() == 'item_add' ) {
                        $l = array('title' => __('Publish a listing'));
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
                            $l = array('title' => __('Edit your listing'));
                            $this->addLevel($l);
                        break;
                        case('send_friend'):
                            $l = array('url' => osc_item_url(), 'title' => osc_item_title());
                            $this->addLevel($l);
                            $l = array('title' => __('Send to a friend'));
                            $this->addLevel($l);
                        break;
                        case('contact'):
                            $l = array('url' => osc_item_url(), 'title' => osc_item_title());
                            $this->addLevel($l);
                            $l = array('title' => __('Contact publisher'));
                            $this->addLevel($l);
                        break;
                        case(''):
                            $l = array('title' => osc_item_title());
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
                            $l = array('title' => __('Search results'));
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
                                if( count($aCity) != 0 ) {
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
                            $l = array('title' => sprintf(__('Search results: %s'), $pattern));
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
                        $l = array('title' => __('Dashboard'));
                        $this->addLevel($l);
                        break;
                    }

                    // use dashboard without url if you're in the dashboards
                    if( $this->getSection() == 'dashboard' ) {
                        $l = array('title' => sprintf(__("%s's profile"), osc_user_name()));
                        $this->addLevel($l);
                        break;
                    }

                    $l = array(
                        'url'   => osc_user_dashboard_url(),
                        'title' => __('Account')
                    );
                    $this->addLevel($l);

                    switch($this->getSection()) {
                        case('items'):
                            $l = array('title' => __('My listings'));
                            $this->addLevel($l);
                        break;
                        case('alerts'):
                            $l = array('title' => __('My alerts'));
                            $this->addLevel($l);
                        break;
                        case('profile'):
                            $l = array('title' => __('My profile'));
                            $this->addLevel($l);
                        break;
                        case('change_email'):
                            $l = array('title' => __('Change my email'));
                            $this->addLevel($l);
                        break;
                        case('change_password'):
                            $l = array('title' => __('Change my password'));
                            $this->addLevel($l);
                        break;
                    }
                break;
                case('login'):
                    switch($this->getSection()) {
                        case('recover'):
                            $l = array('title' => __('Recover your password'));
                            $this->addLevel($l);
                        break;
                        case('forgot'):
                            $l = array('title' => __('Change your password'));
                            $this->addLevel($l);
                        break;
                        case(''):
                            $l = array('title' => __('Login'));
                            $this->addLevel($l);
                        break;
                    }
                break;
                case('register'):
                    $l = array('title' => __('Create a new account'));
                    $this->addLevel($l);
                break;
                case('page'):
                    $l = array('title' => osc_static_page_title());
                    $this->addLevel($l);
                break;
                case('contact'):
                    $l = array('title' => __('Contact'));
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
                    $title = '<a href="' . $this->aLevel[$i]['url'] . '" itemprop="url">' . $title . '</a>';
                }
                $node[] = $text . $title . '</li>' . PHP_EOL;
            }

            $result  = '<ul class="breadcrumb">' . PHP_EOL;
            $result .= implode(PHP_EOL, $node);
            $result .= '</ul>' . PHP_EOL;

            return $result;
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
                return ;
            }
            $this->aLevel[] = $level;
        }
    }

    /* file end: ./oc-includes/osclass/classes/Breadcrumb.php */
?>