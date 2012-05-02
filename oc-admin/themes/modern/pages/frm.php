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

    $page    = __get('page') ;
    $locales = OSCLocale::newInstance()->listAllEnabled() ;

    if( isset($page['pk_i_id']) ) {
        $edit       = true ;
        $title      = __('Edit page') ;
        $action_frm = 'edit_post' ;
        $btn_text   = osc_esc_html( __('Save changes') ) ;
    } else {
        $edit       = false ;
        $title      = __('Add page') ;
        $action_frm = 'add_post' ;
        $btn_text   = osc_esc_html( __('Add page') ) ;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tiny_mce/tiny_mce.js') ; ?>"></script>
        <script type="text/javascript">
            tinyMCE.init({
                mode : "textareas",
                theme : "advanced",
                skin: "o2k7",
                width: "70%",
                height: "340px",
                skin_variant : "silver",
                theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,link,unlink,separator,image,code",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
                theme_advanced_toolbar_align : "left",
                theme_advanced_toolbar_location : "top",
                plugins : "media",
                entity_encoding : "raw",
                theme_advanced_buttons1_add : "media"
            }) ;
        </script>
        <link href="<?php echo osc_current_admin_theme_styles_url('tabs.css') ; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="<?php echo osc_current_admin_theme_js_url('tabber-minimized.js') ; ?>"></script>
        <script type="text/javascript">
            document.write('<style type="text/css">.tabber{ display:none ; }</style>') ;
        </script>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="pages"><?php echo $title ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- page form -->
                <div class="pages-form">
                    <form action="<?php echo osc_admin_base_url(true); ?>" method="post">
                        <input type="hidden" name="page" value="pages" />
                        <input type="hidden" name="action" value="<?php echo $action_frm ; ?>" />
                        <?php PageForm::primary_input_hidden($page) ; ?>
                        <fieldset>
                            <div class="input-line">
                                <label><?php _e('Internal name') ; ?></label>
                                <div class="input medium">
                                    <?php PageForm::internal_name_input_text($page) ; ?>
                                    <p class="help-inline"><?php _e('Used to identify quickly this page') ; ?></p>
                                </div>
                            </div>
                            <div class="input-line">
                                <label></label>
                                <div class="input">
                                    <?php PageForm::multilanguage_name_description($locales, $page) ; ?>
                                </div>
                            </div>
                            <div class="actions">
                                <input type="submit" value="<?php echo $btn_text ; ?>" />
                            </div>
                        </fieldset>
                    </form>
                </div>
                <!-- /page form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>