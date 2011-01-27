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


/**
 * This function checks if the string passed by parameter is a valid email or not.
 * @param string $email
 * @return true if it is a valid email, false otherwise.
 */
function osc_isValidEmail($email) {
	return strpos($email, '@') !== false;
}

function osc_check_form_js($fields = null) {
    if(is_array($fields)) {
        echo "<script type=\"text/javascript\">\n";
        echo "function checkForm() {\n";
        foreach($fields as $field) {
            echo "if(document.getElementById('".$field['name']."').value == \"\") {\n";
                echo "alert(\"".$field['error_msg']."\");\n";
                echo "return false;\n";
            echo "}\n";
        }
        
        echo "return true;\n";
        echo "}\n";
        echo "</script>\n";
    }
}

?>
