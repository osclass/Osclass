<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2014 OSCLASS
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

<?php
    // meta tag robots
    osc_add_hook('header','bender_nofollow_construct');
    bender_add_body_class('error not-found');
    osc_current_web_theme_path('header.php') ;
?>
<div class="flashmessage-404">
    <h1><?php _e("Sorry but I can't find the page you're looking for", 'bender') ; ?></h1>

    <p><?php _e("Let us help you, we have got a few tips for you to find it.", 'bender') ; ?></p>
    <ul>
        <li>
            <?php _e("<strong>Search</strong> for it:", 'bender') ; ?>
            <form action="<?php echo osc_base_url(true) ; ?>" method="get" class="search">
                <input type="hidden" name="page" value="search" />
                <fieldset class="main">
                    <input type="text" name="sPattern"  id="query" value="" />
                    <button type="submit" class="ui-button ui-button-middle"><?php _e('Search', 'bender') ; ?></button>
                </fieldset>
            </form>
        </li>
        <li><?php _e("<strong>Look</strong> for it in the most popular categories.", 'bender') ; ?>
            <div class="categories">
                <?php osc_goto_first_category() ; ?>
                <?php while ( osc_has_categories() ) { ?>
                        <h2><a class="category <?php echo osc_category_slug() ; ?>" href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</h2>
                        <?php if ( osc_count_subcategories() > 0 ) { ?>
                            <?php while ( osc_has_subcategories() ) { ?>
                                <?php if( osc_category_total_items() > 0 ) { ?>
                                    <h3><a class="category <?php echo osc_category_slug() ; ?>" href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</h3>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                <?php } ?>
           </div>
           <div class="clear"></div>
        </li>
    </ul>
</div>
<?php osc_current_web_theme_path('footer.php') ; ?>