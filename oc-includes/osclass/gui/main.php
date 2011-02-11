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

<?php $latestItems = Item::newInstance()->listLatest(10) ; ?>
<?php $catId = Params::getParam('catId') ; ?>
<?php $categories = $this->_get('categories') ; ?>

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
                <strong class="publish_button"><a href="<?php echo osc_item_post_url($catId) ; ?>">Publish your ad for free</a></strong>
            </div>

            <div class="content home">
                <div id="main">
                    <?php
                        $total_categories   = count($categories);
                        $col1_max_cat       = ceil($total_categories/3);
                        $col2_max_cat       = ceil(($total_categories-$col1_max_cat)/2);
                        $col3_max_cat       = $total_categories-($col1_max_cat+$col2_max_cat);
                    ?>
                    <div class="categories <?php echo "c".$total_categories; ?>">
                        <?php
                        $i      = 1;
                        $x      = 1;
                        $col    = 1;
                        echo '<div class="col c1">';
                        foreach($categories as $c) {
                            ?>
                            <div class="category">
                                <h1><strong><a href="<?php echo osc_search_category_url($c) ; ?>"><?php echo $c['s_name'] ; ?></a> <span>(<?php echo CategoryStats::newInstance()->getNumItems($c) ; ?>)</span></strong></h1>
                                <ul>
                                    <?php foreach($c['categories'] as $sc) { ?>
                                        <li><a href="<?php echo osc_search_category_url($sc) ; ?>"><?php echo $sc['s_name'] ; ?></a> <span>(<?php echo CategoryStats::newInstance()->getNumItems($sc) ; ?>)</span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php

                            if (($col==1 && $i==$col1_max_cat) || ($col==2 && $i==$col2_max_cat) || ($col==3 && $i==$col3_max_cat)) {
                                $i = 1;
                                $col++;
                                echo '</div>';
                                if($x < $total_categories) {
                                    echo '<div class="col c'.$col.'">';
                                }
                            }
                            else {
                                $i++ ;
                            }
                            $x++ ;
                        }
                        ?>
                   </div>

                   <div class="latest_ads">
                        <h1><strong><?php _e('Latest Items') ; ?></strong></h1>
                        <?php if(!isset($latestItems) || is_null($latestItems)) { ?>
                            <p class="empty"><?php _e('No Latest Items') ; ?></p>
                        <?php } else { ?>

                             <table border="0" cellspacing="0">
                                 <tbody>
                                    <?php $class = "even";
                                    foreach($latestItems as $i) { ?>
                                        <tr class="<?php echo $class; ?>">
                                             <td class="photo">
                                                 <?php if(osc_itemHasThumbnail($i)) { ?>
                                                    <a href="<?php echo osc_createItemURL($i); ?>"><img src="<?php echo osc_itemThumbnail($i); ?>" /></a>
                                                <?php } else { ?>
                                                    <img src="<?php echo osc_themeResource('images/no_photo.gif'); ?>" />
                                                <?php } ?>
                                             </td>
                                             <td class="text">
                                                 <h3><a href="<?php echo osc_createItemURL($i); ?>"><?php echo $i['s_title']; ?></a></h3>
                                                 <!--
                                                     <h4><strong>Full time</strong> <span>|</span> <strong>Web development</strong></h4>
                                                 -->
                                                 <p><?php echo strip_tags($i['s_description']); ?></p>
                                             </td>
                                             <td class="price"><strong><?php echo osc_formatPrice($i); ?></strong></td>
                                         </tr>
                                        <?php $class = ($class == 'even') ? 'odd' : 'even'; ?>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <p class="see_more_link"><a href="#"><strong>See all offers &raquo;</strong></a></p>
                        <?php } ?>
                    </div>
                </div>

                <div id="sidebar">

                    <div class="navigation">

                        <div class="box location">
                            <h3><strong>Location</strong></h3>
                            <ul>
                                <li><a href="#">Madrid</a> <em>(12.674)</em></li>
                                <li><a href="#">Barcelona</a> <em>(10.432)</em></li>
                                <li><a href="#">Sevilla</a> <em>(9.456)</em></li>
                                <li><a href="#">Valencia</a> <em>(7.503)</em></li>
                                <li><a href="#">Bilbao</a> <em>(7.552)</em></li>
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