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

<form action="<?php echo osc_base_url(true) ; ?>" method="get" class="search" onsubmit="javascript:return doSearch();">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="sPattern"  id="query" value="<?php echo ( osc_search_pattern() != '' ) ? osc_search_pattern() : __("ie. PHP Programmer", 'modern') ; ?>" />

        <?php  if ( osc_count_categories() ) { ?>
            <?php osc_goto_first_category() ; ?>
            <select name="sCategory" id="sCategory">
                    <option value=""><?php _e("Select a category", 'modern') ; ?></option>
                    <?php while ( osc_has_categories() ) { ?>
                        <option value="<?php echo osc_category_id() ; ?>"><?php echo osc_category_name() ; ?></option>
                        <?php if ( osc_count_subcategories() > 0 ) { ?>
                            <?php while ( osc_has_subcategories() ) { ?>
                                <option class="pad" value="<?php echo osc_category_id() ; ?>"><?php echo osc_category_name() ; ?></option>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
            </select>
        <?php  } ?>

        <button type="submit"><?php _e('Search', 'modern') ; ?></button>
    </fieldset>
</form>
