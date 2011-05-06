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

    $categories = __get("categories");
    $last = end($categories);
    $last_id = $last['pk_i_id'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script src="<?php echo osc_current_admin_theme_url(); ?>js/vtip/vtip.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_current_admin_theme_url(); ?>js/vtip/css/vtip.css" />
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Categories'); ?></div>
        <style>
            .TableCategories ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
            .TableCategories li { margin: 5px; padding: 5px; }
        </style>
        <script type="text/javascript">
            $(function() {
                $( "#sortable" ).sortable({
                    revert: true,
                    stop: function(event, ui) { 
                        var list = '';
                        $('#sortable .category_div').each(function() {
                            list += $(this).attr('category_id') + ',';
                        });
                        $.ajax({
                            url: "<?php echo osc_admin_base_url(true)."?page=ajax&action=categories_order&order=";?>"+list,
                            context: document.body,
                            success: function(){
                                $("#jsMessage").fadeIn("fast");
                                $("#jsMessage").html("<?php _e('Order saved');?>");
                                setTimeout(function(){
                                    $("#jsMessage").fadeOut("slow", function () {
                                        $("#jsMessage").html("");
                                    });
                                }, 3000);
                            }
                        });
                    }
                });
                $( "ul, li" ).disableSelection();
                $(".quick_edit").hide();
            });


            function js_edit(s_name, id, locale) {
                var d_category = $('#d_edit_category');

                d_category.css('display','block');
                $('#fade').css('display','block');

                $("input[name=catId]").val(id);
                $("input[name=locale]").val(locale);
                $("input[name=s_name]").val(s_name);

                return false;
            }
        </script>
		<div id="content">
			<div id="separator"></div>	
			<?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
			<div id="right_column">
			    <div id="content_header" class="content_header">
					<div style="float: left;">
                        <img src="<?php echo osc_current_admin_theme_url() ; ?>images/cat-icon.png" title="" alt="" />
                    </div>
					<div id="content_header_arrow">&raquo; <?php _e('Categories'); ?></div>
					<div style="clear: both;"></div>
				</div>
				<div id="content_separator"></div>
				<?php osc_show_flash_message('admin') ; ?>
				
				<div id="jsMessage" class="FlashMessage" style="display:none;">
				</div>
				
                <div style="clear: both;"></div>
				<div id="TableCategories" class="TableCategories">
				    <ul><li>
                        <div style="float:left;">
                            <?php _e('Category name'); ?>
                        </div>
                        <div class="left" style="padding-left:10px;">
                            <img class="vtip" src="<?php echo osc_current_admin_theme_url(); ?>/images/question.png" title="Drag&drop the categories to reorder them the way you like. Click on the pencil icon to quick edit the name of the category." alt=""/>
                        </div>
                        <div style="float:right;">
                            <?php _e('Options'); ?>
                        </div>
                    </li></ul>
                    <div style="clear: both;"></div>
				    <ul id="sortable">
				    <?php foreach($categories as $category) {?>
                    <?php $data = Category::newInstance()->isParentOf($category['pk_i_id']);
                        $has_subcategories = (count($data)>0)?true:false;
                    ?>
				        <li class="category_li <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" >
				            <div class="category_div <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" category_id="<?php echo $category['pk_i_id'];?>" >
				                <div class=".quick_edit" id="<?php echo "quick_edit_".$category['pk_i_id']; ?>" style="float:left;">
				                    <?php echo $category['s_name'];?> 
				                    <a onclick="js_edit(<?php echo "'".$category['s_name']."', '".$category['pk_i_id']."', '".$category['fk_c_locale_code']; ?>');" href='#'>
				                        <img src="<?php echo osc_admin_base_url() ; ?>images/edit.png" alt="<?php _e('Quick edit'); ?>" title="<?php _e('Quick edit'); ?>" />
				                    </a>
				                </div>
				                <div style="float:right;">
				                    <a href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=edit&amp;id=<?php echo $category['pk_i_id']; ?>'>
				                    <?php _e('Edit'); ?>
				                    </a> | <a href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=enable&amp;id=<?php echo $category['pk_i_id']; ?>&enabled=<?php echo $category['b_enabled'] == 1 ? '0' : '1'; ?>'>
				                    <?php _e($category['b_enabled'] == 1 ? 'Disable' : 'Enable'); ?>
				                    </a> <?php if($has_subcategories) { ?>| <a href='<?php echo osc_admin_base_url(true); ?>?page=categories&parentId=<?php echo $category['pk_i_id']; ?>'>
				                    <?php _e('View subcategories'); ?>
				                    </a><?php }; ?> | <a onclick="javascript:return confirm('<?php _e('WARNING: This will also delete the items under that category. This action cann not be undone. Are you sure you want to continue?'); ?>')" href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=delete&amp;id[]=<?php echo $category['pk_i_id']; ?>'>
				                    <?php _e('Delete'); ?>
				                    </a>
				                </div>
                                <div style="clear: both;"></div>
				            </div>
				        </li>
				    <?php }; ?>
				    </ul>
				</div>
               
				</div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <!-- Form edit country -->
        <div id="d_edit_category" class="lightbox_country location" style="height: 140px;">
            <div>
                <h4><?php _e('Edit category') ; ?></h4>
            </div>
            <div style="padding: 14px;">
                <form action="<?php echo osc_admin_base_url(true); ?>" method="POST" accept-charset="utf-8">
                    <input type="hidden" name="page" value="categories" />
                    <input type="hidden" name="action" value="quick_edit" />
                    <input type="hidden" name="catId" value="" />
                    <input type="hidden" name="locale" value="" />
                    <table>
                        <tr>
                            <td><?php _e('Category'); ?>: </td>
                            <td><input type="text" id="s_name" name="s_name" value="" /></td>
                        </tr>
                    </table>
                    <div style="margin-top: 8px; text-align: right; ">
                        <input type="button" value="<?php _e('Cancel'); ?>" onclick="$('#d_edit_category').css('display','none');$('#fade').css('display','none');"/>
                        <input type="submit" name="submit" value="<?php _e('Edit'); ?>" />
                    </div>
                </form>
            </div>
        </div>
        <!-- End form edit country -->
        <div id="fade" class="black_overlay"></div> 
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>				
