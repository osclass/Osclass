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




function osc_meta_publish($catId = null) {
    echo '<div class="row">';
        FieldForm::meta_fields_input($catId);
    echo '</div>';
}

function osc_meta_edit($catId = null, $item_id = null) {
    echo '<div class="row">';
        FieldForm::meta_fields_input($catId, $item_id);
    echo '</div>';
}

osc_add_hook('item_form', 'osc_meta_publish');
osc_add_hook('item_edit', 'osc_meta_edit');


?>