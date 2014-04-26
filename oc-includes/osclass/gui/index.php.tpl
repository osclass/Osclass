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

/*
Theme Name: bender
Theme URI: http://osclass.org/
Description: <%- pkg.description %>
Version: <%- pkg.version %>
Author: <%- pkg.author %>
Author URI: http://osclass.org/
Widgets:
Theme update URI:
*/

    function bender_theme_info() {
        return array(
             'name'        => 'bender'
            ,'version'     => '<%- pkg.version %>'
            ,'description' => '<%- pkg.description %>'
            ,'author_name' => '<%- pkg.author %>'
            ,'author_url'  => 'http://osclass.org'
            ,'locations'   => array()
        );
    }

?>