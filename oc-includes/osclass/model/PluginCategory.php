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

class PluginCategory extends DAO {

	private static $instance ;

	public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

	public function getTableName() { return DB_TABLE_PREFIX . 't_plugin_category'; }

	public function findByCategoryId($catId) {
		return $this->listWhere('fk_i_category_id = ' . $catId);
	}

	public function listSelected($plugin) {
		$selected =  $this->listWhere( 's_plugin_name LIKE \'' . $plugin . '\'' );
        $list = array();
        foreach($selected as $sel) {
            $list[] = $sel['fk_i_category_id'];
        }
        return $list;
	}

	public function isThisCategory($catName, $catId) {
		$var = $this->listWhereCount('`s_plugin_name` LIKE \'' . $catName . '\' AND fk_i_category_id = ' . $catId);
		if(!isset($var[0]) || $var[0]['count']==0) {
			return false;
		}
		return true;
	}
}

