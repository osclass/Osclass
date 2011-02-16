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
<?php
    $item = $this->_get('item') ;
    $aUser = $this->_get('aUser') ;
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
                <?php include("inc.search.php"); ?>
                <strong class="publish_button"><a href="<?php echo osc_item_post_url($catId) ; ?>">Publish your ad for free</a></strong>
            </div>

            <div class="content item">
                <div id="item_head">
                    <div class="inner">
                        <h1><span class="price"><?php echo osc_format_price($item); ?></span> <strong><?php echo $item['s_title']; ?></strong></h1>

                        <p id="report">
                            <strong><?php echo __('Mark as '); ?></strong> 
                            <span>
                                <a id="item_spam" href="<?php echo osc_base_url(); ?>?page=item&action=mark&amp;as=spam&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('spam'); ?></a>
                                <a id="item_bad_category" href="<?php echo osc_base_url(); ?>?page=item&action=mark&amp;as=badcat&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('bad category'); ?></a>
                                <a id="item_repeated" href="<?php echo osc_base_url(); ?>?page=item&action=mark&amp;as=repeated&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('repeated'); ?></a>
                                <a id="item_expired" href="<?php echo osc_base_url(); ?>?page=item&action=mark&amp;as=expired&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('expired'); ?></a>
                                <a id="item_offensive" href="<?php echo osc_base_url(); ?>?page=item&action=mark&amp;as=offensive&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('offensive'); ?></a>
                            </span>
                        </p>
                    </div>
                </div>

                <div id="main">

                    <div id="type_dates">
                        <strong>For Rent</strong>
                        <em class="publish"><?php echo date("d/m/Y", strtotime($a['dt_pub_date'])); ?></em>
                        <em class="update"><?php echo date("d/m/Y", strtotime($a['dt_mod_date'])); ?></em>
                    </div>

                    <ul id="item_location">
                        <?php if($item['s_country']!=""): ?><li>Country: <strong><?php echo $item['s_country']; ?></strong></li><?php endif ?>
                        <?php if($item['s_region']!=""): ?><li>Region: <strong><?php echo $item['s_region']; ?></strong></li><?php endif ?>
                        <?php if($item['s_city']!=""): ?><li>City: <strong><?php echo $item['s_city']; ?></strong></li><?php endif ?>
                        <?php if($item['s_city_area']!=""): ?><li>City area: <strong><?php echo $item['s_city_area']; ?></strong></li><?php endif ?>
                        <?php if($item['s_address']!=""): ?><li>Address: <strong><?php echo $item['s_address']; ?></strong></li><?php endif ?>
                    </ul>

                    <div id="description">
                        <?php 
                        $locales = Locale::newInstance()->listAllEnabled();
                        if(count($locales)==1) 
                            {
                                $locale = $locales[0];
                                ?>
                                <p>
                                <?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
                                </p>
                            <?php 
                        } 
                        else { 
                            ?>
                            <?php foreach($locales as $locale) {?>
                                <h3><?php echo $locale['s_name']; ?>:</h3>
                                <?php echo  @$item['locale'][$locale['pk_c_code']]['s_description']; ?>
                            <?php }; ?>
                        <?php }; ?>

                        <p class="contact_button">
                            <strong><a href="#contact"><?php echo __('Contact seller'); ?></a></strong>
                        </p>
                    </div>

                    <!-- plugins -->
                    <?php osc_run_hook('item_detail', $item); ?>
                    <?php osc_run_hook('location'); ?>


                    <?php if(osc_comments_enabled()) : ?>
                    <div id="comments">
                        <h2><?php echo __('Comments'); ?></h2>
                        <?php if(isset($comments) && count($comments)): ?>
                        <div class="comments_list">
                            <?php foreach($comments as $c): ?>
                                <div class="comment">
                                    <h3><strong><?php echo $c['s_title']; ?></strong> <em>by <?php echo $c['s_author_name']; ?>::</em></h3>
                                    <p><?php echo  $c['s_body'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <form action="<?php echo osc_base_url(true); ?>" method="post">
                        <fieldset>
                            <h3><?php echo __('Leave your comment (spam and offensive messages will be removed)'); ?></h3>
                            <input type="hidden" name="action" value="add_comment" />
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />
                            <label for="authorName"><?php echo __('Your name:'); ?></label> <input type="text" name="authorName" id="authorName" /><br />
                            <label for="authorEmail"><?php echo __('Your email:'); ?></label> <input type="text" name="authorEmail" id="authorEmail" /><br />
                            <label for="title"><?php echo __('Title:'); ?></label><br /><input type="text" name="title" id="title" /><br />
                            <label for="body"><?php echo __('Comment:'); ?></label><br /><textarea name="body" id="body" rows="5" cols="40"></textarea><br />
                            <button type="submit"><?php echo __('Send comment'); ?></button>
                        </fieldset>
                        </form>
                    </div>
                    <?php endif; ?>

                    <div id="useful_info">
                        <h2><?php echo __('Helpful information'); ?></h2>
                        <ul>
                            <li><?php echo __('Avoid scams by dealing locally or paying with PayPal.'); ?></li>
                            <li><?php echo __('Never pay with Western Union, Moneygram or other anonymous payment services.'); ?></li>
                            <li><?php echo __("Don't buy or sell outside of your country. Don't accept cashier cheques from outside your country."); ?></li>
                            <li><?php echo __('This site is never involved in any transaction, and does not handle payments, shipping, guarantee transactions, provide escrow services, or offer "buyer protection" or "seller certification".'); ?></li>
                        </ul>
                    </div>
                </div>

                <div id="sidebar">
                    <div id="photos">
                        <?php if(count($resources)): ?>
                            <?php foreach($resources as $r): ?>
                                <img src="<?php echo osc_base_path(); ?>/oc-content/uploads/<?php echo $r['pk_i_id']; ?>.png" />
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <!--
                        <div class="main_photo">
                            <a href="#"><img src="TMP_PHOTOS/item_big_1.jpg" /><strong>+ enlarge picture</strong></a>
                        </div>
                        <div class="mini_photos">
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_1.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_2.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_3.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_4.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_1.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_2.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_3.jpg" /></span></a>
                            <a href="TMP_PHOTOS/item_big_2.jpg"><span><img src="TMP_PHOTOS/item_4.jpg" /></span></a>
                        </div>
                        -->
                    </div>

                    <div id="contact">
                        <h2>Contact publisher</h2>
                        <form action="item.php" method="post" onsubmit="return validate_contact();">
                        <fieldset>
                            <h3><?php echo $item['s_contact_name']; ?></h3>
                            <?php if($aUser['s_phone_mobile'] != ''):?>
                            <p class="phone">Tel.: <?php echo $aUser['s_phone_mobile']; ?></p>
                            <?php endif;?>
                            <label for="yourName"><?php _e('Your name'); ?> <?php _e('(optional)'); ?>:</label><input type="text" name="yourName" value="" id="yourName" />
                            <label for="yourEmail"><?php _e('Your email address'); ?>:</label><input type="text" name="yourEmail" value="" id="yourEmail" />
                            <label for="phoneNumber"><?php _e('Phone number'); ?>:</label><input type="text" name="phoneNumber" value="" id="phoneNumber" />
                            <label for="message"><?php _e('Message'); ?>:</label><textarea name="message" rows="8" cols="30"></textarea>
                            <input type="hidden" name="action" value="contact_post" />
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>" />
                            <button type="submit"><?php _e('Send message'); ?></button>
                        </fieldset>
                        </form>
                    </div>

                    <script type="text/javascript">
                        function validate_contact() {
                            email = $("#yourEmail");

                            var pattern=/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-])+\.([a-zA-Z])+([a-zA-Z])+/;
                            var num_error = 0;

                            if(!pattern.test(email.value)){
                                email.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(message.val().length < 1) {
                                message.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(num_error > 0) {
                                return false;
                            }

                            return true;
                        }
                    </script>

                    <!--
                    VER QUE HACEMOS CON ESTO    
                    <div id="item_contact_seller"><a href="<?php echo WEB_PATH; ?>/item.php?action=contact&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('Contact seller'); ?></a></div>
                    <div id="item_send_friend"><a href="<?php echo WEB_PATH; ?>/item.php?action=send_friend&amp;id=<?php echo $item['pk_i_id']; ?>"><?php echo __('Send to a friend'); ?></a></div>
                    -->
                </div>
            </div>
        </div>
        <?php $this->osc_print_footer() ; ?>
    </body>
</html>