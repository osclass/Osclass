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


class City extends DAO {

	public static function newInstance() { return new City(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_city'; }

    public function ajax($query) {
        return $this->conn->osc_dbFetchResults("SELECT pk_i_id as id, s_name as label, s_name as value FROM %s WHERE `s_name` LIKE '%s%%' LIMIT 5", $this->getTableName(), $query);
    }

    public function getByRegion($region_id) {
        return $this->conn->osc_dbFetchResults("SELECT * FROM %s WHERE fk_i_region_id = %d", $this->getTableName(), $region_id);
    }


}

