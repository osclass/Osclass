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

    class ItemStats extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_item_stats'; }

        public function increase($column, $id) {
           $this->conn->osc_dbExec('INSERT INTO %s (fk_i_item_id, dt_date, %3$s) VALUES (%d, \'%4$s\',1) ON DUPLICATE KEY UPDATE %3$s = %3$s + 1', $this->getTableName(), $id, $column, date('Y-m-d H:i:s')) ;
        }

        public function emptyRow($id) {
           $this->conn->osc_dbExec('INSERT INTO %s (fk_i_item_id, dt_date) VALUES (%d, \'%s\')', $this->getTableName(), $id, date('Y-m-d H:i:s')) ;
        }
    }

?>