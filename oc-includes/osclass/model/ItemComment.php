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

    class ItemComment extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName() { return DB_TABLE_PREFIX . 't_item_comment'; }

        public function findByItemIDAll($id) {
            return $this->listWhere('fk_i_item_id = ' . $id);
        }

        public function findByItemID($id, $page = null, $comments_per_page = null) {
            if($page==null) { $page = osc_item_comments_page();};
            if($page=='') { $page = 0; };
            if($comments_per_page==null) { $comments_per_page = osc_comments_per_page(); };
            if($comments_per_page=='all' || $comments_per_page==0) {
                return $this->conn->osc_dbFetchResults("SELECT c.* FROM %st_item_comment c WHERE fk_i_item_id = %d AND e_status = 'ACTIVE'", DB_TABLE_PREFIX, $id);
            } else {
                return $this->conn->osc_dbFetchResults("SELECT c.* FROM %st_item_comment c WHERE fk_i_item_id = %d AND e_status = 'ACTIVE' LIMIT %d, %d", DB_TABLE_PREFIX, $id, ($page*$comments_per_page), $comments_per_page);
            }
        }
        
        public function total_comments($id) {
            $total = $this->conn->osc_dbFetchResult("SELECT count(pk_i_id) as total FROM %st_item_comment WHERE fk_i_item_id = %d AND e_status = 'ACTIVE' GROUP BY fk_i_item_id", DB_TABLE_PREFIX, $id);
            return $total['total'];
        }

        public function findByAuthorID($id) {
            return $this->listWhere('fk_i_user_id = ' . $id . " AND e_status = 'ACTIVE'");
        }


        public function extendData($items) {
            $prefLocale = osc_current_user_locale();

            $results = array();
            foreach($items as $item) {
                $descriptions = $this->conn->osc_dbFetchResults('SELECT * FROM %st_item_description WHERE fk_i_item_id = %d', DB_TABLE_PREFIX, $item['fk_i_item_id']);
                $item['locale'] = array();
                foreach($descriptions as $desc) {
                    $item['locale'][$desc['fk_c_locale_code']] = $desc;
                }
                if(isset($item['locale'][$prefLocale])) {
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



        public function getAllComments($itemId = null) {
            if(is_null($itemId)) {
                $comments = $this->conn->osc_dbFetchResults('SELECT c.* FROM %st_item_comment c, %st_item i WHERE c.fk_i_item_id = i.pk_i_id', DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            } else {
                $comments = $this->conn->osc_dbFetchResults('SELECT c.* FROM %st_item_comment c, %st_item i WHERE i.pk_i_id = '.$itemId.' AND fk_i_item_id = ' . $itemId .'', DB_TABLE_PREFIX, DB_TABLE_PREFIX);
            }
            return $this->extendData($comments);
        }

        public function getLastComments($num) {
                if(!intval($num)) return false;

                $lang = osc_current_user_locale() ;
                return $this->conn->osc_dbFetchResults('SELECT i.*, d.s_title
                    FROM %st_item_comment i
                    JOIN %st_item c ON c.pk_i_id = i.fk_i_item_id
                    JOIN %st_item_description d ON d.fk_i_item_id = i.fk_i_item_id
                    GROUP BY d.fk_i_item_id
                    ORDER BY pk_i_id DESC LIMIT 0, ' . $num . '', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX) ;
        }
    }

?>
