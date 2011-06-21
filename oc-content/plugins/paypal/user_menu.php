<?php 
    $itemsPerPage = (Params::getParam('itemsPerPage') != '') ? Params::getParam('itemsPerPage') : 5;
    $page         = (Params::getParam('iPage') != '') ? Params::getParam('iPage') : 0;
    $total_items  = Item::newInstance()->countByUserIDEnabled($_SESSION['userId']);
    $total_pages  = ceil($total_items/$itemsPerPage);
    $items        = Item::newInstance()->findByUserIDEnabled($_SESSION['userId'], $page * $itemsPerPage, $itemsPerPage);

    View::newInstance()->_exportVariableToView('items', $items);
    View::newInstance()->_exportVariableToView('list_total_pages', $total_pages);
    View::newInstance()->_exportVariableToView('list_total_items', $total_items);
    View::newInstance()->_exportVariableToView('items_per_page', $itemsPerPage);
    View::newInstance()->_exportVariableToView('list_page', $page);
?>
            <div class="content user_account">
                <h1>
                    <strong><?php _e('User account manager', 'paypal') ; ?></strong>
                </h1>
                <div id="sidebar">
                    <?php echo osc_private_user_menu() ; ?>
                </div>
                <div id="main">
                    <h2><?php _e('Paypal & your items', 'paypal'); ?></h2>
                    <?php if(osc_count_items() == 0) { ?>
                        <h3><?php _e('You don\'t have any items yet', 'paypal'); ?></h3>
                    <?php } else { ?>
                        <?php while(osc_has_items()) { ?>
                                <div class="item" >
                                        <h3>
                                            <a href="<?php echo osc_item_url(); ?>"><?php echo osc_item_title(); ?></a>
                                        </h3>
                                        <p>
                                        <?php _e('Publication date', 'paypal') ; ?>: <?php echo osc_format_date(osc_item_pub_date()) ; ?><br />
                                        <?php _e('Price', 'paypal') ; ?>: <?php echo osc_format_price(osc_item_price()); ?>
                                        </p>
                                        <p class="options">
                                            <?php if(osc_get_preference("pay_per_post", "paypal")=="1") { ?>
                                                <?php if(paypal_is_paid(osc_item_id())) { ?>
                                                    <strong><?php _e('Paid!', 'paypal'); ?></strong>
                                                <?php } else { ?>
                                                    <strong><a href="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__)."payperpublish.php&itemId=".osc_item_id()); ?>"><?php _e('Pay for this item', 'paypal'); ?></a></strong>
                                                <?php }; ?>
                                            <?php }; ?>
                                            <?php if(osc_get_preference("pay_per_post", "paypal")=="1" && osc_get_preference("allow_premium", "paypal")=="1") { ?>
                                                <span>|</span>
                                            <?php }; ?>
                                            <?php if(osc_get_preference("allow_premium", "paypal")=="1") { ?>
                                                <?php if(paypal_is_premium(osc_item_id())) { ?>
                                                    <strong><?php _e('Already premium!', 'paypal'); ?></strong>
                                                <?php } else { ?>
                                                    <strong><a href="<?php echo osc_render_file_url(osc_plugin_folder(__FILE__)."makepremium.php&itemId=".osc_item_id()); ?>"><?php _e('Make premium', 'paypal'); ?></a></strong>
                                                <?php }; ?>
                                            <?php }; ?>
                                        </p>
                                        <br />
                                </div>
                        <?php } ?>
                        <br />
                        <div class="paginate">
                        <?php for($i = 0 ; $i < osc_list_total_pages() ; $i++) {
                            if($i == osc_list_page()) {
                                printf('<a class="searchPaginationSelected" href="%s">%d</a>', osc_render_file_url(osc_plugin_folder(__FILE__) . 'user_menu.php') . '?iPage=' . $i, ($i + 1));
                            } else {
                                printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', osc_render_file_url(osc_plugin_folder(__FILE__) . 'user_menu.php') . '?iPage='. $i, ($i + 1));
                            }
                        } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>