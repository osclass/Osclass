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

class Item extends DAO {

    public static function newInstance() {
        return new Item();
    }

    public function getTableName() {
        return DB_TABLE_PREFIX . 't_item';
    }

    public function extendCategoryName($items) {

        if (isset($_SESSION['locale'])) {
            $prefLocale = $_SESSION['locale'];
        } else {
            $prefLocale = Preference::newInstance()->findValueByName('language');
        }

        $results = array();
        foreach ($items as $item) {
            $descriptions = $this->conn->osc_dbFetchResults('SELECT fk_c_locale_code, s_name as s_category_name FROM %st_category_description WHERE fk_i_category_id = %d', DB_TABLE_PREFIX, $item['fk_i_category_id']);
            foreach ($descriptions as $desc) {
                $item['locale'][$desc['fk_c_locale_code']]['s_category_name'] = $desc['s_category_name'];
            }
            if (isset($item['locale'][$prefLocale])) {
                $item['s_category_name'] = $item['locale'][$prefLocale]['s_category_name'];
            } else {
                $data = current($item['locale']);
                $item['s_category_name'] = $data['s_category_name'];
                unset($data);
            }
            $results[] = $item;
        }
        return $results;
    }

    public function extendData($items) {

        if (isset($_SESSION['locale'])) {
            $prefLocale = $_SESSION['locale'];
        } else {
            $prefLocale = Preference::newInstance()->findValueByName('language');
        }

        $results = array();
        foreach ($items as $item) {
            $descriptions = $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
            $item['locale'] = array();
            foreach ($descriptions as $desc) {
                if ($desc['s_title'] != "" || $desc['s_description'] != "") {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
            }
            if (isset($item['locale'][$prefLocale])) {
                $item['s_title'] = $item['locale'][$prefLocale]['s_title'];
                $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
                $item['s_what'] = $item['locale'][$prefLocale]['s_what'];
            } else {
                $data = current($item['locale']);
                $item['s_title'] = $data['s_title'];
                $item['s_description'] = $data['s_description'];
                $item['s_what'] = $data['s_what'];
                unset($data);
            }
            $results[] = $item;
        }
        return $results;
    }

    public function extendDataSingle($item) {

        if (isset($_SESSION['locale'])) {
            $prefLocale = $_SESSION['locale'];
        } else {
            $prefLocale = Preference::newInstance()->findValueByName('language');
        }

        $descriptions = $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
        $item['locale'] = array();
        foreach ($descriptions as $desc) {
            if ($desc['s_title'] != "" || $desc['s_description'] != "") {
                $item['locale'][$desc['fk_c_locale_code']] = $desc;
            }
        }
        if (isset($item['locale'][$prefLocale])) {
            $item['s_title'] = $item['locale'][$prefLocale]['s_title'];
            $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
            $item['s_what'] = $item['locale'][$prefLocale]['s_what'];
        } else {
            $data = current($item['locale']);
            $item['s_title'] = $data['s_title'];
            $item['s_description'] = $data['s_description'];
            $item['s_what'] = $data['s_what'];
            unset($data);
        }
        return $item;
    }

    public function listWhere() {
        $argv = func_get_args();
        $sql = null;
        switch (func_num_args ()) {
            case 0: return array();
                break;
            case 1: $sql = $argv[0];
                break;
            default:
                $args = func_get_args();
                $format = array_shift($args);
                $sql = vsprintf($format, $args);
                break;
        }
        $items = $this->conn->osc_dbFetchResults('SELECT l.*, i.* FROM %s i, %st_item_location l WHERE l.fk_i_item_id = i.pk_i_id AND %s', $this->getTableName(), DB_TABLE_PREFIX, $sql);
        return $this->extendData($items);
    }

    public function findByPrimaryKey($id) {
        $item = $this->conn->osc_dbFetchResult('SELECT l.*, i.* FROM %s i JOIN %st_item_location l ON l.fk_i_item_id = i.pk_i_id WHERE i.pk_i_id = %d', $this->getTableName(), DB_TABLE_PREFIX, $id);

        return $this->extendDataSingle($item);
    }

    public function findResourcesByID($id) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
    }

    public function findLocationByID($id) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_location WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
    }

    public function findByCategoryID($catId) {
        return $this->listWhere('fk_i_category_id = %d', $catId);
    }

    public function list_items($category = null, $start = 0, $limit = 10, $active = null, $order_by = null, $search = null) {
        $sql = sprintf('SELECT SQL_CALC_FOUND_ROWS i.* FROM %st_item i, %st_category c WHERE c.pk_i_id = i.fk_i_category_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX);

        $conditions = array();
        if (!is_null($category)) {
            $conditions[] = '(c.pk_i_id = ' . $category['pk_i_id'] . ' OR c.fk_i_parent_id = ' . $category['pk_i_id'] . ')';
            if ($category['i_expiration_days'] > 0) {
                $conditions[] = 'DATE_SUB(CURDATE(),INTERVAL ' . $category['i_expiration_days'] . ' DAY) <= i.dt_pub_date';
            }
        }

        if (!is_null($active)) {
            if (($active == 'ACTIVE') ||  ($active == 'INACTIVE') ||  ($active == 'SPAM')) {
                $conditions[] = "e_status = '$active'";
            }
        }

        if (count($conditions) > 0) {
            $sql .= ' AND ';
            for ($i = 0; $i < count($conditions); $i++) {
                $sql .= $conditions[$i];
                if ($i < (count($conditions) - 1)) {
                    $sql .= ' AND ';
                }
            }
        }

        if ($search) {
            //$sql .= " AND ";
            //$sql .= "i.s_title LIKE '%$search%' OR i.s_description LIKE '%$search%'";
        }

        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by['column_name'] . ' ' . $order_by['type'];
        } else {
            $sql .= ' ORDER BY i.dt_pub_date DESC';
        }

        if ($start == 0 && $limit == 0) {
            $sql .= '';
        } else {
            $sql .= ' LIMIT ' . $start . ',' . $limit;
        }
        $aItems = $this->conn->osc_dbFetchResults($sql);
        $found = $this->found_rows();
        $items = $this->extendData($aItems);
        $items = $this->extendCategoryName($items);

        return array('found' => $found, 'items' => $items);
    }

    public function list_items_conditions($category = null, $start = 0, $limit = 10, $conditions = array(), $active = null, $order_by = null, $search = null) {
        $sql = sprintf('SELECT SQL_CALC_FOUND_ROWS i.* FROM %st_item i, %st_category c WHERE AND c.pk_i_id = i.fk_i_category_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX);

        //$conditions = array();
        if (!is_null($category)) {
            $conditions[] = '(c.pk_i_id = ' . $category['pk_i_id'] . ' OR c.fk_i_parent_id = ' . $category['pk_i_id'] . ')';
            if ($category['i_expiration_days'] > 0) {
                $conditions[] = 'DATE_SUB(CURDATE(),INTERVAL ' . $category['i_expiration_days'] . ' DAY) <= i.dt_pub_date';
            }
        }

        if (!is_null($active)) {
            if (($active == 'ACTIVE') ||  ($active == 'INACTIVE') ||  ($active == 'SPAM')) {
                $conditions[] = "e_status = '$active'";
            }
        }

        if (count($conditions) > 0) {
            $sql .= ' AND ';
            for ($i = 0; $i < count($conditions); $i++) {
                $sql .= $conditions[$i];
                if ($i < (count($conditions) - 1)) {
                    $sql .= ' AND ';
                }
            }
        }

        if ($search) {
            //$sql .= " AND ";
            //$sql .= "i.s_title LIKE '%$search%' OR i.s_description LIKE '%$search%'";
        }

        if ($order_by) {
            $sql .= ' ORDER BY ' . $order_by['column_name'] . ' ' . $order_by['type'];
        } else {
            $sql .= ' ORDER BY i.dt_pub_date DESC';
        }

        if ($start == 0 && $limit == 0) {
            $sql .= '';
        } else {
            $sql .= ' LIMIT ' . $start . ',' . $limit;
        }

        $aItems = $this->conn->osc_dbFetchResults($sql);
        $found = $this->found_rows();
        $items = $this->extendData($aItems);
        $items = $this->extendCategoryName($items);

        return array('found' => $found, 'items' => $items);
    }

    public function list_premiums($category = null, $start = 0, $limit = 2, $active = null, $order_by = null, $search = null) {
        $condtions = array();
        $conditions[] = ' b_premium = 1 ';
        return list_items_conditions($category, $start, $limit, $conditions, $active, $order_by, $search);
    }

    public function list_no_premiums($category = null, $start = 0, $limit = 10, $active = null, $order_by = null, $search = null) {
        $condtions = array();
        $conditions[] = ' b_premium = 0 ';
        return list_items_conditions($category, $start, $limit, $conditions, $active, $order_by, $search);
    }

    public function list_items_with_premiums($category = null, $start = 0, $limit = 10, $premium_start = 0, $premium_limit = 2, $active = null, $order_by = null, $search = null) {
        $premiums = list_premiums($category, $premium_start, $premium_limit, $active, $order_by, $search);
    }

    public function found_rows() {
        $sql = "SELECT FOUND_ROWS() as total";
        $total_ads = $this->conn->osc_dbFetchResult($sql);
        return $total_ads['total'];
    }

    public function total_items($category = null, $active = null) {
        $sql = sprintf('SELECT count(*) AS total FROM %st_item i JOIN
                %st_category c ON c.pk_i_id = i.fk_i_category_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX);

        $conditions = array();
        if (!is_null($active)) {
            if (($active == 'ACTIVE') ||  ($active == 'INACTIVE') ||  ($active == 'SPAM')) {
                $conditions[] = "e_status = '$active'";
            }
        }

        if (count($conditions) > 0) {
            $sql .= ' WHERE ';
            for ($i = 0; $i < count($conditions); $i++) {
                $sql .= $conditions[$i];
                if ($i < (count($conditions) - 1)) {
                    $sql .= ' AND ';
                }
            }
        }

        $total_ads = $this->conn->osc_dbFetchResult($sql);
        return $total_ads['total'];
    }

    public function listLatest($limit = 10) {
        return $this->listWhere(" e_status = 'ACTIVE' ORDER BY dt_pub_date DESC LIMIT " . $limit);
    }

    public function insertLocale($id, $locale, $title, $description, $what) {
        $sql = sprintf("INSERT INTO %st_item_description (`fk_i_item_id`, `fk_c_locale_code`, `s_title`, `s_description`, `s_what`) VALUES ('%s', '%s', '%s', '%s', '%s')", DB_TABLE_PREFIX, $id, $locale, $title, $description, $what) ;
        return $this->conn->osc_dbExec($sql);
    }

    public function listLatestExtended($limit = 10) {
        return $this->conn->osc_dbFetchResults('SELECT * FROM %s, %st_item_location WHERE %st_item.e_status = \'%s\' AND %st_item_location.fk_i_item_id = %st_item.pk_i_id  ORDER BY %st_item.dt_pub_date DESC LIMIT %d', $this->getTableName(), DB_TABLE_PREFIX, DB_TABLE_PREFIX, 'ACTIVE', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $limit) ;
    }

    public function listAllWithCategories() {
        return $this->conn->osc_dbFetchResults('SELECT i.*, cd.s_name AS s_category_name FROM %st_item i, %st_category c, %st_category_description cd WHERE c.pk_i_id = i.fk_i_category_id AND cd.fk_i_category_id = i.fk_i_category_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX) ;
    }

    public function search($pattern) {
        return $this->listWhere("s_title LIKE '%%%s%%' OR s_description LIKE '%%%1\$s%%'", $pattern);
    }

    public function findByUserID($userId, $limit = null) {
        if($limit==null) {
            $limit_text = '';
        } else {
            $limit_text = ' LIMIT '.$limit;
        }
        $items = $this->conn->osc_dbFetchResults('SELECT l.*, i.* FROM %s i, %st_item_location l WHERE l.fk_i_item_id = i.pk_i_id AND i.fk_i_user_id = %d ORDER BY i.pk_i_id ASC %s', $this->getTableName(), DB_TABLE_PREFIX, $userId, $limit_text);
        return $this->extendData($items);
        //return $this->listWhere('i.fk_i_user_id = %d', $userId);
    }

    /* findByItemStat()
     * used for filter items in oc-admin
     */
    public function findByItemStat($stat) {

        switch($stat) {
            case 'spam':
                $sql = "SELECT i.*, s.i_num_spam as num_total FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE s.`i_num_spam` > 0";
                break;

            case 'duplicated':
                $sql = "SELECT i.*, s.i_num_repeated as num_total FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE s.`i_num_repeated` > 0";
                break;

            case 'bad':
                $sql = "SELECT i.*, s.i_num_bad_classified as num_total FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE s.`i_num_bad_classified` > 0";
                break;

            case 'offensive':
                $sql = "SELECT i.*, s.i_num_offensive as num_total FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE s.`i_num_offensive` > 0";
                break;

            case 'expired':
                $sql = "SELECT i.*, s.i_num_expired as num_total FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE s.`i_num_expired` > 0";
                break;

            case 'pending':
                $sql = "SELECT i.*, s.* FROM oc_t_item AS i INNER JOIN `oc_t_item_description` AS d ON i.pk_i_id = d.fk_i_item_id INNER JOIN `oc_t_item_stats` AS s ON i.pk_i_id = s.fk_i_item_id WHERE i.`e_status` = 'INACTIVE'";
                break;

            default:
                break;
        }

        $aItems = $this->conn->osc_dbFetchResults($sql);
        $found = $this->found_rows();
        $items = $this->extendData($aItems);
        $items = $this->extendCategoryName($items);
        return array('found' => $found, 'items' => $items);
    }

    public function updateLocaleForce($id, $locale, $title, $text) {
        $sql = sprintf("REPLACE INTO %st_item_description SET `s_title` = '%s', `s_description` = '%s', `fk_c_locale_code` = '%s', `fk_i_item_id` = %s, `s_what` = '%s'", DB_TABLE_PREFIX, $title, $text, $locale, $id, $title . " " . $text);
        $this->conn->osc_dbExec($sql);
        $date = date('Y-m-d H:i:s');
        $sql = sprintf("UPDATE %st_item SET `dt_mod_date` = '%s' WHERE `pk_i_id` = %s", DB_TABLE_PREFIX, $date, $id);
        return $this->conn->osc_dbExec($sql);
    }

    public function deleteByID($id) {
        $this->conn->osc_dbExec('DELETE FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        $this->conn->osc_dbExec('DELETE FROM %st_item_comment WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        $this->conn->osc_dbExec('DELETE FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        $this->conn->osc_dbExec('DELETE FROM %st_item_location WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        $this->conn->osc_dbExec('DELETE FROM %st_item_stats WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        $this->conn->osc_dbExec('DELETE FROM %st_item WHERE pk_i_id = %d', DB_TABLE_PREFIX, $id);
    }

    public function deleteByPrimaryKey($id) {
        return $this->deleteByID($id);
    }

	public function delete($conditions) {
		$where = array();
		foreach($conditions as $key => $value) {
			if($key == DB_CUSTOM_COND)
				$where[] = $value;
			else
				$where[] = $key . ' = ' . $this->formatValue($value);
		}
		$where = implode(' AND ', $where);
		$items = $this->listWhere($where);
        foreach($items as $item) {
            $this->deleteByID($item['pk_i_id']);
        }
	}


}
