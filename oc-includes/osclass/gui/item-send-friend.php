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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_web_theme_path('head.php') ; ?>
    </head>
    <body>
        
        <div class="container">

            <?php osc_current_web_theme_path('header.php') ; ?>

            <div class="content item">
                <div id="contact" class="inner">
                    <h2><?php _e('Send to a friend'); ?></h2>

                    <script type="text/javascript">
                        function validate_form() {
                            email = $("#yourEmail");
                            friendemail = $("#friendEmail");
                            yourname = $("#yourName");
                            friendname = $("#friendName");
                            message = $("#message");

                            var pattern=/^([a-zA-Z0-9_\.-])+@([a-zA-Z0-9_\.-]+)\.([a-zA-Z]{2,3})$/;
                            var num_error = 0;

                            if(!pattern.test(email.val())){
                                email.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(!pattern.test(friendemail.val())){
                                friendemail.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(yourname.val().length<=0){
                                yourname.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(friendname.val().length<=0){
                                friendname.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(message.val().length < 1) {
                                message.css('border', '1px solid red');
                                num_error = num_error + 1;
                            }

                            if(num_error > 0) {
                                return false;
                            } else {
                                            document.forms['send-friend'].submit();
                                return true;
                                    }
                        }

                        $(document).ready(function(){
                            $("#yourEmail").focus(function(){
                                $(this).css('border', '');
                            });
                            $("#friendEmail").focus(function(){
                                $(this).css('border', '');
                            });
                            $("#yourName").focus(function(){
                                $(this).css('border', '');
                            });
                            $("#friendName").focus(function(){
                                $(this).css('border', '');
                            });
                            $("#message").focus(function(){
                                $(this).css('border', '');
                            });
                        });
                    </script>

                    <form id="send-friend" name="send-friend" action="<?php echo osc_base_url(true); ?>" method="post">
                        <fieldset>
                            <input type="hidden" name="action" value="send_friend_post" />
                            <input type="hidden" name="page" value="item" />
                            <input type="hidden" name="id" value="<?php echo $item['pk_i_id']; ?>" />

                            <label><?php _e('Item'); ?>: <a href="<?php echo osc_item_url($item); ?>"><?php echo $item['s_title']; ?></a></label><br/>
                            <label for="yourName"><?php _e('Your name'); ?></label><input type="text" name="yourName" id="yourName" /><br/>
                            <label for="yourEmail"><?php _e('Your email address'); ?></label><input type="text" name="yourEmail" id="yourEmail" /><br/>
                            <label for="friendName"><?php _e("Your friend's name"); ?></label><input type="text" name="friendName" id="friendName" /><br/>
                            <label for="friendEmail"><?php _e("Your friend's email address"); ?></label><input type="text" name="friendEmail" id="friendEmail" /><br/>
                            <label for="message"><?php _e('Message'); ?></label><textarea cols="50" name="message" id="message"></textarea><br/>

                            <input onclick="validate_form()" type="button" value="<?php _e('Send message'); ?>" />
                        </fieldset>
                    </form>

                </div>

            </div>

            <?php osc_current_web_theme_path('footer.php') ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>

    </body>

</html>