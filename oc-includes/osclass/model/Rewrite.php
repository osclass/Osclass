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


class Rewrite {

    private $rules;
    private $conn;

    public function __construct() {
        $this->conn = getConnection();
        $this->rules = $this->getRules();
    }

    public static function newInstance() { return new Rewrite; }

    public function getRules() {
        global $preferences;
        return osc_unserialize($preferences['rewrite_rules']);
    }

    public function setRules() {
        global $preferences;
        Preference::newInstance()->update(
                array('s_value' => osc_serialize($this->rules)),
                array('s_name' => 'rewrite_rules')
        );
    }

    public function listRules() {
        return $this->rules;
    }

    public function addRules($rules) {
        if(is_array($rules)) {
            foreach($rules as $rule) {
                if(is_array($rule) && count($rule)>1) {
                    $this->addRule($rule[0], $rule[1]);
                }
            }
        }
    }

    public function addRule($regexp, $uri) {
        $regexp = trim($regexp);
        $uri = trim($uri);
        if($regexp!='' && $uri!='') {
            if(!in_array($regexp, $this->rules)) {
                $this->rules[$regexp] = $uri;
            }
        }
    }

    public function doRedirect() {

        $redirected = null;
        if(isset($_SERVER['REQUEST_URI'])) {
            //$rules = Permalink::newInstance()->getRules();
            $request_uri = str_replace(REL_WEB_URL, "", $_SERVER['REQUEST_URI']);
            foreach($this->rules as $match => $uri) {
                if(preg_match('#'.$match.'#', $request_uri, $m)) {
                    $uri = preg_replace('#'.$match.'#', $uri, $request_uri);
                    $this->extractParams($uri);
                    $redirected = $this->extractURL($uri);
                    break;
                }
            }
        }
        return $redirected;
    }

    public function extractURL($uri = '') {
        $uri_array = explode('?', $uri);
        if(substr($uri_array[0], 0, 1)=="/") {
            return substr($uri_array[0], 1);
        } else {
            return $uri_array[0];
        }
    }

    public function extractParams($uri = '') {
        $uri_array = explode('?', $uri);
        $url = substr($uri_array[0], 1);
        $length_i = count($uri_array);
        for($var_i = 1;$var_i<$length_i;$var_i++) {
            if(preg_match_all('|&([^=]+)=([^&]*)|', '&'.$uri_array[$var_i].'&', $matches)) {
                $length = count($matches[1]);
                for($var_k = 0;$var_k<$length;$var_k++) {
                    $_GET[$matches[1][$var_k]] = $matches[2][$var_k];
                    $_REQUEST[$matches[1][$var_k]] = $matches[2][$var_k];
                }
            }
        }
    }

    public function removeRule($regexp) {
        unset($this->rules[$regexp]);
    }

    public function clearRules() {
        unset($this->rules);
        $this->rules = array();
    }



}

