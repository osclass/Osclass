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
<script src="<?php echo osc_base_url() ; ?>/oc-includes/js/tabber-minimized.js"></script>
<link type="text/css" href="<?php echo osc_base_url() ; ?>/oc-includes/css/tabs.css" media="screen" rel="stylesheet" />
<div>
        <h2><?php echo $item['s_title']; ?></h2>
        <div class="itemContent">
        <div class="itemDate" >
        <?php _e('Price:'); ?> <?php echo osc_format_price($item) ; ?><br />
        <?php _e('Publication date:'); ?> <?php echo osc_format_date($item) ; ?>
        </div>
        <div>
<?php $locales = Locale::newInstance()->listAllEnabled();
if(count($locales)==1) {
$locale=$locales[0];?>
<p>
<?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
</p>
<?php }else { ?>
        				<div class="tabber">
<?php foreach($locales as $locale) {?>
<div class="tabbertab">
<h2><?php echo $locale['s_name']; ?></h2>

<p>
<?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
</p>
</div>
<?php }; ?>
</div>
<?php }; ?>
        </div>
        </div>
        <div class="itemContact">
            <a href="<?php echo osc_base_url() ; ?>/item.php?action=contact&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('Contact seller'); ?></a><br />
            <a href="<?php echo osc_base_url() ; ?>/item.php?action=send_friend&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('Send to a friend'); ?></a><br />
            <br />
            <div class="itemButtons" >
                <?php _e('Mark as'); ?>
                <a href="<?php echo osc_base_url() ; ?>/item.php?action=mark&amp;as=spam&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('spam'); ?></a>, 
                <a href="<?php echo osc_base_url() ; ?>/item.php?action=mark&amp;as=badcat&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('bad category'); ?></a>,
                <a href="<?php echo osc_base_url() ; ?>/item.php?action=mark&amp;as=offensive&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('offensive'); ?></a>,
                <a href="<?php echo osc_base_url() ; ?>/item.php?action=mark&amp;as=repeated&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('repeated'); ?></a>,
                <a href="<?php echo osc_base_url() ; ?>/item.php?action=mark&amp;as=expired&amp;id=<?php echo $item['pk_i_id']; ?>"><?php _e('expired'); ?></a>.
            </div>
        </div>
        <div style="clear: both;"></div>
</div>

<?php osc_run_hook('item_detail', $item) ; ?>

<?php osc_run_hooks('location') ; ?>

<?php if(count($resources)) { ?>
        <h3><?php _e('Images') ; ?></h3>
        
        <?php foreach($resources as $r) { ?>
            <img src="<?php echo osc_createResourceURL($r); ?>" style="width: 150px;" />
        <?php } ?>
<?php } ?>

<form action="<?php echo osc_base_url() ; ?>/item.php" method="post">
    <input type="hidden" name="action" value="add_comment" />
    <input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

    <div class="commentHolder" >
        <div class="commentHeader" ><?php _e('Comments'); ?></div>

        <?php if(osc_comments_enabled()) { ?>
            <div class="commentData" >
                <?php if(isset($comments) && count($comments)) { ?>
                    <?php foreach($comments as $c) { ?>
                        <div><p><i><?php echo $c['s_title'] ; ?></i> by <i><?php echo $c['s_author_name'] ; ?></i>:</p><p><?php echo  $c['s_body'] ; ?></p><hr></div>
                    <?php } ?>
                <?php } else { ?>
                    <?php _e('Be the first to comment on this item!'); ?>
                <?php } ?>
            </div>
            <div class="commentContent" >
                <p><?php _e('Leave your comment (spam and offensive messages will be removed)') ; ?></p>
                <p>
                    <label for="authorName"><?php _e('Your name'); ?>:</label> <input type="text" name="authorName" id="authorName" /><br />
                    <label for="authorEmail"><?php _e('Your email'); ?>:</label> <input type="text" name="authorEmail" id="authorEmail" /><br />
                </p>
                <p>
                    <label for="title"><?php _e('Title'); ?>:</label><br /><input type="text" name="title" id="title" /><br />
                    <label for="body"><?php _e('Comment'); ?>:</label><br /><textarea name="body" id="body" rows="3" cols="60"></textarea>
                </p>
                <p><input type="submit" value="<?php _e('Send comment') ; ?>" /></p>
            </div>

        <?php } else { ?>
                <div class="commentContent" >
                    <?php _e('Comments are not enabled.'); ?>
                </div>
        <?php } ?>
    </div>

</form>

<div class="helpHolder">
    <div class="helpHeader"><?php _e('Helpful information') ; ?></div>
    <div class="helpContent">
        <ul>
            <li><?php _e('Avoid scams by dealing locally or paying with PayPal.'); ?></li>
            <li><?php _e('Never pay with Western Union, Moneygram or other anonymous payment services.'); ?></li>
            <li><?php _e("Don't buy or sell outside of your country. Don't accept cashier check from outside your country."); ?></li>
            <li><?php _e('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification".'); ?></li>
        </ul>
    </div>
</div>


