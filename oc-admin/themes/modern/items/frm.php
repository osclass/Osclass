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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>
        <?php $this->osc_print_header() ; ?>

        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{display:none;}<\/style>');
            $(document).ready(function(){
                $("#userId").change(function(){
                    if($(this).val()=='') {
                        $("#contact_info").show();
                    } else {
                        $("#contact_info").hide();
                    }
                });
                if($($("#userId")).val()=='') {
                    $("#contact_info").show();
                } else {
                    $("#contact_info").hide();
                }
            });
        </script>
        <?php ItemForm::location_javascript(); ?>
        <div id="content">
            <div id="separator"></div>

            <?php include_once osc_current_admin_theme_path() . 'include/backoffice_menu.php'; ?>

            <div id="right_column">
                <div id="home_header" style="margin-left: 40px;"><h2><?php if(isset($new_item) && $new_item==TRUE) { _e('New item');} else { _e('Update your item');}; ?></h2></div>
                <div align="center">
                    <div id="add_item_form" class="item-form">
                        <form action="items.php" method="post" enctype="multipart/form-data">
                            <?php if(isset($new_item) && $new_item==TRUE) { ?>
                                <input type="hidden" name="action" value="post_item" />
                            <?php } else { ?>
                                <input type="hidden" name="action" value="item_edit_post" />
                                <input type="hidden" name="id" value="<?php echo $item['pk_i_id'];?>" />
                                <input type="hidden" name="secret" value="<?php echo $item['s_secret'];?>" />
                            <?php }; ?>
                            <div class="user-post">
                                <h2><?php _e('User'); ?></h2>
                                <?php _e('Item posted by'); ?>&nbsp;<?php ItemForm::user_select($users, $item, __('Non-registered user')); ?>
                                <div  id="contact_info">
                                    <label for="contactName"><?php _e('Name'); ?></label>
                                    <?php ItemForm::contact_name_text($item) ; ?><br/>
                                    <label for="contactEmail"><?php _e('E-Mail'); ?></label>
                                    <?php ItemForm::contact_email_text($item); ?>
                                </div>
                            </div>
                            <h2>
                                <?php _e('General Information'); ?>
                            </h2>
                            <label for="catId">
                                <?php _e('Category') ?>:
                                <?php ItemForm::category_select($categories, $item); ?>
                            </label>

                            <?php ItemForm::multilanguage_title_description($locales, $item); ?>

                            <?php if(osc_price_enabled_at_items()) { ?>
                                <div>
                                    <h2><?php _e('Price'); ?></h2>
                                    <?php ItemForm::price_input_text($item); ?>
                                    <?php ItemForm::currency_select($currencies, $item); ?>
                                </div>
                            <?php } ?>

                            <?php if(osc_images_enabled_at_items()) { ?>
                                <div>
                                    <script type="text/javascript">
                                        var photoIndex = 0;
                                        function gebi(id) { return document.getElementById(id); }
                                        function ce(name) { return document.createElement(name); }
                                        function re(id) {
                                            var e = gebi(id);
                                            e.parentNode.removeChild(e);
                                        }
                                        function addNewPhoto() {
                                            var id = 'p-' + photoIndex++;

                                            var i = ce('input');
                                            i.setAttribute('type', 'file');
                                            i.setAttribute('name', 'photos[]');

                                            var a = ce('a');
                                            a.style.fontSize = 'x-small';
                                            a.setAttribute('href', '#');
                                            a.setAttribute('divid', id);
                                            a.onclick = function() { re(this.getAttribute('divid')); return false; }
                                            a.appendChild(document.createTextNode('<?php _e('Remove'); ?>'));

                                            var d = ce('div');
                                            d.setAttribute('id', id);

                                            d.appendChild(i);
                                            d.appendChild(a);

                                            gebi('photos').appendChild(d);
                                        }

                                        $(document).ready(function() {
                                            $('a.delete').click(function(e) {
                                                e.preventDefault();
                                                var parent = $(this).parent();
                                                $.ajax({
                                                    type: 'get',
                                                    url: 'items.php',
                                                    data: 'action=deleteResource&id='+parent.attr('id')+'&fkid='+parent.attr('fkid')+'&name='+parent.attr('name'),
                                                    success: function() {
                                                        parent.slideUp(300,function() {
                                                            parent.remove();
                                                        });
                                                    }
                                                });
                                            });
                                        });
                                    </script>

                                    <?php _e('Photos') ; ?><br />
                                    <div id="photos">
                                        <?php foreach($resources as $_r) {?>
                                            <div id="<?php echo $_r['pk_i_id'];?>" fkid="<?php echo $_r['fk_i_item_id'];?>" name="<?php echo $_r['s_name'];?>">
                                                <img src="../<?php echo $_r['s_path'];?>" /><a onclick=\"javascript:return confirm('<?php _e('This action can not be undone. Are you sure you want to continue?'); ?>')\" href="items.php?action=deleteResource&id=<?php echo $_r['pk_i_id'] ; ?>&fkid=<?php echo $_r['fk_i_item_id'] ; ?>&name=<?php echo $_r['s_name'] ; ?>" class="delete"><?php _e('Delete'); ?></a>
                                            </div>
                                        <?php } ?>
                                        <div>
                                            <input type="file" name="photos[]" /> (<?php _e('optional') ; ?>)
                                        </div>
                                    </div>
                                    <a style="font-size: small;" href="#" onclick="addNewPhoto(); return false;"><?php _e('Add new photo') ; ?></a>
                                </div>
                            <?php } ?>

                            <div class="location-post">
                                <!-- location info -->
                                <h2><?php _e('Location'); ?></h2>
                                <dl>
                                    <dt><?php _e('Country'); ?></dt>
                                    <dd><?php ItemForm::country_select($countries, $item) ; ?></dd>
                                    <dt><?php _e('Region'); ?></dt>
                                    <dd><?php ItemForm::region_select($regions, $item) ; ?></dd>
                                    <dt><?php _e('City'); ?></dt>
                                    <dd><?php ItemForm::city_select($cities, $item) ; ?></dd>
                                    <dt><?php _e('City area'); ?></dt>
                                    <dd><?php ItemForm::city_area_text($item) ; ?></dd>
                                    <dt><?php _e('Address'); ?></dt>
                                    <dd><?php ItemForm::address_text($item) ; ?></dd>
                                </dl>
                            </div>

                            <?php if(isset($new_item) && $new_item==TRUE) {
                                    ItemForm::plugin_post_item($categories);
                                } else {
                                    osc_run_hook('item_edit', $item);
                                };
                            ?>
                            <div class="clear"></div>
                            <div align="center" style="margin-top: 30px; padding: 20px; background-color: #eee;">
                                <button type="button" onclick="window.location='items.php';" ><?php _e('Cancel'); ?></button>
                                <button type="submit"><?php if(isset($new_item) && $new_item==TRUE) { _e('Add item');} else { _e('Save');}; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php $this->osc_print_footer() ; ?>

    </body>

</html>