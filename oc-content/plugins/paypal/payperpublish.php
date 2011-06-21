<?php
    if(osc_get_preference('pay_per_post', 'paypal')) {
        // Load Item Information, so we could tell the user which item is he/she paying for
        $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
        if($item) {
            // Check if it's already payed or not
            $conn = getConnection();
            $paid = $conn->osc_dbFetchResult("SELECT b_paid FROM %st_paypal_publish WHERE fk_i_item_id = %d", DB_TABLE_PREFIX, Params::getParam("itemId"));
            if(!$paid || (isset($paid) && $paid['b_paid'] == 0)) {
                // Item is not paid, continue
                $ppl_category = $conn->osc_dbFetchResult("SELECT f_publish_cost FROM %st_paypal_prices WHERE fk_i_category_id = %d", DB_TABLE_PREFIX, $item['fk_i_category_id']);
                if($ppl_category && isset($ppl_category['f_publish_cost'])) {
                    $category_fee = $ppl_category['f_publish_cost'];
                } else {
                    $category_fee = osc_get_preference('default_publish_cost', 'paypal');
                }
                if($category_fee > 0) {
                ?>

                <h1><?php _e('Continue the publish process', 'paypal'); ?></h1>
                <div>
                    <div style="float:left; width: 50%;">
                        <label style="font-weight: bold;"><?php _e("Item's title", 'paypal'); ?>:</label> <?php echo $item['s_title']; ?><br/>
                        <label style="font-weight: bold;"><?php _e("Item's description", 'paypal'); ?>:</label> <?php echo $item['s_description']; ?><br/>
                    </div>
                    <div style="float:left; width: 50%;">
                        <?php _e("In order to make visible your ad to other users, it's required to pay a fee", 'paypal'); ?>.<br/>
                        <?php echo sprintf(__('The current fee for this category is: %.2f %s', 'paypal'), $category_fee, osc_get_preference('currency', 'paypal')); ?><br/>
                        <?php paypal_button($category_fee, sprintf(__('Publish fee for item %d at %s', 'paypal'), $item['pk_i_id'], osc_page_title()), $item['fk_i_user_id']."|".$item['pk_i_id']."|".$item['s_contact_email'], "101x".$item['fk_i_category_id']."x".$item['pk_i_id']); ?>
                    </div>
                    <div style="clear:both;"></div>
                    <div name="result_div" id="result_div"></div>
                    <script type="text/javascript">
                        var rd = document.getElementById("result_div");
                    </script>
                </div>
                <?php
                } else {
                    // PRICE IS ZERO!
                    ?>
                    <h1><?php _e("There was an error", 'paypal'); ?></h1>
                    <div>
                        <p><?php _e("There's no need to pay the publish fee", 'paypal'); ?></p>
                    </div>
                    <?php
                }
            } else {
                // ITEM WAS ALREADY PAID! STOP HERE
                ?>
                <h1><?php _e('There was an error', 'paypal'); ?></h1>
                <div>
                    <p><?php _e('The publish fee is already paid', 'paypal'); ?></p>
                </div>
                <?php
            }
        } else {
            //ITEM DOES NOT EXIST! STOP HERE
            ?>
            <h1><?php _e('There was an error','paypal'); ?></h1>
            <div>
                <p><?php _e('The item doesn not exists', 'paypal'); ?></p>
            </div>
            <?php
        }
    } else {
        // NO NEED TO PAY AT ALL!
        ?>
        <h1><?php _e('There was an error', 'paypal'); ?></h1>
        <div>
            <p><?php _e("There's no need to pay the publish fee", 'paypal'); ?></p>
        </div>
        <?php
    }
?>