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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>

        <div class="container">

            <?php $this->osc_print_header() ; ?>

            <div id="form_publish">
                <?php include("inc.search.php") ; ?>
                <strong class="publish_button"><a href="<?php echo osc_item_post_url() ; ?>"><?php _e('Publish your ad for free') ; ?></a></strong>
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
                        <?php
                            $i      = 1;
                            $x      = 1;
                            $col    = 1;
                            echo '<div class="col c1">';
                        ?>

                        <?php osc_goto_first_category() ; ?>
                        
                        <?php while ( osc_has_categories() ) { ?>
                            <div class="category">
                                <h1><strong><a href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</span></strong></h1>

                                <?php if ( osc_count_subcategories() > 0 ) { ?>
                                    <ul>
                                        <?php while ( osc_has_subcategories() ) { ?>
                                            <li><a href="<?php echo osc_search_category_url() ; ?>"><?php echo osc_category_name() ; ?></a> <span>(<?php echo osc_category_total_items() ; ?>)</span></li>
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
                        <h1><strong><?php _e('Latest Items') ; ?></strong></h1>
                        
                        <?php if( osc_count_latest_items() == 0) { ?>

                            <p class="empty"><?php _e('No Latest Items') ; ?></p>
                            
                        <?php } else { ?>

                            <table border="0" cellspacing="0">
                                 <tbody>
                                    <?php $class = "even"; ?>
                                    <?php while ( osc_has_latest_items() ) { ?>
                                        <tr class="<?php echo $class ; ?>">
                                             <td class="photo">
                                                <?php if( osc_count_item_resources() ) { ?>

                                                    <a href="<?php echo osc_item_url() ; ?>"><img src="<?php echo osc_resource_thumbnail_url() ; ?>" /></a>
                                                
                                                <?php } else { ?>

                                                    <img src="<?php echo $this->osc_get_theme_url('images/no_photo.gif') ; ?>" />

                                                <?php } ?>
                                             </td>
                                             <td class="text">
                                                 <h3><a href="<?php echo osc_item_url() ; ?>"><?php echo osc_item_title() ; ?></a></h3>
                                                 <!--
                                                     <h4><strong>Full time</strong> <span>|</span> <strong>Web development</strong></h4>
                                                 -->
                                                 <p><?php echo osc_item_description() ; ?></p>
                                             </td>
                                            <td class="price"><strong><?php echo osc_item_formated_price() ; ?></strong></td>
                                         </tr>
                                        <?php $class = ($class == 'even') ? 'odd' : 'even' ; ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <?php if( osc_count_latest_items() == osc_max_latest_items() ) { ?>
                                <p class="see_more_link"><a href="#"><strong><?php _e("See all offers");?> &raquo;</strong></a></p>
                            <?php } ?>

                        <?php } ?>
                    </div>
                </div>

                <div id="sidebar">

                    <div class="navigation">

                        <div class="box location">
                            <h3><strong><?php _e("Location");?></strong></h3>
                            <ul>
                            <?php $regions = osc_search_list_regions();
                                foreach($regions as $region) { ?>
                                <li><a href="<?php echo osc_search_url(array('sRegion' => $region['region_name']));?>"><?php echo $region['region_name'];?></a> <em>(<?php echo $region['items'];?>)</em></li>
                            <?php } ?>
                            </ul>
                        </div>

                    </div>

                </div>

            </div>

            <?php $this->osc_print_footer() ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>

    </body>
    
</html>
