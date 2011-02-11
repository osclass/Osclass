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

class CWebMain extends BaseModel
{

    function __construct() {
        parent::__construct() ;

        $this->add_css('style.css') ;
        $this->add_css('jquery-ui.css') ;
        $this->add_global_js('tiny_mce.js') ;
        $this->add_global_js('jquery-1.4.2.js') ;
        $this->add_global_js('jquery-ui-1.8.5.js') ;
        $this->add_js('jquery-extends.js') ;
        $this->add_js('global.js') ;
    }

    //Business Layer...
    function doModel() {
        //recovering data needed at main.php
        $categories = Category::newInstance()->toTree();
        $locales = Locale::newInstance()->listAllEnabled() ;

        //calling the view...
        $this->_exportVariableToView('categories', $categories) ;
        $this->_exportVariableToView('locales', $locales) ;

        $this->doView('main.php') ;
    }

    //hopefully generic...
    function doView($file) {
        $this->osc_print_html($file) ;
    }
}

?>