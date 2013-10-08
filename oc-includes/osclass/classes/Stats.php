<?php

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
     * Stats 
     */
    class Stats 
    {
        /**
         *
         * @var type 
         */
        private static $instance;
        private $conn; 

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * 
         */
        function __construct()
        {
            $conn = DBConnectionClass::newInstance();
            $data = $conn->getOsclassDb();
            $this->conn = new DBCommandClass($data);
        }
        
        public function new_users_count($from_date, $date = 'day') 
        {    
            if($date=='week') {
                $this->conn->select('WEEK(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('WEEK(dt_reg_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('MONTH(dt_reg_date)');
            } else {
                $this->conn->select('DATE(dt_reg_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('DAY(dt_reg_date)');
            }
            $this->conn->from(DB_TABLE_PREFIX.'t_user');
            $this->conn->where("dt_reg_date >= '$from_date'");
            $this->conn->orderBy('dt_reg_date', 'DESC');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function users_by_country()
        {  
            $this->conn->select('s_country, COUNT(pk_i_id) as num');
            $this->conn->from(DB_TABLE_PREFIX.'t_user');
            $this->conn->groupBy('s_country');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function users_by_region() 
        { 
            $this->conn->select('s_region, COUNT(pk_i_id) as num');
            $this->conn->from(DB_TABLE_PREFIX.'t_user');
            $this->conn->groupBy('s_region');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function items_by_user() 
        {
            $result = $this->conn->query("SELECT AVG( num ) as avg FROM (SELECT COUNT( pk_i_id ) AS num FROM ".DB_TABLE_PREFIX."t_item GROUP BY s_contact_email ) AS dummy_table");
            return $result->result();
        }
        
        public function latest_users() 
        {
            $this->conn->select();
            $this->conn->from(DB_TABLE_PREFIX.'t_user');
            $this->conn->orderBy('dt_reg_date', 'DESC');
            $this->conn->limit('5');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function new_items_count($from_date, $date = 'day') 
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('WEEK(dt_pub_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('MONTH(dt_pub_date)');
            } else {
                $this->conn->select('DATE(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('DAY(dt_pub_date)');
            }
            
            $this->conn->from(DB_TABLE_PREFIX."t_item");
            $this->conn->where("dt_pub_date >= '$from_date'");
            $this->conn->orderBy('dt_pub_date', 'DESC');
            
            $result = $this->conn->get();
            if($result) {
                return $result->result();
            }
            return array();
        }
        
        public function latest_items() 
        {
            $this->conn->select('l.*, i.*, d.*');
            $this->conn->from(DB_TABLE_PREFIX.'t_item i, '.DB_TABLE_PREFIX.'t_item_location l, '.DB_TABLE_PREFIX.'t_item_description d');
            $this->conn->where('l.fk_i_item_id = i.pk_i_id AND d.fk_i_item_id = i.pk_i_id');
            $this->conn->groupBy('i.pk_i_id');
            $this->conn->orderBy('dt_pub_date', 'DESC');
            $this->conn->limit('5');
         
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function new_comments_count($from_date, $date = 'day') 
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('WEEK(dt_pub_date)');
            } else if($date=='month') {
                $this->conn->select('MONTH(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('MONTH(dt_pub_date)');
            } else {
                $this->conn->select('DAY(dt_pub_date) as d_date, COUNT(pk_i_id) as num');
                $this->conn->groupBy('DAY(dt_pub_date)');
            }
            
            $this->conn->from(DB_TABLE_PREFIX."t_item_comment");
            $this->conn->where("dt_pub_date >= '$from_date'");
            $this->conn->orderBy('dt_pub_date', 'DESC');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function latest_comments() 
        {
            $this->conn->select('i.*, c.*');
            $this->conn->from(DB_TABLE_PREFIX.'t_item i, '.DB_TABLE_PREFIX.'t_item_comment c');
            $this->conn->where('c.fk_i_item_id = i.pk_i_id');
            $this->conn->orderBy('c.dt_pub_date', 'DESC');
            $this->conn->limit('5');
         
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function new_reports_count($from_date, $date = 'day') 
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->conn->groupBy('WEEK(dt_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_date) as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->conn->groupBy('MONTH(dt_date)');
            } else {
                $this->conn->select('dt_date as d_date, SUM(i_num_views) as views, SUM(i_num_spam) as spam, SUM(i_num_repeated) as repeated, SUM(i_num_bad_classified) as bad_classified, SUM(i_num_offensive) as offensive, SUM(i_num_expired) as expired');
                $this->conn->groupBy('DAY(dt_date)');
            }
            
            $this->conn->from(DB_TABLE_PREFIX.'t_item_stats');
            $this->conn->where("dt_date >= '$from_date'");

            $result = $this->conn->get();
            return $result->result();
        }
        
        public function new_alerts_count($from_date, $date = 'day') 
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_date) as d_date, COUNT(s_email) as num');
                $this->conn->groupBy('WEEK(dt_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_date) as d_date, COUNT(s_email) as num');
                $this->conn->groupBy('MONTH(dt_date)');
            } else {
                $this->conn->select('DATE(dt_date) as d_date, COUNT(s_email) as num');
                $this->conn->groupBy('DAY(dt_date)');
            }
            
            $this->conn->from(DB_TABLE_PREFIX."t_alerts");
            $this->conn->where("dt_date >= '$from_date'");
            $this->conn->where("dt_unsub_date IS NULL");
            $this->conn->orderBy('dt_date', 'ASC');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        public function new_subscribers_count($from_date, $date = 'day') 
        {
            if($date=='week') {
                $this->conn->select('WEEK(dt_date) as d_date, COUNT(DISTINCT s_email) as num');
                $this->conn->groupBy('WEEK(dt_date)');
            } else if($date=='month') {
                $this->conn->select('MONTHNAME(dt_date) as d_date, COUNT(DISTINCT s_email) as num');
                $this->conn->groupBy('MONTH(dt_date)');
            } else {
                $this->conn->select('DATE(dt_date) as d_date, COUNT(DISTINCT s_email) as num');
                $this->conn->groupBy('DAY(dt_date)');
            }
            
            $this->conn->from(DB_TABLE_PREFIX."t_alerts");
            $this->conn->where("dt_date >= '$from_date'");
            $this->conn->where("dt_unsub_date IS NULL");
            $this->conn->orderBy('dt_date', 'ASC');
            
            $result = $this->conn->get();
            return $result->result();
        }
        
        
        
        
    }
?>