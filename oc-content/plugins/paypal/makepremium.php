<?php

    if(osc_get_preference('allow_premium', 'paypal')) {
        // Load Item Information, so we could tell the user which item is he/she paying for
        $item = Item::newInstance()->findByPrimaryKey(Params::getParam('itemId'));
        if($item) {
            // Check if it's already payed or not
            if(!paypal_is_premium($item['pk_i_id'])) {
                // Item is not paid, continue
                $conn = getConnection();
                $ppl_category = $conn->osc_dbFetchResult("SELECT f_premium_cost FROM %st_paypal_prices WHERE fk_i_category_id = %d", DB_TABLE_PREFIX, $item['fk_i_category_id']);
                if($ppl_category && isset($ppl_category['f_premium_cost']) && $ppl_category['f_premium_cost']>0) {
                    $category_fee = $ppl_category['f_premium_cost'];
                } else {
                    $category_fee = osc_get_preference('default_premium_cost', 'paypal');
                }
                if($category_fee > 0) {
                ?>
                <h1><?php _e('Make the ad premium', 'paypal'); ?></h1>
                <div>
                    <div style="float:left; width: 50%;">
                        <label style="font-weight: bold;"><?php _e("Item's title", 'paypal'); ?>:</label> <?php echo $item['s_title']; ?><br/>
                        <label style="font-weight: bold;"><?php _e("Item's description", 'paypal'); ?>:</label> <?php echo $item['s_description']; ?><br/>
                    </div>
                    <div style="float:left; width: 50%;">
                        <?php _e("In order to make premium your ad , it's required to pay a fee", 'paypal'); ?>.<br/>
                        <?php echo sprintf(__("The current fee for this category is: %.2f %s", 'paypal'), $category_fee, osc_get_preference('currency', 'paypal')); ?><br/>
                        <?php paypal_button($category_fee, sprintf(__("Premium fee for item %d at %s", "paypal"), $item['pk_i_id'], osc_page_title()), $item['fk_i_user_id']."|".$item['pk_i_id']."|".$item['s_contact_email'], "201x".$item['fk_i_category_id']."x".$item['pk_i_id']); ?>
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
                    <h1><?php _e('There was an error', 'paypal'); ?></h1>
                    <div>
                        <p><?php _e("There's no need to pay the premium fee", 'paypal'); ?></p>
                    </div>
                    <?php
                }
            } else {
                // ITEM WAS ALREADY PAID! STOP HERE
                ?>
                <h1><?php _e('There was an error', 'paypal'); ?></h1>
                <div>
                    <p><?php _e('The item is already a premium ad', 'paypal'); ?></p>
                </div>
                <?php
            }
        } else {
            //ITEM DOES NOT EXIST! STOP HERE
            ?>
            <h1><?php _e('There was an error', 'paypal'); ?></h1>
            <div>
                <p><?php _e('The item doesn not exists', 'paypal'); ?></p>
            </div>
            <?php
        }
    } else {
        // NO NEED TO PAY AT ALL!
        ?>
        <h1><?php _e("There was an error", "paypal");?></h1>
        <br/>
        <div><p><?php _e("Premiums ads are not allowed", "paypal");?></p></div>
        <?php
    }

?>