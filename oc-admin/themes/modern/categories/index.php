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
    $parent     = __get("parent");
    $last = end($categories);
    $last_id = $last['pk_i_id'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script src="<?php echo osc_current_admin_theme_url(); ?>js/vtip/vtip.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo osc_current_admin_theme_url(); ?>js/vtip/css/vtip.css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_url(); ?>js/jquery.ui.nestedSortable.js"></script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <div id="update_version" style="display:none;"></div>
        <div class="Header"><?php _e('Categories'); ?></div>
        <script type="text/javascript">
            $(function() {
                
                var list_original = '';
                
                $('.sortable').nestedSortable({
                    disableNesting: 'no-nest',
                    forcePlaceholderSize: true,
                    handle: 'div',
                    helper:	'clone',
                    listType: 'ul',
                    items: 'li',
                    maxLevels: 2,
                    opacity: .6,
                    placeholder: 'placeholder',
                    revert: 250,
                    tabSize: 25,
                    tolerance: 'pointer',
                    toleranceElement: '> div',
                    create: function(event, ui){
                        list_original = $('.sortable').nestedSortable('serialize');
                    },
                    stop: function(event, ui) { 
                        var list = '';
                        list = $('.sortable').nestedSortable('serialize');
                        if(list_original != list) {
                            $.ajax({
                                url: "<?php echo osc_admin_base_url(true)."?page=ajax&action=categories_order&";?>"+list,
                                context: document.body,
                                success: function(res){
                                    var ret = eval( "(" + res + ")");
                                    var message = "";
                                    if(ret.error) { 
                                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/cross.png"/>';
                                        message += ret.error; 

                                    }
                                    if(ret.ok){ 
                                        message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/tick.png"/>';
                                        message += ret.ok; 
                                    }

                                    $("#jsMessage").fadeIn("fast");
                                    $("#jsMessage").html(message);
                                    setTimeout(function(){
                                        $("#jsMessage").fadeOut("slow", function () {
                                            $("#jsMessage").html("");
                                        });
                                    }, 3000);
                                },
                                error: function(){
                                    $("#jsMessage").fadeIn("fast");
                                    $("#jsMessage").html("<?php _e('Ajax error, try again.');?>");
                                    setTimeout(function(){
                                        $("#jsMessage").fadeOut("slow", function () {
                                            $("#jsMessage").html("");
                                        });
                                    }, 3000);
                                }
                            });

                            list_original = list;
                        }
                    }
                });
            });

            
            list_original = $('.sortable').nestedSortable('serialize');
            
            function show_iframe(class_name, id) {
                var name = 'frame_'+ id ; 
                var id_  = 'frame_'+ id ;
                var url  = '<?php echo osc_admin_base_url(true); ?>?page=categories&action=edit_iframe&id='+id; 
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res){
                        $('div.'+class_name).html(res);
                        $('div.'+class_name).fadeIn("fast");
                    }
                });
                
                return false;
            }
            
            function delete_category(id){
                var answer = confirm('<?php _e('WARNING: This will also delete the items under that category. This action cann not be undone. Are you sure you want to continue?'); ?>');
                if(answer){
                    var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_category&id='+id;
                    $.ajax({
                        url: url,
                        context: document.body,
                        success: function(res){
                            var ret = eval( "(" + res + ")");
                            var message = "";
                            if(ret.error) { 
                                message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/cross.png"/>';
                                message += ret.error; 
                            }
                            if(ret.ok){
                                message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/tick.png"/>';
                                message += ret.ok;
                                
                                $('#list_'+id).fadeOut("slow");
                                $('#list_'+id).remove();
                            }
                            
                            $("#jsMessage").fadeIn("fast");
                            $("#jsMessage").html(message);
                            setTimeout(function(){
                                $("#jsMessage").fadeOut("slow", function () {
                                    $("#jsMessage").html("");
                                });
                            }, 3000);

                        },
                        error: function(){
                            $("#jsMessage").fadeIn("fast");
                            $("#jsMessage").html("<?php _e('Ajax error, try again.');?>");
                            setTimeout(function(){
                                $("#jsMessage").fadeOut("slow", function () {
                                    $("#jsMessage").html("");
                                });
                            }, 3000);
                        }
                    });
                }
                return false;
            }
            
            function enable_cat(id, enabled){
                
                var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=enable_category&id='+id+'&enabled='+enabled; 
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res){
                        var ret = eval( "(" + res + ")");
                        var message = "";
                        if(ret.error) { 
                            message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/cross.png"/>';
                            message += ret.error; 
                        }
                        if(ret.ok){ 
                            message += '<img style="padding-right:5px;padding-top:2px;" src="<?php echo osc_current_admin_theme_url();?>images/tick.png"/>';
                            message += ret.ok;
                            if(enabled == 0) {
                                $('div[category_id='+ id +']').addClass('disabled');
                                $('div[category_id='+ id +']').removeClass('enabled');
                                
                                var js = "enable_cat('"+id+"','1')";
                                // create a function from the "js" string
                                var newclick = new Function(js);
                                
                                $('div[category_id='+ id +']').find('a.enable').attr('onclick',"").click(newclick);
                                $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Enable');?>');
                                
                                for(var i = 0; i < ret.afectedIds.length; i++) {
                                    id =  ret.afectedIds[i].id ;
                                    $('div[category_id='+ id +']').addClass('disabled');
                                    $('div[category_id='+ id +']').removeClass('enabled');
                                    js = "enable_cat('"+id+"','1')";
                                    // create a function from the "js" string
                                    newclick = new Function(js);

                                    $('div[category_id='+ id +']').find('a.enable').attr('onclick',"").click(newclick);
                                    $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Enable');?>');
                                }
                            } else {
                                $('div[category_id='+ id +']').removeClass('disabled');
                                $('div[category_id='+ id +']').addClass('enabled');
                                
                                var js = "enable_cat('"+id+"','0')";
                                // create a function from the "js" string
                                var newclick = new Function(js);

                                $('div[category_id='+ id +']').find('a.enable').attr('onclick','').click(newclick);
                                $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Disable');?>');
                                
                                for(var i = 0; i < ret.afectedIds.length; i++) {
                                    id =  ret.afectedIds[i].id ;
                                    $('div[category_id='+ id +']').removeClass('disabled');
                                    $('div[category_id='+ id +']').addClass('enabled');
                                    
                                    js = "enable_cat('"+id+"','0')";
                                    // create a function from the "js" string
                                    newclick = new Function(js);

                                    $('div[category_id='+ id +']').find('a.enable').attr('onclick',"").click(newclick);
                                    $('div[category_id='+ id +']').find('a.enable').text('<?php _e('Disable');?>');
                                }
                            }
                        }

                        $("#jsMessage").fadeIn("fast");
                        $("#jsMessage").html(message);
                        setTimeout(function(){
                            $("#jsMessage").fadeOut("slow", function () {
                                $("#jsMessage").html("");
                            });
                        }, 3000);

                    },
                    error: function(){
                        $("#jsMessage").fadeIn("fast");
                        $("#jsMessage").html("<?php _e('Ajax error, try again.');?>");
                        setTimeout(function(){
                            $("#jsMessage").fadeOut("slow", function () {
                                $("#jsMessage").html("");
                            });
                        }, 3000);
                    }
                });
            };
            
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
                    <div id="jsMessage" class="" style="float:right;display:none;"></div>
                    <div style="clear: both;"></div>
                </div>
                <div id="content_separator"></div>
                <?php osc_show_flash_message('admin') ; ?>

                <div id="jsMessage" class="FlashMessage" style="display:none;"></div>

                <div style="clear: both;"></div>
                <div id="TableCategories" class="TableCategories">
                    <ul>
                        <li>
                            <div style="float:left;">
                                <?php _e('Category name'); ?>
                            </div>
                            <div class="left" style="padding-left:10px;">
                                <img class="vtip" src="<?php echo osc_current_admin_theme_url(); ?>/images/question.png" title="Drag&drop the categories to reorder them the way you like. Click on the pencil icon to quick edit the name of the category." alt=""/>
                            </div>
                        </li>
                    </ul>
                    <ul id="sortable" class="sortable">
                    <?php foreach($categories as $category) {?>
                    <?php 
                        if( count($category['categories']) > 0 ) { $has_subcategories = true; } else { $has_subcategories = false; }
                    ?>
                        <li id="list_<?php echo $category['pk_i_id']; ?>" class="category_li <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" >
                            <div class="category_div <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" category_id="<?php echo $category['pk_i_id'];?>" >
                                <div class="quick_edit" id="<?php echo "quick_edit_".$category['pk_i_id']; ?>" style="float:left;">
                                    <?php echo $category['s_name'];?> 
                                </div>
                                <div style="float:right;">
                                    <a onclick="show_iframe('content_list_<?php echo $category['pk_i_id'];?>','<?php echo $category['pk_i_id'];?>');">
                                    <?php _e('Edit'); ?>
                                    </a> | <a class="enable" onclick="enable_cat('<?php echo $category['pk_i_id']; ?>','<?php echo $category['b_enabled'] == 1 ? '0' : '1'; ?>')">
                                    <?php _e($category['b_enabled'] == 1 ? 'Disable' : 'Enable'); ?>
                                    </a> | <a onclick="delete_category(<?php echo $category['pk_i_id']; ?>)">
                                    <?php _e('Delete'); ?>
                                    </a>
                                </div>
                                <div class="edit content_list_<?php echo $category['pk_i_id'];?>"></div>
                                <div style="clear: both;"></div>
                                
                            </div>
                            <?php if($has_subcategories) {?>
                                <ul>
                                <?php if( count($category['categories']) > 0 ) { $has_subcategories = true; } else { $has_subcategories = false; } ?>
                                <?php foreach($category['categories'] as $category) {?>
                                    <li id="list_<?php echo $category['pk_i_id']; ?>" class="category_li <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" >
                                        <div class="category_div <?php echo $category['b_enabled'] == 1 ? 'enabled' : 'disabled'; ?>" category_id="<?php echo $category['pk_i_id'];?>" >
                                            <div class="quick_edit" id="<?php echo "quick_edit_".$category['pk_i_id']; ?>" style="float:left;">
                                                <?php echo $category['s_name'];?> 
                                            </div>
                                            <div style="float:right;">
                                                <a onclick="show_iframe('content_list_<?php echo $category['pk_i_id'];?>','<?php echo $category['pk_i_id'];?>');">
                                                <?php _e('Edit'); ?>
                                                </a> | <a class="enable" onclick="enable_cat('<?php echo $category['pk_i_id']; ?>','<?php echo $category['b_enabled'] == 1 ? '0' : '1'; ?>')">
                                                <?php _e($category['b_enabled'] == 1 ? 'Disable' : 'Enable'); ?>
                                                </a> | <a onclick="delete_category(<?php echo $category['pk_i_id']; ?>)">
                                                <?php _e('Delete'); ?>
                                                </a>
                                            </div>
                                            <div class="edit content_list_<?php echo $category['pk_i_id'];?>"></div>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </li>
                                <?php }?>
                                </ul>
                            <?php }?>
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
