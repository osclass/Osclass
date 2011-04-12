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

	private static $instance ;

	public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function getTableName() { return DB_TABLE_PREFIX . 't_alerts'; }

    public function getAlertsFromUser($user) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE fk_i_user_id = %d', DB_TABLE_PREFIX, $user);
    }

    public function getAlertsFromEmail($email) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE s_email LIKE \'%s\'', DB_TABLE_PREFIX, $email);
    }

    public function getAlertsByType($type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type =\'%s\'', DB_TABLE_PREFIX, $type);
    }

    public function getAlertsByTypeGroup($type, $active = FALSE) {
        if($active) {
            return $this->conn->osc_dbFetchResults('SELECT s_search,s_secret FROM %st_alerts WHERE e_type =\'%s\' AND b_active = 1 GROUP BY s_search', DB_TABLE_PREFIX, $type);
        }else{
            return $this->conn->osc_dbFetchResults('SELECT s_search,s_secret FROM %st_alerts WHERE e_type =\'%s\' GROUP BY s_search', DB_TABLE_PREFIX, $type);
        }
    }

    public function getAlertsBySearchAndType($search, $type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type =\'%s\' AND s_search LIKE \'%s\'', DB_TABLE_PREFIX, $type, addslashes($search));
    }

    public function getUsersBySearchAndType($search, $type, $active = FALSE) {
        if($active) {
            return $this->conn->osc_dbFetchResults('SELECT a.s_email, a.fk_i_user_id FROM %st_alerts as a WHERE a.e_type =\'%s\' AND b_active = 1 AND a.s_search LIKE \'%s\' ', DB_TABLE_PREFIX, $type, addslashes($search));
        }else{
            return $this->conn->osc_dbFetchResults('SELECT a.s_email, a.fk_i_user_id FROM %st_alerts as a WHERE a.e_type =\'%s\' AND a.s_search LIKE \'%s\' ', DB_TABLE_PREFIX, $type, addslashes($search));
        }
    }

    public function getAlertsFromUserByType($user, $type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type = \'%s\' AND fk_i_user_id = %d', DB_TABLE_PREFIX, $type, $user);
    }

    public function getAlertsFromEmailByType($email, $type) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE e_type = \'%s\' AND s_email LIKE \'%s\'', DB_TABLE_PREFIX, $type, $email);
    }
    
    public function createAlert($userid = null, $email, $alert, $secret, $type = 'DAILY') {
        if($userid == null){
            $results = $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE s_search LIKE \'%s\' AND fk_i_user_id = 0 AND s_email LIKE \'%s\'', DB_TABLE_PREFIX, $alert, $email);
        } else {
            $results = $this->conn->osc_dbFetchResults('SELECT * FROM %st_alerts WHERE s_search LIKE \'%s\' AND fk_i_user_id = %s', DB_TABLE_PREFIX, $alert, $userid);
        }
        if(count($results)==0) {
            $this->insert(array( 'fk_i_user_id' => $userid, 's_email' => $email, 's_search' => $alert, 'e_type' => $type, 's_secret' => $secret));
            return true;
        }
        return false;
    }

    public function activate( $email, $secret) {
        $this->conn->osc_dbExec('UPDATE %st_alerts SET b_active = 1 WHERE s_email = \'%s\' AND s_secret = \'%s\'', DB_TABLE_PREFIX, $email, $secret);
        return $this->conn->get_affected_rows();
    }
}

