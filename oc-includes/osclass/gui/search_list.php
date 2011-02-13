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

 <table border="0" cellspacing="0">
     <tbody>
        <?php $class = "even" ; ?>
        <?php foreach($items as $item) { ?>
            <tr class="<?php echo $class; ?>">
                 <td class="photo">
                     <?php if(osc_item_has_thumbnail($item)) { ?>
                        <a href="<?php echo osc_item_url($item) ; ?>"><img src="<?php echo osc_item_thumbnail_url($item) ; ?>" /></a>
                    <?php } else { ?>
                        <img src="<?php echo $this->osc_get_theme_url('images/no_photo.gif') ; ?>" />
                    <?php } ?>
                 </td>
                 <td class="text">
                     <h3><a href="<?php echo osc_item_url($item) ; ?>"><?php echo $item['s_title'] ; ?></a></h3>
                     <!--
                         <h4><strong>Full time</strong> <span>|</span> <strong>Web development</strong></h4>
                     -->
                     <p><?php echo strip_tags($i['s_description']) ; ?></p>
                 </td>
                 <td class="price"><strong><?php echo osc_formatPrice($item) ; ?></strong></td>
             </tr>
            <?php $class = ($class == 'even') ? 'odd' : 'even' ; ?>
        <?php } ?>
    </tbody>
</table>