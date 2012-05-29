<?php
    /**
     * OSClass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2010 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    $numUsers            = __get("numUsers") ;
    $numAdmins           = __get("numAdmins") ;

    $numItems            = __get("numItems") ;
    $numItemsSpam        = __get("numItemsSpam") ;
    $numItemsBlock       = __get("numItemsBlock") ;
    $numItemsInactive    = __get("numItemsInactive") ;
    $numItemsPerCategory = __get("numItemsPerCategory") ;
    $newsList            = __get("newsList") ;
    $comments            = __get("comments") ;
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="content-head">
    <h1 class="dashboard"><?php _e('Dashboard') ; ?></h1>
</div>
<?php osc_show_flash_message('admin') ; ?>
<div id="content-page">
<div id="dashboard">
<div class="grid-system">
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Listings by category') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php
                    $countEvent = 1;
                    if( !empty($numItemsPerCategory) ) { ?>
                    <table class="table" cellpadding="0" cellspacing="0">
                        <tbody>
                        <?php foreach($numItemsPerCategory as $c) { ?>
                            <tr<?php if($countEvent%2 == 0){ echo ' class="even"';} if($countEvent == 1){ echo ' class="table-first-row"';} ?>>
                                <td><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $c['pk_i_id'] ; ?>"><?php echo $c['s_name'] ; ?></a></td>
                                <td><?php echo "(" . $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ) . ")" ; ?></td>
                            </tr>
                            <?php foreach($c['categories'] as $subc) {?>
                                <tr<?php if($countEvent%2 == 0){ echo 'class="even"';} ?>>
                                    <td class="children-cat"><a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name'] ; ?></a></td>
                                    <td><?php echo "(" . $subc['i_num_items'] . " " . ( ( $subc['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ) . ")" ; ?></td>
                                </tr>
                            <?php
                            $countEvent++;
                            }
                            ?>
                        <?php
                        $countEvent++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <?php } else { ?>
                        <?php _e("There aren't any uploaded listing yet") ; ?>
                    <?php } ?>  
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Statistics'); ?> <select class="widget-box-selector"><option>New items</option><option>New comments</option></select></h3></div>
                <div class="widget-box-content">
                    <table class="table" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr class="table-first-row">
                                <td><?php _e('Number of listings') ; ?></td><td><?php echo (int) $numItems ; ?></td>
                            </tr>
                            <tr class="even">
                                <td><?php _e('Number of public users') ; ?></td><td><?php echo (int) $numUsers ; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Number of administrators') ; ?></td><td><?php echo (int) $numAdmins ; ?></td>
                            </tr>
                            <tr class="even">
                                <td><?php _e('Number of listings marked as spam') ; ?></td><td><?php echo (int) $numItemsSpam ; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e('Number of listings marked as blocked') ; ?></td><td><?php echo (int) $numItemsBlock ; ?></td>
                            </tr>
                            <tr class="even">
                                <td><?php _e('Number of listings marked as inactive') ; ?></td><td><?php echo (int) $numItemsInactive ; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-first-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Latest comments') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php if (count($comments) > 0) { ?>
                    <ul class="list-latests">
                        <?php foreach($comments as $c) { ?>
                        <li>
                            <strong><?php echo $c['s_author_name'] ; ?></strong> <?php _e('Commented on listing') ; ?> <em><a title="<?php echo $c['s_body'] ; ?>" target='_blank' href='<?php echo osc_base_url(true) . '?page=item&amp;id=' . $c['fk_i_item_id'] ; ?>' id='dt_link'><?php echo $c['s_title'] ; ?></a></em>
                        </li>
                        <?php } ?>
                    </ul>
                    <?php } else { ?>
                        <?php _e("There aren't any comments yet") ; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="grid-row grid-50">
        <div class="row-wrapper">
            <div class="widget-box">
                <div class="widget-box-title"><h3><?php _e('Latest news from OSClass') ; ?></h3></div>
                <div class="widget-box-content">
                    <?php if( is_array($newsList) ) { ?>
                        <ul class="list-latests">
                        <?php foreach ($newsList as $list) { ?>
                        <?php $new = ( strtotime($list['pubDate']) > strtotime('-1 week') ? true : false ) ; ?>
                            <li>
                                <a href="<?php echo $list['link'] ; ?>" target="_blank"><?php echo $list['title'] ; ?></a>
                                <?php if( $new ) { ?>
                                    <span style="color:red; font-size:10px; font-weight:bold;"><?php _e('new') ; ?></span>
                                <?php } ?>
                            </li>
                        <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <?php _e('Unable to fetch news from OSClass. Please try again later') ; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>