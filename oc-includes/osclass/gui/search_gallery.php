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
<div>
    <?php foreach ($items as $i) { ?>
    <div class="searchGalleryItem" >
    <?php if ( osc_itemHasThumbnail($i) ) { ?>
        <a href="<?php osc_createItemURL($i, true); ?>"><img src="<?php echo osc_itemThumbnail($i); ?>" class="searchGalleryImageLink" /></a>
    <?php } ?>
        <div class="searchGalleryItemPrice"><?php echo osc_formatPrice($i); ?></div>
        <p>
            <a href="<?php osc_createItemURL($i, true); ?>" class="searchGalleryLink" ><?php echo $i['s_title']; ?></a>
        </p>
        <p><?php echo strip_tags($i['s_description']); ?></p>
    </div>
    <?php } ?>
    <div style="clear: both;"></div>
</div>
