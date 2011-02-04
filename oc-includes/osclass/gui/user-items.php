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
?>
<h2><?php _e('Your items') ; ?></h2>

<?php if(count($items) == 0) { ?>
	<h3><?php _e('You do not have any items yet.') ; ?></h3>
<?php } else { ?>
    <?php foreach($items as $i) { ?>
        <div class="userItem" >
            <div><a href="<?php osc_create_item_url($i, true) ; ?>"><?php echo $i['s_title'] ; ?></a></div>

            <div class="userItemData" >
                <?php _e('Publication date'); ?>: <?php echo osc_formatDate($i) ; ?><br />
                <?php _e('Price'); ?>: <?php echo osc_format_price($i) ; ?>
            </div>
            <div class="userItemButtons" ><a onclick="javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>');" href="<?php echo osc_create_url(array('file' => 'user', 'action' => 'deleteItem', 'id' => $i['pk_i_id'], 'secret' => $i['s_secret']));?>"><?php _e('Delete'); ?></a> | <a href="<?php echo osc_create_url(array('file' => 'user', 'action' => 'editItem', 'id' => $i['pk_i_id'], 'secret' => $i['s_secret']));?>"><?php _e('Edit') ; ?></a></div>
        </div>
        <br />
    <?php } ?>
<?php } ?>
