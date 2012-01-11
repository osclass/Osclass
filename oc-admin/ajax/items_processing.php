<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

     class ItemsProcessingAjax 
     {
        private $items;
        private $result;
        private $toJSON;
        private $toDatatables;

        private $limit;
        private $start;
        private $total;
        private $search;
        private $order_by = array();
        private $stat;
        private $extraCols = 0;
        private $sExtraCol = array();

        private $column_names  = 
            array(  0=> 'dt_pub_date',
                    1=> 's_title',
                    2=> 's_contact_name',
                    3=> 's_category_name',
                    4=> 's_country',
                    5=> 's_region',
                    6=> 's_city',
                    7=> 'dt_pub_date');

        private $tables_columns = 
            array(  0=> NULL,
                    1=> NULL,
                    2=> NULL,
                    3=> NULL,
                    4=> NULL,
                    5=> NULL,
                    6=> NULL,
                    7=> NULL);

        private $tables_filters =
            array( 
                'fCol_userIdValue'  => '%st_item.fk_i_user_id'
                ,'fCol_countryId'   => '%st_item_location.fk_c_country_code'
                ,'fCol_regionId'    => '%st_item_location.fk_i_region_id'
                ,'fCol_cityId'      => '%st_item_location.fk_i_city_id'
                ,'fCol_country'     => '%st_item_location.s_country'
                ,'fCol_region'      => '%st_item_location.s_region'
                ,'fCol_city'        => '%st_item_location.s_city'
                ,'fCol_catId'       => '%st_item.fk_i_category_id'
                ,'fCol_bPremium'    => '%st_item.b_premium'
                ,'fCol_bActive'     => '%st_item.b_active'
                ,'fCol_bEnabled'    => '%st_item.b_enabled'
                ,'fCol_bSpam'       => '%st_item.b_spam'
                );

        /* For Datatables */
        private $sOutput = null;
        private $sEcho = null;
        private $filters = array();

        private $_get;

        function __construct($params) {

            $this->_get = $params;
            $this->getDBParams();


            $mSearch = new Search(true);
            $mSearch->limit($this->start, $this->limit);

            $mSearch->order($this->order_by['column_name'], $this->order_by['type'], $this->order_by['table_name'] );

            if(Params::getParam("catId")!="") {
                $mSearch->addCategory(Params::getParam("catId"));
            }
            if($this->search) {
                $mSearch->addTable(sprintf('%st_item_description as d', DB_TABLE_PREFIX));
                $mSearch->addConditions(sprintf("d.fk_i_item_id = %st_item.pk_i_id", DB_TABLE_PREFIX));
                $mSearch->addConditions(sprintf("MATCH(d.s_title, d.s_description) AGAINST('%s' IN BOOLEAN MODE)", $this->search));
            }
            
            if(@$this->stat['spam']) {
                $this->extraCols++;
                $this->sExtraCol['i_num_spam'] = true;
                $mSearch->addField('SUM(s.`i_num_spam`) as i_num_spam');
                $mSearch->addConditions("s.`i_num_spam` > 0");
                $mSearch->addConditions(sprintf("%st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
                $mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            }
            if(@$this->stat['duplicated']) {
                $this->extraCols++;
                $this->sExtraCol['i_num_repeated'] = true;
                $mSearch->addField('SUM(s.`i_num_repeated`) as i_num_repeated');
                $mSearch->addConditions("s.`i_num_repeated` > 0");
                $mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
                $mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            }
            if(@$this->stat['bad']) {
                $this->extraCols++;
                $this->sExtraCol['i_num_bad_classified'] = true;
                $mSearch->addField('SUM(s.`i_num_bad_classified`) as i_num_bad_classified');
                $mSearch->addConditions("s.`i_num_bad_classified` > 0");
                $mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
                $mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            }
            if(@$this->stat['offensive']) {
                $this->extraCols++;
                $this->sExtraCol['i_num_offensive'] = true;
                $mSearch->addField('SUM(s.`i_num_offensive`) as i_num_offensive');
                $mSearch->addConditions("s.`i_num_offensive` > 0");
                $mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
                $mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            }
            if(@$this->stat['expired']) {
                $this->extraCols++;
                $this->sExtraCol['i_num_expired'] = true;
                $mSearch->addField('SUM(s.`i_num_expired`) as i_num_expired');
                $mSearch->addConditions("s.`i_num_expired` > 0");
                $mSearch->addConditions(sprintf(" %st_item.pk_i_id = s.fk_i_item_id", DB_TABLE_PREFIX));
                $mSearch->addTable(sprintf("%st_item_stats s", DB_TABLE_PREFIX));
            }

            foreach($this->filters as $aFilter ){
                $sFilter = "";
                
                if($aFilter[1] == 'NULL') {
                    $sFilter .= $aFilter[0]." IS NULL";
                } else {
                    $sFilter .= $aFilter[0]." = '".$aFilter[1]."'";
                }
                $sFilter = sprintf( $sFilter , DB_TABLE_PREFIX ) ;
                $mSearch->addConditions( $sFilter );
            }
            // do Search
            $list_items = $mSearch->doSearch(true);

            $this->result = Item::newInstance()->extendCategoryName(Item::newInstance()->extendData($list_items));
            $this->filtered_total = $mSearch->count();
            $this->total = count($list_items); //TEMPORARY FIX

            $this->toDatatablesFormat();
            $this->dumpToDatatables();
        }

        function __destruct() {
            unset($this->_get);
        }

        private function getDBParams() {
            
            foreach($this->_get as $k=>$v) {
                if($k == 'iDisplayStart') $this->start = intval($v);
                if($k == 'iDisplayLength') $this->limit = intval($v);
                if($k == 'sEcho') $this->sEcho = intval($v);

                /* for sorting */
                if($k == 'iSortCol_0') {
                    $this->order_by['column_name'] = $this->column_names[$v];
                    $this->order_by['table_name'] = $this->tables_columns[$v];
                }
                if($k == 'sSortDir_0') $this->order_by['type'] = $v;
                if($k == 'sSearch') {
                    $this->search = base64_decode($v);
                }
                if($k == 'spam')        $this->stat['spam'] = true;
                if($k == 'duplicated')  $this->stat['duplicated'] = true;
                if($k == 'offensive')   $this->stat['offensive'] = true;
                if($k == 'bad')         $this->stat['bad'] = true;
                if($k == 'expired')     $this->stat['expired'] = true;
                // get all filters
                // user filter
                if($k == 'fCol_userIdValue')    array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_countryId')      array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_country')        array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_regionId')       array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_region')         array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_cityId')         array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_city')           array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_catId')          array_push($this->filters, array($this->tables_filters[$k], $v ));

                if($k == 'fCol_bPremium')       array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_bActive')        array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_bEnabled')       array_push($this->filters, array($this->tables_filters[$k], $v ));
                if($k == 'fCol_bSpam')          array_push($this->filters, array($this->tables_filters[$k], $v ));
            }
        }

        /* START - format functions */
        private function toDatatablesFormat() {
            $this->sOutput = '{';
            $this->sOutput .= '"iTotalRecords": '.($this->total).', ';
            $this->sOutput .= '"iTotalDisplayRecords": '.($this->filtered_total).', ';
            $this->sOutput .= '"iExtraCols": '.($this->extraCols).', ';

            $this->sOutput .= '"sExtraCols": [';
            if(isset($this->sExtraCol['i_num_spam'])) {
                $this->sOutput .= '"spam",';
            }
            if(isset($this->sExtraCol['i_num_repeated'])) {
                $this->sOutput .= '"duplicated",';
            }
            if(isset($this->sExtraCol['i_num_bad_classified'])) {
                $this->sOutput .= '"bad",';
            }
            if(isset($this->sExtraCol['i_num_offensive'])) {
                $this->sOutput .= '"offensive",';
            }
            if(isset($this->sExtraCol['i_num_expired'])) {
                $this->sOutput .= '"expired",';
            }
            if($this->extraCols > 0) $this->sOutput = substr($this->sOutput,0,-1);
            $this->sOutput .= '], ';

            $this->sOutput .= '"aaData": [ ';

            if(count($this->result)>0) {
                $count = 0;
                foreach ($this->result as $aRow)
                {
                    // make address (Location)
                    $addr = array();
                    if($aRow['s_address']!='' && $aRow['s_address']!=null) { $addr[] = $aRow['s_address']; };
                    if($aRow['s_city']!='' && $aRow['s_city']!=null) { $addr[] = $aRow['s_city']; };
                    if($aRow['s_zip']!='' && $aRow['s_zip']!=null) { $addr[] = $aRow['s_zip']; };
                    if($aRow['s_region']!='' && $aRow['s_region']!=null) { $addr[] = $aRow['s_region']; };
                    if($aRow['s_country']!='' && $aRow['s_country']!=null) { $addr[] = $aRow['s_country']; };
                    $address = implode(", ", $addr);
                    
                    $this->sOutput .= "[";
                    $this->sOutput .= '"<div style=\'width:10px;\'><input type=\'checkbox\' name=\'id[]\' value=\''.$aRow['pk_i_id'].'\' /></div>",';
                    
                    $title         =   mb_substr($aRow['s_title'], 0, 30, 'utf-8');
                    if($title != $aRow['s_title']) {
                        $title .= "...";
                    }
                    $this->sOutput .= '"'.addslashes(osc_esc_html(preg_replace('|\s+|',' ',$title))).' <br/>';
                    $this->sOutput .= '<div id=\'datatable_wrapper\'><div id=\'datatables_quick_edit\' ';
                    if($count % 2) {
                        $this->sOutput .= ' class=\'even\' ';
                    }else{
                        $this->sOutput .= ' class=\'odd\' ';
                    }
                    $this->sOutput .= ' style=\'position:absolute;\'>';
                    $this->sOutput .= '<a href=\''.osc_admin_base_url(true).'?page=comments&action=list&amp;id='.$aRow['pk_i_id'].'\'>'.  __('View comments') .'</a>';
                    $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=media&action=list&amp;id='. $aRow['pk_i_id'] .'\'>'. __('View media') .'</a>';
                    if(isset($aRow['b_active']) && ($aRow['b_active'] == 1)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status&amp;id='. $aRow['pk_i_id'] .'&amp;value=INACTIVE\'>'. __('Deactivate') .'</a>';
                    } else if (isset($aRow['b_active']) && ($aRow['b_active'] == 0)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status&amp;id='. $aRow['pk_i_id'] .'&amp;value=ACTIVE\'>'. __('Activate') .'</a>';
                    }
                    if(isset($aRow['b_enabled']) && ($aRow['b_enabled'] == 1)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status&amp;id='. $aRow['pk_i_id'] .'&amp;value=DISABLE\'>'. __('Disable') .'</a>';
                    } else if (isset($aRow['b_enabled']) && ($aRow['b_enabled'] == 0)) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status&amp;id='. $aRow['pk_i_id'] .'&amp;value=ENABLE\'>'. __('Enable') .'</a>';
                    }
                    if(isset($aRow['b_premium']) && $aRow['b_premium']) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status_premium&amp;id='. $aRow['pk_i_id'] .'&amp;value=0\'>'. __('Unmark as premium') .'</a>';
                    } else { 
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status_premium&amp;id='. $aRow['pk_i_id'] .'&amp;value=1\'>'. __('Mark as premium') .'</a>';
                    }
                    if(isset($aRow['b_spam']) && $aRow['b_spam']) {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status_spam&amp;id='. $aRow['pk_i_id'] .'&amp;value=0\'>'. __('Unmark as spam') .'</a>';
                    } else {
                        $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=status_spam&amp;id='. $aRow['pk_i_id'] .'&amp;value=1\'>'. __('Mark as spam') .'</a>';
                    }
                    $this->sOutput .= ' | <a href=\''.osc_admin_base_url(true).'?page=items&action=item_edit&amp;id='. $aRow['pk_i_id'] .'\'>'. __('Edit') .'</a>';
                                            $var = 'onclick=\"javascript:return confirm(\''.__('This action can not be undone. Are you sure you want to continue?').'\')\"';
                    $this->sOutput .= ' | <a '.$var.' href=\''.osc_admin_base_url(true).'?page=items&action=delete&amp;id[]='. $aRow['pk_i_id'] .'\'>'. __('Delete') .'</a>';

                    if($this->stat){
                        foreach($this->stat as $key => $_stat) {
                            $this->sOutput .= ' | <a '.$var.' href=\''.osc_admin_base_url(true).'?page=items&action=clear_stat&amp;stat='.$key.'&amp;id='. $aRow['pk_i_id'] .'\'>'. __('Clear') .' '.$key.'</a>';
                        }
                        $this->sOutput .= '</div>",';
                    } else {
                        $this->sOutput .= '</div></div>",';
                    }
                    
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_user_name'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_category_name'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_country'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_region'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['s_city'])).'",';
                    $this->sOutput .= '"'.addslashes(osc_esc_html($aRow['dt_pub_date'])).'"';
                    if($this->extraCols > 0) $this->sOutput .= ',';

                    if(isset($aRow['i_num_spam'])) {
                        $this->sOutput .= '"'.$aRow['i_num_spam'].'",';
                    }
                    if(isset($aRow['i_num_repeated'])) {
                        $this->sOutput .= '"'.$aRow['i_num_repeated'].'",';
                    }
                    if(isset($aRow['i_num_bad_classified'])) {
                        $this->sOutput .= '"'.$aRow['i_num_bad_classified'].'",';
                    }
                    if(isset($aRow['i_num_offensive'])) {
                        $this->sOutput .= '"'.$aRow['i_num_offensive'].'",';
                    }
                    if(isset($aRow['i_num_expired'])) {
                        $this->sOutput .= '"'.$aRow['i_num_expired'].'",';
                    }
                    if($this->extraCols > 0) $this->sOutput = substr($this->sOutput,0,-1);


                    if(end($this->result) == $aRow) {
                        $this->sOutput .= "]";

                    } else {
                        $this->sOutput .= "],";
                    }
                    $count++;
                }
            }
            $this->sOutput .= ']}';

        }

        private function toJSON($result) {
            $this->toJSON = json_encode($result);
        }
        /* END - format functions */

        /* START - dump results */
        private function dumpResult() {
            $this->toJSON($this->result);
            echo $this->toJSON();
        }

        private function dumpToDatatables() {
            echo str_replace("\'", "'", $this->sOutput);
        }
        /* END - dump results */
     }
     
?>