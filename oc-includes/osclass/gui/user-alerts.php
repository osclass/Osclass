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
<h2><?php _e('Your alerts'); ?></h2>

<?php if(count($alerts) == 0): ?>
	<h3><?php _e('You do not have any alerts yet.'); ?></h3>
<?php else: ?>
<?php foreach($alerts as $a): ?>
	<div class="userItem" >
		<div><?php echo __('Alert'); ?> | <a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')\" href="user.php?action=unsub_alert&amp;alert=<?php echo $a['s_search']; ?>&amp;email=<?php echo $a['s_email']; ?>"><?php echo __('Delete this alert'); ?></a></div>

        <div style="width: 75%; padding-left: 100px;" >
        <?php foreach($a['items'] as $i) : ?>
	        <div class="userItem" >
		        <div><a href="<?php echo osc_item_url($i); ?>"><?php echo $i['s_title']; ?></a></div>

		        <div class="userItemData" >
		        <?php _e('Publication date'); ?>: <?php echo osc_formatDate($i); ?><br />
		        <?php _e('Price'); ?>: <?php echo osc_formatPrice($i); ?>
		        </div>

	        </div>
	        <br />
        <?php endforeach; ?>
        </div>
	</div>
	<br />
<?php endforeach; ?>
<?php endif; ?>
