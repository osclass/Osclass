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

    $categories = __get('categories') ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_url('js/jquery.ui.nestedSortable.js') ; ?>"></script>
        <link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>
        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{ display:none ; }</style>');
        </script>
        <style>
            .placeholder {
                background-color: #cfcfcf;
            }
            ul.sortable > li > ul {
                margin-left: 20px;
            }
            .footest .category_div {
                opacity: 0.8;
            }
            .list-categories li {
                opacity: 1 !important;
            }
            .category_div {
                background: #ffffff;
            }
            .alert-custom {
                background-color: #FDF5D9;
                border-bottom: 1px solid #EEDC94;
                color: #404040;
            }
        </style>
        <script type="text/javascript">
            $(function() {
                var list_original = '' ;

                $('.sortable').nestedSortable({
                    disableNesting: 'no-nest',
                    forcePlaceholderSize: true,
                    handle: 'div',
                    helper: 'clone',
                    listType: 'ul',
                    items: 'li',
                    maxLevels: 2,
                    opacity: .6,
                    placeholder: 'placeholder',
                    revert: 250,
                    tabSize: 25,
                    tolerance: 'pointer',
                    toleranceElement: '> div',
                    create: function(event, ui) {
                        list_original = $('.sortable').nestedSortable('serialize') ;
                    },
                    start: function(event, ui) { 
                        $(ui.helper).addClass('footest');
                        $(ui.helper).prepend("<div style='opacity: 1 !important; padding:5px;' class='alert-custom'><?php _e('Note: You need to expand the category if you want to make it a subcategory.'); ?></div>");
                    },
                    stop: function(event, ui) { 
                        var list = '' ;
                        list = $('.sortable').nestedSortable('serialize') ;
                        var array_list = $('.sortable').nestedSortable('toArray') ;
                        var l = array_list.length ;
                        for(var k = 0; k < l; k++ ) {
                            if( array_list[k].item_id == $(ui.item).find('div').attr('category_id') ) {
                                if( array_list[k].parent_id == 'root' ) {
                                    $(ui.item).closest('.toggle').show() ;
                                }
                                break ;
                            }
                        }
                        if( !$(ui.item).parent().hasClass('sortable') ) {
                            $(ui.item).parent().addClass('subcategory') ;
                        }
                        if(list_original != list) {
                            $.ajax({
                                url: "<?php echo osc_admin_base_url(true) . "?page=ajax&action=categories_order&" ; ?>" + list,
                                context: document.body,
                                success: function(res){
                                    var ret = eval( "(" + res + ")") ;
                                    var message = "" ;
                                    if( ret.error ) {
                                        $(".jsMessage p").attr('class', 'error') ;
                                        message += ret.error ;
                                    }
                                    if( ret.ok ){
                                        $(".jsMessage p").attr('class', 'ok') ;
                                        message += ret.ok ;
                                    }

                                    $(".jsMessage").show() ;
                                    $(".jsMessage p").html(message) ;
                                },
                                error: function(){
                                    $(".jsMessage").fadeIn("fast") ;
                                    $(".jsMessage p").attr('class', '') ;
                                    $(".jsMessage p").html("<?php _e('Ajax error, try again.') ; ?>") ;
                                }
                            }) ;

                            list_original = list ;
                        }
                    }
                }) ;
                
                $(".toggle").bind("click", function(e) {
                    var list = $(this).parent().parent().parent().parent().find('ul');
                    var li   = $(this).closest('li');
                    if( $(this).attr('status') == 'collapsed' ) {
                        $(li).removeClass('no-nest');
                        $(list).show();
                        $(this).attr('status', 'expanded');
                        $(this).html('-');
                    } else {
                        $(li).addClass('no-nest');
                        $(list).hide();
                        $(this).attr('status', 'collapsed');
                        $(this).html('+');
                    }
                }) ;
            }) ;

            list_original = $('.sortable').nestedSortable('serialize') ;
            
            function show_iframe(class_name, id) {
                $('.iframe-category').remove() ;

                var name = 'frame_'+ id ; 
                var id_  = 'frame_'+ id ;
                var url  = '<?php echo osc_admin_base_url(true) ; ?>?page=ajax&action=category_edit_iframe&id=' + id ;
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res){
                        $('div.' + class_name).html(res);
                        $('div.' + class_name).fadeIn("fast");
                    }
                });
                
                return false;
            }
            
            function delete_category(id){
                var answer = confirm('<?php echo osc_esc_js( __('WARNING: This will also delete the items under that category. This action cann not be undone. Are you sure you want to continue?') ) ; ?>');
                if( answer ) {
                    var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=delete_category&id=' + id ;

                    $.ajax({
                        url: url,
                        context: document.body,
                        success: function(res) {
                            var ret = eval( "(" + res + ")") ;
                            var message = "" ;
                            if( ret.error ) {
                                message += ret.error ;
                                $(".jsMessage p").attr('class', 'error') ;
                            }
                            if( ret.ok ) {
                                message += ret.ok;
                                $(".jsMessage p").attr('class', 'ok') ;

                                $('#list_'+id).fadeOut("slow") ;
                                $('#list_'+id).remove() ;
                            }

                            $(".jsMessage").show() ;
                            $(".jsMessage p").html(message) ;
                        },
                        error: function() {
                            $(".jsMessage").show() ;
                            $(".jsMessage p").attr('class', '') ;
                            $(".jsMessage p").html("<?php _e('Ajax error, try again.') ; ?>") ;
                        }
                    }) ;
                }
                return false;
            }
            
            function enable_cat(id) {
                var enabled ;
                
                $(".jsMessage").fadeIn("fast") ;
                $(".jsMessage p").attr('class', '') ;
                $(".jsMessage p").html("<img height='16' width='16' src='<?php echo osc_current_admin_theme_url('images/spinner_loading.gif');?>'> <?php _e('This action can take a while.') ; ?>") ;

                if( $('div[category_id=' + id + ']').hasClass('disabled') ) {
                    enabled = 1 ;
                } else {
                    enabled = 0 ;
                }

                var url  = '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=enable_category&id=' + id + '&enabled=' + enabled ;
                $.ajax({
                    url: url,
                    context: document.body,
                    success: function(res) {
                        var ret = eval( "(" + res + ")");
                        var message = "" ;
                        if(ret.error) {
                            message += ret.error ;
                            $(".jsMessage p").attr('class', 'error') ;
                        }
                        if(ret.ok) {
                            if( enabled == 0 ) {
                                $('div[category_id=' + id + ']').addClass('disabled') ;
                                $('div[category_id=' + id + ']').removeClass('enabled') ;
                                $('div[category_id=' + id + ']').find('a.enable').text('<?php _e('Enable') ; ?>') ;
                                for(var i = 0; i < ret.affectedIds.length; i++) {
                                    id =  ret.affectedIds[i].id ;
                                    $('div[category_id=' + id + ']').addClass('disabled') ;
                                    $('div[category_id=' + id + ']').removeClass('enabled') ;
                                    $('div[category_id=' + id + ']').find('a.enable').text('<?php _e('Enable') ; ?>') ;
                                }
                            } else {
                                $('div[category_id=' + id + ']').removeClass('disabled') ;
                                $('div[category_id=' + id + ']').addClass('enabled') ;
                                $('div[category_id=' + id + ']').find('a.enable').text('<?php _e('Disable'); ?>') ;

                                for(var i = 0; i < ret.affectedIds.length; i++) {
                                    id =  ret.affectedIds[i].id ;
                                    $('div[category_id=' + id + ']').removeClass('disabled') ;
                                    $('div[category_id=' + id + ']').addClass('enabled') ;
                                    $('div[category_id=' + id + ']').find('a.enable').text('<?php _e('Disable'); ?>') ;
                                }
                            }

                            message += ret.ok ;
                            $(".jsMessage p").attr('class', 'ok') ;
                        }

                        $(".jsMessage").show();
                        $(".jsMessage p").html(message);
                    },
                    error: function(){
                        $(".jsMessage").show() ;
                        $(".jsMessage p").attr('class', '') ;
                        $(".jsMessage p").html("<?php _e('Ajax error, try again.') ; ?>") ;
                    }
                }) ;
            }
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path ( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <input type="button" value="<?php echo osc_esc_html( __('Add new category') ) ; ?>" onclick="window.location.href='<?php echo osc_admin_base_url(true) ; ?>?page=categories&amp;action=add_post_default'" />
                    <h1 class="categories"><?php _e('Categories') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <div class="jsMessage FlashMessage info" style="display: none;">
                    <a class="close" href="javascript://">×</a>
                    <p></p>
                </div>
                <!-- categories form -->
                <div class="categories">
                    <div class="FlashMessage info">
                        <p class="info"><?php _e('Drag&drop the categories to reorder them the way you like. Click on edit link to edit the category') ; ?></p>
                    </div>
                    <div class="list-categories">
                        <ul class="sortable">
                        <?php foreach($categories as $category) { ?>
                        <?php
                            if( count($category['categories']) > 0 ) { $has_subcategories = true ; } else { $has_subcategories = false ; }
                        ?>
                            <li id="list_<?php echo $category['pk_i_id'] ; ?>" class="category_li <?php echo ( $category['b_enabled'] == 1 ? 'enabled' : 'disabled' ) ; ?> <?php if($has_subcategories) { echo 'no-nest'; } ?>" >
                                <div class="category_div <?php echo ( $category['b_enabled'] == 1 ? 'enabled' : 'disabled' ) ; ?>" category_id="<?php echo $category['pk_i_id'] ; ?>" >
                                    <div class="category_row">
                                        <div class="name-cat" id="<?php echo 'quick_edit_' . $category['pk_i_id'] ; ?>">
                                            <?php if( $has_subcategories ) { ?>
                                            <span class="toggle" status="collapsed">+</span>
                                            <?php } else { ?>
                                            <span class="toggle" status="expanded">-</span>
                                            <?php } ?>
                                            <span class="name"><?php echo $category['s_name'] ; ?></span>
                                        </div>
                                        <div class="actions-cat">
                                            <a onclick="show_iframe('content_list_<?php echo $category['pk_i_id'];?>','<?php echo $category['pk_i_id'] ; ?>');"><?php _e('Edit') ; ?></a>
                                            &middot;
                                            <a class="enable" onclick="enable_cat('<?php echo $category['pk_i_id']; ?>')"><?php $category['b_enabled'] == 1 ? _e('Disable') : _e('Enable'); ?></a> 
                                            &middot;
                                            <a onclick="delete_category(<?php echo $category['pk_i_id']; ?>)"><?php _e('Delete') ; ?></a>
                                        </div>
                                    </div>
                                    <div class="edit content_list_<?php echo $category['pk_i_id'] ; ?>"></div>
                                </div>
                                <?php if($has_subcategories) { ?>
                                    <ul class="subcategory subcategories-<?php echo $category['pk_i_id'] ; ?>" style="display: none;">
                                    <?php foreach($category['categories'] as $category) {?>
                                        <li id="list_<?php echo $category['pk_i_id'] ; ?>" class="category_li <?php echo ( $category['b_enabled'] == 1 ? 'enabled' : 'disabled' ) ; ?>" >
                                            <div class="category_div <?php echo ( $category['b_enabled'] == 1 ? 'enabled' : 'disabled' ) ; ?>" category_id="<?php echo $category['pk_i_id'] ; ?>" >
                                                <div class="category_row">
                                                    <div class="name-cat" id="<?php echo "quick_edit_" . $category['pk_i_id'] ; ?>">
                                                        <span class="toggle" status="expanded">-</span>
                                                        <span class="name"><?php echo $category['s_name'] ; ?></span>
                                                    </div>
                                                    <div class="actions-cat">
                                                        <a onclick="show_iframe('content_list_<?php echo $category['pk_i_id'] ; ?>','<?php echo $category['pk_i_id'] ; ?>');"><?php _e('Edit') ; ?></a>
                                                        &middot;
                                                        <a class="enable" onclick="enable_cat('<?php echo $category['pk_i_id'] ; ?>')"><?php echo ( $category['b_enabled'] == 1 ? __('Disable') : __('Enable') ) ; ?></a> 
                                                        &middot;
                                                        <a onclick="delete_category(<?php echo $category['pk_i_id'] ; ?>)"><?php _e('Delete') ; ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="edit content_list_<?php echo $category['pk_i_id'] ; ?>"></div>
                                        </li>
                                    <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- /categories form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>