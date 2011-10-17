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

    /**
     * Model database for City table
     * 
     * @package OSClass
     * @subpackage Model
     * @since unknown
     */
    class Stats extends DAO
    {
        /**
         * It references to self object: City.
         * It is used as a singleton
         * 
         * @access private
         * @since unknown
         * @var Stats 
         */
        private static $instance ;

        /**
         * It creates a new Stats object class ir if it has been created
         * before, it return the previous object
         * 
         * @access public
         * @since unknown
         * @return Stats
         */
        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self ;
            }
            return self::$instance ;
        }

        /**
         * Set data related to t_meta_fields table
         */
        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_item_stats') ;
            $this->setStatss( array('fk_i_item_id', 'i_num_views', 'i_num_spam', 'i_num_repeated', 'i_num_bad_classified', 'i_num_offensive', 'i_num_expired', 'dt_date', 'i_num_premium_views') ) ;
        }

        public function new_users_count($from_date, $date = 'day') {
            if($date=='week') {
                $this->dao->select('WEEK(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('WEEK(dt_reg_date');
            } else if($date=='month') {
                $this->dao->select('MONTHNAME(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('MONTH(dt_reg_date');
            } else {
                $this->dao->select('DATE(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('DAY(dt_reg_date');
            }
            $this->dao->from(sprintf('%st_user', DB_TABLE_PREFIX));
            $this->dao->where("dt_reg_date > '".$from_date."'");
            $this->dao->orderBy('dt_reg_date', 'DESC');
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function users_by_country() {
            $this->dao->select('s_country, COUNT(pk_i_id) as num');
            $this->dao->from(sprintf('%st_user', DB_TABLE_PREFIX));
            $this->dao->groupBy('s_country');
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function users_by_region() {
            $this->dao->select('s_region, COUNT(pk_i_id) as num');
            $this->dao->from(sprintf('%st_user', DB_TABLE_PREFIX));
            $this->dao->groupBy('s_region');
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function items_by_user() {
            $results = $this->dao->query(sprintf("SELECT AVG( num ) as avg FROM (SELECT COUNT( pk_i_id ) AS num FROM %st_item GROUP BY s_contact_email ) AS dummy_table", DB_TABLE_PREFIX));
            return $results->result();
        }
        
        public function latest_users() {
            $this->dao->select();
            $this->dao->from(sprintf('%st_user', DB_TABLE_PREFIX));
            $this->dao->orderBy('dt_reg_date', 'DEC');
            $this->dao->limit(5);
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function new_items_count($from_date, $date = 'day') {
            if($date=='week') {
                $this->dao->select('WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('WEEK(dt_pub_date');
            } else if($date=='month') {
                $this->dao->select('MONTHNAME(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('MONTH(dt_pub_date');
            } else {
                $this->dao->select('DATE(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('DAY(dt_pub_date');
            }
            $this->dao->from(sprintf('%st_item', DB_TABLE_PREFIX));
            $this->dao->where("dt_pub_date > '".$from_date."'");
            $this->dao->orderBy('dt_pub_date', 'DESC');
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function latest_items() {
            $this->dao->select('l.*, i.*, d.*');
            $this->dao->from(sprintf('%st_item i, %st_item_location l, %st_item_description d', DB_TABLE_PREFIX, DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $this->dao->where('l.fk_i_item_id = i.pk_i_id');
            $this->dao->where('d.fk_i_item_id = i.pk_i_id');
            $this->dao->orderBy('dt_pub_date', 'DESC');
            $this->limit(5);
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function new_comments_count($from_date, $date = 'day') {
            if($date=='week') {
                $this->dao->select('WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('WEEK(dt_pub_date');
            } else if($date=='month') {
                $this->dao->select('MONTHNAME(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('MONTH(dt_pub_date');
            } else {
                $this->dao->select('DATE(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->dao->groupBy('DAY(dt_pub_date');
            }
            $this->dao->from(sprintf('%st_item_comment', DB_TABLE_PREFIX));
            $this->dao->where("dt_pub_date > '".$from_date."'");
            $this->dao->orderBy('dt_pub_date', 'DESC');
            $results = $this->dao->get();
            return $results->result();
        }
        
        public function latest_comments() {
            $this->dao->select('i.*, c.*');
            $this->dao->from(sprintf('%st_item i, %st_item_comment c', DB_TABLE_PREFIX, DB_TABLE_PREFIX));
            $this->dao->where('c.fk_i_item_id = i.pk_i_id');
            $this->dao->orderBy('dt_pub_date', 'DESC');
            $this->limit(5);
            $results = $this->dao->get();
            return $results->result();
        }

        public function new_reports_count($from_date, $date = 'day') {
            if($date=='week') {
                $this->dao->select('WEEK(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->dao->groupBy('WEEK(dt_date');
            } else if($date=='month') {
                $this->dao->select('MONTHNAME(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->dao->groupBy('MONTH(dt_date');
            } else {
                $this->dao->select('dt_date as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->dao->groupBy('DAY(dt_date');
            }
            $this->dao->from(sprintf('%st_item_comment', DB_TABLE_PREFIX));
            $this->dao->where("dt_date > '".$from_date."'");
            $this->dao->orderBy('dt_date', 'DESC');
            $results = $this->dao->get();
            return $results->result();
        }

    }

?>