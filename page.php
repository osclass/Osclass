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

    class CWebPage extends BaseModel
    {
        var $pageManager ;

        function __construct() {
            parent::__construct() ;

            //specific things for this class
            $this->pageManager = Page::newInstance() ;
        }

        //Business Layer...
        function doModel() {
            $id = Params::getParam('id') ;
            $page = $this->pageManager->findByPrimaryKey($id) ;

            if( $page == '' || $page['b_indelible'] == 1 ) {
                echo "404 ERROR!!!" ;
                exit() ;
            } else {
                $locale = Session::newInstance()->_get('locale') ;

                if(isset($page['locale'][$locale])) {
                    $title = $page['locale'][$locale]['s_title'] ;
                    $text = $page['locale'][$locale]['s_text'] ;
                } else {
                    $data = current($page['locale']) ;
                    $title = $data['s_title'] ;
                    $text = $data['s_text'] ;
                    unset($data) ;
                }

                //calling the view...
                $this->_exportVariableToView('title', $title) ;
                $this->_exportVariableToView('text', $text) ;

                $this->doView('page.php') ;
            }
        }

        //hopefully generic...
        function doView($file) {
            osc_current_web_theme_path($file) ;
        }
    }

?>