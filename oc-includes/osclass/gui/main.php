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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()); ?>">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
        <meta name="robots" content="index, follow" />
        <meta name="googlebot" content="index, follow" />
    </head>
    <body>
        <div class="container">
            <?php osc_current_web_theme_path('header.php') ; ?>
            <div id="form_publish">
                <?php osc_current_web_theme_path('inc.search.php') ; ?>
            </div>
            <div class="content home">
                <div id="main">
                    <?php
                        $total_categories   = osc_count_categories() ;
                        $col1_max_cat       = ceil($total_categories/3);
                        $col2_max_cat       = ceil(($total_categories-$col1_max_cat)/2);
                        $col3_max_cat       = $total_categories-($col1_max_cat+$col2_max_cat);
                    ?>
                    <div class="categories <?php echo 'c' . $total_categories ; ?>">
                        <?php osc_goto_first_category() ; ?>
                        <?php
                            $i      = 1;
                            $x      = 1;
                            $col    = 1;
                            if(osc_count_categories () > 0) {
                                echo '<div class="col c1">';
                            }
                        ?>
                        <?php while ( osc_has_categories() ) { ?>
                            <div class="category">
                                <h1><strong><a class="category <?php echo osc_category_slug() ; ?>" href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</span></strong></h1>
                                <?php if ( osc_count_subcategories() > 0 ) { ?>
                                    <ul>
                                        <?php while ( osc_has_subcategories() ) { ?>
                                            <li><a class="category <?php echo osc_category_slug() ; ?>" href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</span></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                            <?php
                                if (($col==1 && $i==$col1_max_cat) || ($col==2 && $i==$col2_max_cat) || ($col==3 && $i==$col3_max_cat)) {
                                    $i = 1;
                                    $col++;
                                    echo '</div>';
                                    if($x < $total_categories) {
                                        echo '<div class="col c'.$col.'">';
                                    }
                                } else {
                                    $i++ ;
                                }
                                $x++ ;
                            ?>
                        <?php } ?>
                   </div>

                   <div class="latest_ads">
                        <h1><strong><?php _e('Latest Items', 'modern') ; ?></strong></h1>
                        <?php if( osc_count_latest_items() == 0) { ?>
                            <p class="empty"><?php _e('No Latest Items', 'modern') ; ?></p>
                        <?php } else { ?>
                            <table border="0" cellspacing="0">
                                 <tbody>
                                    <?php $class = "even"; ?>
                                    <?php while ( osc_has_latest_items() ) { ?>
                                        <tr class="<?php echo $class ; ?>">
                                            <?php if( osc_images_enabled_at_items() ) { ?>
                                             <td class="photo">
                                                <?php if( osc_count_item_resources() ) { ?>
                                                    <a href="<?php echo osc_item_url() ; ?>">
                                                        <img src="<?php echo osc_resource_thumbnail_url() ; ?>" width="75px" height="56px" title="" alt="" />
                                                    </a>
                                                <?php } else { ?>
                                                    <img src="<?php echo osc_current_web_theme_url('images/no_photo.gif') ; ?>" alt="" title=""/>
                                                <?php } ?>
                                             </td>
                                            <?php } ?>
                                             <td class="text">
                                                 <h3><a href="<?php echo osc_item_url() ; ?>"><?php echo osc_item_title() ; ?></a></h3>
                                                 <p><strong><?php if( osc_price_enabled_at_items() ) { echo osc_item_formated_price() ; ?> - <?php } echo osc_item_city(); ?> (<?php echo osc_item_region();?>) - <?php echo osc_format_date(osc_item_pub_date()); ?></strong></p>
                                                 <p><?php echo osc_highlight( strip_tags( osc_item_description() ) ) ; ?></p>
                                             </td>                                       
                                         </tr>
                                        <?php $class = ($class == 'even') ? 'odd' : 'even' ; ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php if( osc_count_latest_items() == osc_max_latest_items() ) { ?>
                                <p class="see_more_link"><a href="<?php echo osc_search_show_all_url();?>"><strong><?php _e("See all offers", 'modern'); ?> &raquo;</strong></a></p>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div id="sidebar">
                    <div class="navigation">
                        <?php if(osc_count_list_cities()>0) {?>
                        <div class="box location">
                            <h3><strong><?php _e("Location", 'modern'); ?></strong></h3>
                            <ul>
                            <?php while(osc_has_list_cities()) { ?>
                                <li><a href="<?php echo osc_search_url(array('sCity' => osc_list_city_name()));?>"><?php echo osc_list_city_name();?></a> <em>(<?php echo osc_list_city_items();?>)</em></li>
                            <?php } ?>
                            </ul>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php osc_current_web_theme_path('footer.php') ; ?>
        </div>
        <?php osc_show_flash_message() ; ?>
        <?php osc_run_hook('footer'); ?>
    </body>
</html>