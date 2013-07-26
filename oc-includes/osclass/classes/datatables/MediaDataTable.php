<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
     * MediaDataTable class
     * 
     * @since 3.1
     * @package Osclass
     * @subpackage classes
     * @author Osclass
     */
    class MediaDataTable extends DataTable
    {
        private $order_by;
        private $resourceID;
        
        public function table($params)
        {
            
            $this->addTableHeader();
            $this->getDBParams($params);

            $media = ItemResource::newInstance()->getResources($this->resourceID, $this->start, $this->limit, ( $this->order_by['column_name'] ? $this->order_by['column_name'] : 'r.pk_i_id' ), ( $this->order_by['type'] ? $this->order_by['type'] : 'desc' ) );
            $this->processData($media);
            
            $this->total = ItemResource::newInstance()->countResources();
            if( $this->resourceID == null ) {
                $this->total_filtered = $this->total;
            } else {
                $this->total_filtered = ItemResource::newInstance()->countResources( $this->resourceID );
            }
                        
            return $this->getData();
        }

        private function addTableHeader()
        {

            $this->addColumn('bulkactions', '<input id="check_all" type="checkbox" />');
            $this->addColumn('file', __('File'));
            $this->addColumn('action', __('Action'));
            $this->addColumn('attached_to', __('Attached to'));
            $this->addColumn('date', __('Date'));

            $dummy = &$this;
            osc_run_hook("admin_media_table", $dummy);
        }
        
        private function processData($media)
        {
            if(!empty($media)) {
            
                foreach($media as $aRow) {
                    $row = array();

                    $row['bulkactions'] = '<input type="checkbox" name="id[]" value="' . $aRow['pk_i_id'] . '" />';
                    $row['file'] = '<div id="media_list_pic"><img src="' . osc_apply_filter('resource_path', osc_base_url() . $aRow['s_path']) . $aRow['pk_i_id'] . '_thumbnail.' . $aRow['s_extension'] . '" style="max-width: 60px; max-height: 60px;" /></div> <div id="media_list_filename">' . $aRow['s_content_type'];
                    $row['action'] = '<a onclick="return delete_dialog(\'' . $aRow['pk_i_id'] . '\');" >' . __('Delete') . '</a>';
                    $row['attached_to'] = '<a target="_blank" href="' . osc_item_url_ns($aRow['fk_i_item_id']) . '">item #' . $aRow['fk_i_item_id'] . '</a>';
                    $row['date'] = osc_format_date($aRow['dt_pub_date']);

                    $row = osc_apply_filter('media_processing_row', $row, $aRow);

                    $this->addRow($row);
                    $this->rawRows[] = $aRow;
                }

            }
        }
                
        private function getDBParams($_get)
        {
            
            $this->order_by['column_name'] = 'r.pk_i_id';
            $this->order_by['type'] = 'desc';
            
            foreach($_get as $k => $v) {
                if( ( $k == 'resourceId' ) && !empty($v) ) {
                    $this->resourceID = intval($v);
                }
                if( $k == 'iDisplayStart' ) {
                    $this->start = intval($v);
                }
                if( $k == 'iDisplayLength' ) {
                    $this->limit = intval($v);
                }
                if( $k == 'sEcho' ) {
                    $this->sEcho = intval($v);
                }
            }

            // set start and limit using iPage param
            $start = ((int)Params::getParam('iPage')-1) * $_get['iDisplayLength'];

            $this->start = intval($start);
            $this->limit = intval($_get['iDisplayLength']);

            
        }
        
    }

?>