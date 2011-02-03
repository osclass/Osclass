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

class Preference extends DAO
{
    private static $instance ;
    private $pref ;

	public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function __construct() {
        parent::__construct();
        $this->pref = $this->toArray() ;
    }

	public function getTableName() {
        return DB_TABLE_PREFIX . 't_preference';
    }

	public function findValueByName($name) {
		return $this->conn->osc_dbFetchValue("SELECT s_value FROM %s WHERE s_name = '%s'", $this->getTableName(), $name);
	}

	public function findBySection($name) {
		return $this->conn->osc_dbFetchResults("SELECT * FROM %s WHERE s_section = '%s'", $this->getTableName(), $name);
	}

	public function toArray() {
		$aTmpPref = $this->listAll() ;

        foreach($aTmpPref as $tmpPref) {
			$this->pref[$tmpPref['s_section']][$tmpPref['s_name']] = $tmpPref['s_value'] ;
		}
	}

    public function get($key, $section = "osclass") {
        if (!isset($this->pref[$section][$key])) {
            return '' ;
        }
        return ($this->pref[$section][$key]) ;
    }

    public function set($key, $value, $section = "osclass") {
        $this->pref[$section][$key] = $value ;
    }
}

