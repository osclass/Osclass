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

function osc_show_pagination_admin($aData)
{
    $pageActual = Params::getParam('iPage');
    $urlActual  = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];
    $urlActual  = preg_replace('/&iPage=(\d+)?/', '', $urlActual) ;
    $pageTotal  = ceil($aData['iTotalDisplayRecords']/$aData['iDisplayLength']);
    $params     = array(
        'total'    => $pageTotal,
        'selected' => $pageActual - 1,
        'url'      => $urlActual . '&iPage={PAGE}',
        'sides'    => 5
    );
?>
<div class="has-pagination">
    <form method="get" action="<?php echo $urlActual; ?>" style="display:inline;">
        <?php foreach( Params::getParamsAsArray('get') as $key => $value ) { ?>
        <?php if($key!='iPage') {?>
        <input type="hidden" name="<?php echo $key;?>" value="<?php echo osc_esc_html($value); ?>" />
        <?php } } ?>
        <ul>
            <li>
                <span class="list-first"><?php _e('Page'); ?></span>
            </li>
            <li class="pagination-input">
                <input id="gotoPage" type="text" name="iPage" value="<?php echo osc_esc_html($pageActual); ?>"/><button type="submit"><?php _e('Go!'); ?></button>
            </li>
        </ul>
    </form>
<?php
        $pagination = new Pagination($params);
        $aux = $pagination->doPagination();
        echo $aux;
?>
</div>
<?php 
}
?>