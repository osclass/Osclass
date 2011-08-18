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

    class Item extends DAO
    {
        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName()
        {
            return DB_TABLE_PREFIX . 't_item';
        }

        public function extendCategoryName($items)
        {
            $prefLocale = osc_current_user_locale();

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

        public function extendData($items)
        {
            if(defined('OC_ADMIN')) {
                $prefLocale = osc_current_admin_locale();
            } else {
                $prefLocale = osc_current_user_locale();
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

        public function extendDataSingle($item)
        {
            $prefLocale = osc_current_user_locale();

            $descriptions = $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
            $item['locale'] = array();
            foreach ($descriptions as $desc) {
                if ($desc['s_title'] != "" || $desc['s_description'] != "") {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
            }
            $is_itemLanguageAvailable = (!empty($item['locale'][$prefLocale]['s_title'])
                                         && !empty($item['locale'][$prefLocale]['s_description']));
            if (isset($item['locale'][$prefLocale]) && $is_itemLanguageAvailable) {
                $item['s_title'] = $item['locale'][$prefLocale]['s_title'];
                $item['s_description'] = $item['locale'][$prefLocale]['s_description'];
            } else {
                $mCategories = new Category();
                $aCategory = $mCategories->findByPrimaryKey($item['fk_i_category_id']);

                $title = sprintf(__('%s in'), $aCategory['s_name']);
                if(isset($item['s_city'])) {
                    $title .= ' ' . $item['s_city'];
                } else if(isset($item['s_region'])) {
                    $title .= ' ' .$item['s_region'];
                } else if(isset($item['s_country'])) {
                    $title .= ' ' . $item['s_country'];
                }
                $item['s_title'] = $title;
                $item['s_description'] = __('There\'s no description available in your language');
                unset($data);
            }
            return $item;
        }

        public function listWhere()
        {
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

        public function findByPrimaryKey($id)
        {
            $item = $this->conn->osc_dbFetchResult('SELECT l.*, i.*, SUM(s.i_num_views) AS i_num_views FROM %s i LEFT JOIN %st_item_location l ON l.fk_i_item_id = i.pk_i_id LEFT JOIN %st_item_stats s ON i.pk_i_id = s.fk_i_item_id WHERE i.pk_i_id = %d GROUP BY s.fk_i_item_id', $this->getTableName(), DB_TABLE_PREFIX, DB_TABLE_PREFIX, $id);

            if(count($item) > 0) {
                return $this->extendDataSingle($item);
            } else {
                return array();
            }
        }

        public function findResourcesByID($id)
        {
            return $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        }

        public function findLocationByID($id)
        {
            return $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_location WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
        }

        public function findByCategoryID($catId)
        {
            return $this->listWhere('fk_i_category_id = %d', $catId);
        }

        public function found_rows()
        {
            $sql = "SELECT FOUND_ROWS() as total";
            $total_ads = $this->conn->osc_dbFetchResult($sql);
            return $total_ads['total'];
        }

        public function total_items($category = null, $active = null)
        {
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

        // LEAVE THIS FOR COMPATIBILITIES ISSUES (ONLY SITEMAP GENERATOR)
        // BUT REMEMBER TO DELETE IN ANYTHING > 2.1.x THANKS
        public function listLatest($limit = 10)
        {
            return $this->listWhere(" b_active = 1 AND b_enabled = 1 ORDER BY dt_pub_date DESC LIMIT " . $limit);
        }

        public function insertLocale($id, $locale, $title, $description, $what)
        {
            $title = addslashes($title);
            $description = addslashes($description);
            $what = addslashes($what);
            $sql = sprintf("INSERT INTO %st_item_description (`fk_i_item_id`, `fk_c_locale_code`, `s_title`, `s_description`, `s_what`) VALUES ('%s', '%s', '%s', '%s', '%s')", DB_TABLE_PREFIX, $id, $locale, $title, $description, $what) ;
            return $this->conn->osc_dbExec($sql);
        }

        public function listLatestExtended($limit = 10)
        {
            return $this->conn->osc_dbFetchResults('SELECT * FROM %s, %st_item_location WHERE %st_item.b_active = 1 AND %st_item.b_enabled = 1 AND %st_item_location.fk_i_item_id = %st_item.pk_i_id  ORDER BY %st_item.dt_pub_date DESC LIMIT %d', $this->getTableName(), DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $limit) ;
        }

        public function listAllWithCategories()
        {
            return $this->conn->osc_dbFetchResults('SELECT i.*, cd.s_name AS s_category_name FROM %st_item i, %st_category c, %st_category_description cd WHERE c.pk_i_id = i.fk_i_category_id AND cd.fk_i_category_id = i.fk_i_category_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX) ;
        }

        public function search($pattern)
        {
            return $this->listWhere("s_title LIKE '%%%s%%' OR s_description LIKE '%%%1\$s%%'", $pattern);
        }

        public function findByUserID($userId, $start = 0, $end = null)
        {
            if($end==null) {
                $limit_text = '';
            } else {
                $limit_text = ' LIMIT '.$start.", ".$end;
            }
            $items = $this->conn->osc_dbFetchResults('SELECT l.*, i.* FROM %s i, %st_item_location l WHERE l.fk_i_item_id = i.pk_i_id AND i.fk_i_user_id = %d ORDER BY i.pk_i_id DESC %s', $this->getTableName(), DB_TABLE_PREFIX, $userId, $limit_text);
            return $this->extendData($items);
        }

        public function countByUserID($userId)
        {
            $items = $this->conn->osc_dbFetchResult('SELECT count(i.pk_i_id) as total FROM %s i WHERE i.fk_i_user_id = %d ORDER BY i.pk_i_id DESC ', $this->getTableName(), $userId);
            return $items['total'];
        }

        public function findByUserIDEnabled($userId, $start = 0, $end = null)
        {
            if($end==null) {
                $limit_text = '';
            } else {
                $limit_text = ' LIMIT '.$start.", ".$end;
            }
            $items = $this->conn->osc_dbFetchResults('SELECT l.*, i.* FROM %s i, %st_item_location l WHERE i.b_enabled = 1 AND l.fk_i_item_id = i.pk_i_id AND i.fk_i_user_id = %d ORDER BY i.pk_i_id DESC %s', $this->getTableName(), DB_TABLE_PREFIX, $userId, $limit_text);
            return $this->extendData($items);
        }

        public function countByUserIDEnabled($userId)
        {
            $items = $this->conn->osc_dbFetchResult('SELECT count(i.pk_i_id) as total FROM %s i WHERE i.b_enabled = 1 AND i.fk_i_user_id = %d ORDER BY i.pk_i_id DESC ', $this->getTableName(), $userId);
            return $items['total'];
        }

        public function listLocations($scope)
        {
            $availabe_scopes = array('country', 'region', 'city');
            $fields = array('country' => 's_country',
                            'region'  => 's_region',
                            'city'    => 's_city');
            $stringFields = array('country' => 's_country',
                                  'region'  => 's_region',
                                  'city'    => 's_city');

            if(!in_array($scope, $availabe_scopes)) {
                return array();
            }

            $sql = 'SELECT *, count(*) as total FROM ' . DB_TABLE_PREFIX . 't_item_location WHERE ' . $fields[$scope] . ' IS NOT NULL';
            $sql .= ' GROUP BY ' . $fields[$scope] . ' ORDER BY ' . $stringFields[$scope];

            $results = $this->conn->osc_dbFetchResults($sql);

            return $results;
        }

        public function clearStat($id, $stat)
        {
            switch($stat) {
                case 'spam':
                    $sql = "UPDATE `%st_item_stats` SET i_num_spam = 0 WHERE fk_i_item_id = $id";
                    break;

                case 'duplicated':
                    $sql = "UPDATE `%st_item_stats` SET i_num_repeated = 0 WHERE fk_i_item_id = $id";
                    break;

                case 'bad':
                    $sql = "UPDATE `%st_item_stats` SET i_num_bad_classified = 0 WHERE fk_i_item_id = $id";
                    break;

                case 'offensive':
                    $sql = "UPDATE `%st_item_stats` SET i_num_offensive = 0 WHERE fk_i_item_id = $id";
                    break;

                case 'expired':
                    $sql = "UPDATE `%st_item_stats` SET i_num_expired = 0 WHERE fk_i_item_id = $id";
                    break;

                default:
                    break;
            }
            $sql = sprintf($sql,DB_TABLE_PREFIX);
            return $this->conn->osc_dbExec($sql);
        }

        public function updateLocaleForce($id, $locale, $title, $text)
        {
            $title = addslashes($title);
            $text  = addslashes($text);

            $sql = sprintf("REPLACE INTO %st_item_description SET `s_title` = '%s', `s_description` = '%s', `fk_c_locale_code` = '%s', `fk_i_item_id` = %s, `s_what` = '%s'", DB_TABLE_PREFIX, $title, $text, $locale, $id, $title . " " . $text);
            return $this->conn->osc_dbExec($sql);
            /*$date = date('Y-m-d H:i:s');
            $sql = sprintf("UPDATE %st_item SET `dt_mod_date` = '%s' WHERE `pk_i_id` = %s", DB_TABLE_PREFIX, $date, $id);
            return $this->conn->osc_dbExec($sql);*/
        }
        
        public function meta_fields($id) {
            return $this->conn->osc_dbFetchResults("SELECT im.s_value as s_value,mf.pk_i_id as pk_i_id, mf.s_name as s_name, mf.e_type as e_type FROM %st_item i, %st_item_meta im, %st_meta_categories mc, %st_meta_fields mf WHERE im.fk_i_item_id = %d AND mf.pk_i_id = im.fk_i_field_id AND i.pk_i_id = %d AND mf.pk_i_id = mc.fk_i_field_id AND mc.fk_i_category_id = i.fk_i_category_id", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX, $id, $id);
        }

        public function deleteByPrimaryKey($id)
        {
            osc_run_hook('delete_item', $id);
            $item = $this->findByPrimaryKey($id);
            if($item['b_active']==1) {
                CategoryStats::newInstance()->decreaseNumItems($item['fk_i_category_id']);
            }
            
            $this->conn->osc_dbExec('DELETE FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec('DELETE FROM %st_item_comment WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec('DELETE FROM %st_item_resource WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec('DELETE FROM %st_item_location WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec('DELETE FROM %st_item_stats WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            $this->conn->osc_dbExec('DELETE FROM %st_item_meta WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $id);
            return $this->conn->osc_dbExec('DELETE FROM %st_item WHERE pk_i_id = %d', DB_TABLE_PREFIX, $id);
        }

        public function delete($conditions)
        {
            $success = false;
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
                $success = $this->deleteByPrimaryKey($item['pk_i_id']);
            }
            return $success;
        }
    }

?>
