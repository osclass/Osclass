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

<?php osc_show_widgets('footer'); ?>
<div id="footer">
    <div class="inner">
        <a href="<?php echo osc_contact_url(); ?>"><?php _e('Contact', 'modern') ; ?></a> |
        <?php osc_reset_static_pages() ; ?>
        <?php while( osc_has_static_pages() ) { ?>
            <a href="<?php echo osc_static_page_url() ; ?>"><?php echo osc_static_page_title() ; ?></a> |
        <?php } ?>
        <?php _e('This website is proudly using the <a title="OSClass web" href="http://osclass.org/">open source classifieds</a> software <strong>OSClass</strong>', 'modern'); ?>.
    </div>
</div>