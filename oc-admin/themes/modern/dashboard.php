<?php include 'parts/header.php'; ?>
<div id="content-head"><h1>Dashboard</h1></div>
<div id="content-page">
<?php
/*******
*******
*******
*******/ ?>
                $numUsers            = __get("numUsers") ;
    $numAdmins           = __get("numAdmins") ;
    $numItems            = __get("numItems") ;
    $numItemsSpam        = __get("numItemsSpam") ;
    $numItemsBlock       = __get("numItemsBlock") ;
    $numItemsInactive    = __get("numItemsInactive") ;
    $numItemsPerCategory = __get("numItemsPerCategory") ;
    $newsList            = __get("newsList") ;
    $comments            = __get("comments") ;
                <?php osc_show_flash_message('admin') ; ?>
                <!-- dashboard -->
                <div class="main-page">
                    <!-- dashboard boxes -->
                    <div class="sortable_div">
                        <div class="float50per">
                        <div class="latest-items ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('Listings by category') ; ?></h3>
                            <div class="ui-state-body">
                                <?php if( !empty($numItemsPerCategory) ) { ?>
                                <ul>
                                    <?php foreach($numItemsPerCategory as $c) { ?>
                                    <li>
                                        <a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $c['pk_i_id'] ; ?>"><?php echo $c['s_name'] ; ?></a>
                                        <?php echo "(" . $c['i_num_items'] . "&nbsp;" . ( ( $c['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ) . ")" ; ?>
                                        <ul>
                                        <?php foreach($c['categories'] as $subc) {?>
                                            <li>
                                                <a href="<?php echo osc_admin_base_url(true); ?>?page=items&amp;catId=<?php echo $subc['pk_i_id'];?>"><?php echo $subc['s_name'] ; ?></a>
                                                <?php echo "(" . $subc['i_num_items'] . " " . ( ( $subc['i_num_items'] == 1 ) ? __('Listing') : __('Listings') ) . ")" ; ?>
                                            </li>
                                        <?php }?>
                                        </ul>
                                    </li>
                                    <?php }?>
                                </ul>
                                <?php } else { ?>
                                    <?php _e("There aren't any uploaded listing yet") ; ?>
                                <?php } ?>
                            </div>
                        </div>
                        </div>
                        <div class="float50per">

                        <div class="dashboard-statistics ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('Statistics'); ?></h3>
                            <div class="ui-state-body">
                                <ul>
                                    <li><?php printf( __('Number of listings: %d'), (int) $numItems ) ; ?></li>
                                    <li><?php printf( __('Number of public users: %d'), (int) $numUsers ) ; ?></li>
                                    <li><?php printf( __('Number of administrators: %d'), $numAdmins ) ; ?></li>
                                    
                                    <li><?php printf( __('Number of listings marked as spam: %d'), $numItemsSpam) ; ?></li>
                                    <li><?php printf( __('Number of listings marked as blocked: %d'), $numItemsBlock) ; ?></li>
                                    <li><?php printf( __('Number of listings marked as inactive: %d'), $numItemsInactive ) ; ?></li>
                                </ul>
                            </div>
                        </div>
                        </div>
                        <div class="float50per">
                        <div class="latest-comments ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('Latest comments') ; ?></h3>
                            <div class="ui-state-body">
                                <?php if (count($comments) > 0) { ?>
                                <ul>
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
                        <div class="float50per">
                        <div class="latest-news ui-dialog ui-corner-all">
                            <h3 class="ui-dialog-titlebar"><?php _e('Latest news from OSClass') ; ?></h3>
                            <div class="ui-state-body">
                            <?php if( is_array($newsList) ) { ?>
                                <ul>
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
                        <div class="clear"></div>
                    </div>
                    <!-- /dashboard boxes -->
                </div>
<?php /******
*************
*************
*************
********/ ?>
</div>
<?php include 'parts/footer.php'; ?>