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


class Preference extends DAO {

	public static function newInstance() { return new Preference(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_preference'; }

	public function findValueByName($name) {
		return $this->conn->osc_dbFetchValue("SELECT s_value FROM %s WHERE s_name = '%s'", $this->getTableName(), $name);
	}

	public function findValueBySection($name) {
		return $this->conn->osc_dbFetchValue("SELECT s_name, s_value FROM %s WHERE s_section = '%s'", $this->getTableName(), $name);
	}

	public function toArray($section = null) {
		$array = array();
		if($section==null) {
    		$preferences = $this->listAll();
        } else {
    		$preferences = $this->findBySection($section);
        }
		foreach($preferences as $p) {
			$array[$p['s_name']] = $p['s_value'];
		}
		return $array;
	}
	
}

function osc_comments_enabled($preferences = null) {
    if(!$preferences) {
        global $preferences;
    }

    if(!isset($preferences['enabled_comments']))
        return true;

    if($preferences['enabled_comments']) {
        return true;
    } else {
        return false;
    }
}
