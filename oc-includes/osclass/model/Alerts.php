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


class Alerts extends DAO {

	public static function newInstance() { return new Alerts(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_alerts'; }

    public function getAlertsFromUser($user) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE fk_i_user_id = %d', DB_TABLE_PREFIX, $user);
    }

    public function getAlertsByType($type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type =\'%s\'', DB_TABLE_PREFIX, $type);
    }

    public function getAlertsByTypeGroup($type) {
        return $this->conn->osc_dbFetchResults('SELECT s_search FROM %st_alerts WHERE e_type =\'%s\' GROUP BY s_search', DB_TABLE_PREFIX, $type);
    }

    public function getAlertsBySearchAndType($search, $type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type =\'%s\' AND s_search LIKE \'%s\'', DB_TABLE_PREFIX, $type, addslashes($search));
    }

    public function getUsersBySearchAndType($search, $type) {
        return $this->conn->osc_dbFetchResults('SELECT u.s_name, u.s_email FROM %st_alerts as a, %st_user as u WHERE a.e_type =\'%s\' AND a.s_search LIKE \'%s\' AND u.pk_i_id = a.fk_i_user_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, $type, addslashes($search));
    }

    public function getAlertsFromUserByType($user, $type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type = \'%s\' AND fk_i_user_id = %d', DB_TABLE_PREFIX, $type, $user);
    }


}

