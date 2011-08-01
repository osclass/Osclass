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

    class Stats extends DAO {

        private static $instance ;

        public static function newInstance() {
            if(!self::$instance instanceof self) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        public function getTableName()
        {
            return '';
        }

        public function new_users_count($from_date, $date = 'day') {
            if($date=='week') {
                return $this->conn->osc_dbFetchResults("SELECT WEEK(dt_reg_date) as d_date, COUNT(pk_i_id) as num FROM %st_user WHERE dt_reg_date > '%s' GROUP BY WEEK(dt_reg_date) ORDER BY dt_reg_date DESC", DB_TABLE_PREFIX, $from_date);
            } else if($date=='month') {
                return $this->conn->osc_dbFetchResults("SELECT MONTHNAME(dt_reg_date) as d_date, COUNT(pk_i_id) as num FROM %st_user WHERE dt_reg_date > '%s' GROUP BY MONTH(dt_reg_date) ORDER BY dt_reg_date DESC", DB_TABLE_PREFIX, $from_date);
            } else {
                return $this->conn->osc_dbFetchResults("SELECT DATE(dt_reg_date) as d_date, COUNT(pk_i_id) as num FROM %st_user WHERE dt_reg_date > '%s' GROUP BY DAY(dt_reg_date) ORDER BY dt_reg_date DESC", DB_TABLE_PREFIX, $from_date);
            }
        }
        
        public function users_by_country() {
            return $this->conn->osc_dbFetchResults("SELECT s_country, COUNT(pk_i_id) as num FROM %st_user GROUP BY s_country", DB_TABLE_PREFIX);
        }
        
        public function users_by_region() {
            return $this->conn->osc_dbFetchResults("SELECT s_region, COUNT(pk_i_id) as num FROM %st_user GROUP BY s_region", DB_TABLE_PREFIX);
        }
        
        public function items_by_user() {
            return $this->conn->osc_dbFetchResult("SELECT AVG( num ) as avg FROM (SELECT COUNT( pk_i_id ) AS num FROM %st_item GROUP BY s_contact_email ) AS dummy_table", DB_TABLE_PREFIX);
        }
        
        public function latest_users() {
            return $this->conn->osc_dbFetchResults("SELECT * FROM %st_user ORDER BY dt_reg_date DESC LIMIT 5", DB_TABLE_PREFIX);
        }
        
        public function new_items_count($from_date, $date = 'day') {
            if($date=='week') {
                return $this->conn->osc_dbFetchResults("SELECT WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item WHERE dt_pub_date > '%s' GROUP BY WEEK(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            } else if($date=='month') {
                return $this->conn->osc_dbFetchResults("SELECT MONTHNAME(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item WHERE dt_pub_date > '%s' GROUP BY MONTH(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            } else {
                return $this->conn->osc_dbFetchResults("SELECT DATE(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item WHERE dt_pub_date > '%s' GROUP BY DAY(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            }
        }
        
        public function latest_items() {
            return $this->conn->osc_dbFetchResults("SELECT l.*, i.*, d.* FROM %st_item i, %st_item_location l, %st_item_description d WHERE l.fk_i_item_id = i.pk_i_id AND d.fk_i_item_id = i.pk_i_id ORDER BY dt_pub_date DESC LIMIT 5", DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX);
        }
        
        public function new_comments_count($from_date, $date = 'day') {
            if($date=='week') {
                return $this->conn->osc_dbFetchResults("SELECT WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item_comment WHERE dt_pub_date > '%s' GROUP BY WEEK(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            } else if($date=='month') {
                return $this->conn->osc_dbFetchResults("SELECT MONTHNAME(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item_comment WHERE dt_pub_date > '%s' GROUP BY MONTH(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            } else {
                return $this->conn->osc_dbFetchResults("SELECT DATE(dt_pub_date) as d_date, COUNT(pk_i_id) as num FROM %st_item_comment WHERE dt_pub_date > '%s' GROUP BY DAY(dt_pub_date) ORDER BY dt_pub_date DESC", DB_TABLE_PREFIX, $from_date);
            }
        }
        
        public function latest_comments() {
            return $this->conn->osc_dbFetchResults("SELECT i.*, c.* FROM %st_item i, %st_item_comment c WHERE c.fk_i_item_id = i.pk_i_id ORDER BY c.dt_pub_date DESC LIMIT 5", DB_TABLE_PREFIX, DB_TABLE_PREFIX);
        }

        public function new_reports_count($from_date, $date = 'day') {
            if($date=='week') {
                return $this->conn->osc_dbFetchResults("SELECT WEEK(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired FROM %st_item_stats WHERE dt_date > '%s' GROUP BY WEEK(dt_date) ORDER BY dt_date DESC", DB_TABLE_PREFIX, $from_date);
            } else if($date=='month') {
                return $this->conn->osc_dbFetchResults("SELECT MONTHNAME(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired FROM %st_item_stats WHERE dt_date > '%s' GROUP BY MONTH(dt_date) ORDER BY dt_date DESC", DB_TABLE_PREFIX, $from_date);
            } else {
                return $this->conn->osc_dbFetchResults("SELECT dt_date as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired FROM %st_item_stats WHERE dt_date > '%s' GROUP BY DAY(dt_date) ORDER BY dt_date DESC", DB_TABLE_PREFIX, $from_date);
            }
        }
        

    }

?>