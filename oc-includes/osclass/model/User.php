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


class User extends DAO {

	public static function newInstance() { return new User(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_user'; }

	public function findByEmail($email) {
		$results = $this->listWhere("s_email = '%s'", $email);
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByCredentials($key, $password) {
		$results = $this->listWhere("s_email = '%s' AND s_password = '%s'", $key, sha1($password));
		if(count($results)==1) {
		    return $results[0];
		} else {
		    // For backwards-compatibility issues
    		$results = $this->listWhere("s_username = '%s' AND s_password = '%s'", $key, sha1($password));
    		if(count($results)==1) {
    		    return $results[0];
            } else {
                return null;
            }
		}
		return null;
	}

	public function findByUsername($userName) {
		$results = $this->listWhere("s_userName = '%s'", $userName);
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByIdSecret($id, $secret) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_secret = '%s'", $this->getTableName(), $id, $secret);
	}

	public function findByIdPasswordSecret($id, $secret) {
        if($secret=='') { return null; }
        $date = date("Y-m-d H:i:s", (time()-(24*3600)));//mktime(date('H'), date('i'), date('s'), date('m'), date('d')-1, date('Y')));
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_pass_code = '%s' AND s_pass_date >= '%s'", $this->getTableName(), $id, $secret, $date);
	}

	public function preferences($id) {
		$prefs = $this->conn->osc_dbFetchResults("SELECT * FROM %st_user_preferences WHERE fk_i_user_id = %d", DB_TABLE_PREFIX, $id);
        $preferences = array();
        foreach($prefs as $pref) {
            $preferences[$pref['s_name']] = $pref['s_value'];
        }
        return $preferences;
	}

	public function updatePreferences($options = array(), $id = null) {
        if($id!=null) {
            foreach($options as $k => $v) {
        		$this->conn->osc_dbExec("REPLACE INTO `%st_user_preferences` SET `s_value` = %s, `fk_i_user_id` = %d, `s_name` = '%s'", DB_TABLE_PREFIX, $v, $id, $k);
            }
        }
	}

}

