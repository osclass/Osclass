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

foreach($items as $i): ?>
<div class="searchListItem" >
	<?php if(osc_itemHasThumbnail($i)): ?>
		<a href="<?php echo osc_createItemURL($i); ?>"><img src="<?php echo osc_itemThumbnail($i); ?>" class="searchListImageLink" /></a>
	<?php endif; ?>
	<div class="searchListItemData">
		<p><a href="<?php echo osc_createItemURL($i); ?>" class="searchListLink"><?php echo $i['s_title']; ?></a></p>

		<p><?php echo strip_tags($i['s_description']); ?></p>
	</div>
	<div class="searchListItemPrice"><?php echo osc_formatPrice($i); ?></div>
	<div style="clear: both;"></div>
</div>
<?php endforeach; ?>
