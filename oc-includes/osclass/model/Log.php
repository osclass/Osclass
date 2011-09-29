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


class Log extends DAO {

	private static $instance ;

	public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }
    
	public function getTableName() { return DB_TABLE_PREFIX . 't_log' ; }
    
    public function insertLog($section, $action, $id, $data, $who, $who_id) {
        $sql = 'INSERT INTO ' . $this->getTableName() . ' (dt_date, s_section, s_action, fk_i_id, s_data, s_ip, s_who, fk_i_who_id)';
        $sql .= ' VALUES (\''.date('Y-m-d H:i:s').'\', \'' . $section . '\', \'' . $action . '\', \'' . $id . '\', \'' . $data . '\', \'' . $_SERVER['REMOTE_ADDR'] . '\', \'' . $who . '\', \'' . $who_id . '\' )';
        return $this->conn->osc_dBExec($sql);
    }

}

