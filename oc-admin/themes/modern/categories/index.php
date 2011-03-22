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
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Categories'); ?></div>
        <style>
            .TableCategories ul { list-style-type: none; margin: 0; padding: 0; margin-bottom: 10px; }
            .TableCategories li { margin: 5px; padding: 5px; width: 700px; }
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
                                $("#jsMessage").html("<?php _e('Order saved');?>");
                                setTimeout(function(){
                                    $("#jsMessage").fadeOut("slow", function () {
                                        $("#jsMessage").html("");
                                    });
                                }, 3000);
                            }
                        });                    }
                });
                $( "ul, li" ).disableSelection();
            });
        </script>

        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('datatables.post_init.js') ; ?>"></script>
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
				
				<div id="jsMessage" class="flashMessage">
				</div>
				
                <div style="clear: both;"></div>
				<div id="TableCategories" class="TableCategories">
				    <ul id="sortable">
				    <?php foreach($categories as $category) {?>
                    <?php $data = Category::newInstance()->isParentOf($category['pk_i_id']);
                        $has_subcategories = (count($data)>0)?true:false;
                    ?>
				        <li><div class="category_div" category_id="<?php echo $category['pk_i_id'];?>" ><div style="float:left;"><?php echo $category['s_name'];?></div><div style="float:right;"><a href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=edit&amp;id=<?php echo $category['pk_i_id']; ?>'><?php _e('Edit'); ?></a> | <a href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=enable&amp;id=<?php echo $category['pk_i_id']; ?>&enabled=<?php echo $category['b_enabled'] == 1 ? '0' : '1'; ?>'><?php _e($category['b_enabled'] == 1 ? 'Disable' : 'Enable'); ?></a> <?php if($has_subcategories) { ?>| <a href='<?php echo osc_admin_base_url(true); ?>?page=categories&parentId=<?php echo $category['pk_i_id']; ?>'><?php _e('View subcategories'); ?></a><?php }; ?> | <a onclick=\"javascript:return confirm('<?php _e('WARNING: This will also delete the items under that category. This action can\\\\\'t be undone. Are you sure you want to continue?'); ?>')\" href='<?php echo osc_admin_base_url(true); ?>?page=categories&action=delete&amp;id[]=<?php echo $category['pk_i_id']; ?>'><?php _e('Delete'); ?></a></div>
                        <div style="clear: both;"></div>
				        </div></li>
				    <?php }; ?>
				    </ul>
				</div>
				</div> <!-- end of right column -->
            <div style="clear: both;"></div>
        </div> <!-- end of container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>				
