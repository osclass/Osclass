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


class CategoryStats extends DAO {

	public static function newInstance() { return new CategoryStats(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_category_stats'; }

	public function increaseNumItems($categoryId) {
		$this->conn->osc_dbExec('INSERT INTO %s (fk_i_category_id, i_num_items) VALUES (%d, 1) ON DUPLICATE KEY UPDATE i_num_items = i_num_items + 1', $this->getTableName(), $categoryId);
		$result = Category::newInstance()->findByPrimaryKey($categoryId);
		if($result['fk_i_parent_id']!=NULL) {
			$this->increaseNumItems($result['fk_i_parent_id']);
		}
	}

	public function decreaseNumItems($categoryId) {
		$this->conn->osc_dbExec('INSERT INTO %s (fk_i_category_id, i_num_items) VALUES (%d, 0) ON DUPLICATE KEY UPDATE i_num_items = i_num_items - 1', $this->getTableName(), $categoryId);
		$result = Category::newInstance()->findByPrimaryKey($categoryId);
		if($result['fk_i_parent_id']!=NULL) {
			$this->decreaseNumItems($result['fk_i_parent_id']);
		}
	}

	public function findByCategoryId($categoryId) {
		return $this->conn->osc_dbFetchResult('SELECT * FROM %s WHERE fk_i_category_id = ', $this->getTableName(), $categoryId);
	}

	public function countItemsFromCategory($categoryId) {
		$data = $this->conn->osc_dbFetchResult('SELECT i_num_items FROM %s WHERE fk_i_category_id = %d', $this->getTableName(), $categoryId);
		if($data==null) { return 0; } else { return $data['i_num_items']; };
	}

	function getNumItems($cat) {
		static $numItemsMap = null;
		if(is_null($numItemsMap)) {
			$numItemsMap = $this->toNumItemsMap();
		}
		if(isset($numItemsMap[$cat['pk_i_id']]))
			return $numItemsMap[$cat['pk_i_id']];
		else
			return 0;
	}	

	public function toNumItemsMap() {
		$map = array();
		$all = $this->listAll();
		foreach($all as $a)
			$map[$a['fk_i_category_id']] = $a['i_num_items'];

		return $map;
	}
}

