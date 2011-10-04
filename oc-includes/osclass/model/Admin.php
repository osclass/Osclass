<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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

    class Admin extends DAO {
        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_admin'; }

        public function findByEmail($email) {
            $results = $this->listWhere("s_email = '%s'", $email);
            return count($results) == 1 ? $results[0] : null;
        }

        public function findByUsername($username) {
            $results = $this->listWhere("s_username = '%s'", $username);
            return count($results) == 1 ? $results[0] : null;
        }

        public function findByCredentials($userName, $password) {
            $results = $this->listWhere("s_username = '%s' AND s_password = '%s'", $userName, sha1($password));
            return count($results) == 1 ? $results[0] : null;
        }

        public function findByIdSecret($id, $secret) {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_secret = '%s'",
                $this->getTableName(), $id, $secret);
        }

        public function updateArray($admin) {
            $admin['name'] = addslashes($admin['name']);
            $admin['userName'] = addslashes($admin['userName']);
            $admin['email'] = addslashes($admin['email']);
            $admin['password'] = addslashes($admin['password']);
            $this->conn->osc_dbExec("UPDATE %s SET s_name = '%s', s_username = '%s', s_email = '%s', s_password = '%s' WHERE pk_i_id = %d", $this->getTableName(),
                $admin['name'], $admin['userName'], $admin['email'], $admin['password'], $admin['id']);
        }
    }

?>