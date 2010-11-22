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

	public function findByCredentials($userName, $password) {
		$results = $this->listWhere("s_userName = '%s' AND s_password = '%s'", $userName, sha1($password));
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByUsername($userName) {
		$results = $this->listWhere("s_userName = '%s'", $userName);
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByIdSecret($id, $secret) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_secret = '%s'", $this->getTableName(), $id, $secret);
	}
}

