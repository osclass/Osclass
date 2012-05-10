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
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
    </head>
    <body>
        <?php osc_current_admin_theme_path('header.php') ; ?>
        <!-- container -->
        <div id="content">
            <?php osc_current_admin_theme_path( 'include/backoffice_menu.php' ) ; ?>
            <!-- right container -->
            <div class="right">
                <div class="header_title">
                    <h1 class="languages"><?php _e('Add a language') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- add language form -->
                <div class="languages">
                <?php if( is_writable( osc_translations_path() ) ) { ?>
                    <div class="FlashMessage info">
                        <p class="info"><?php printf( __('Download more languages at %s'), '<a href="https://sourceforge.net/projects/osclass/files/Languages/" target="_blank">Sourceforge</a>') ; ?></p>
                    </div>
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_post" />
                        <input type="hidden" name="page" value="languages" />
                        <div class="actions-nomargin">
                            <p class="text">
                                <?php _e('Language package (.zip)') ; ?>
                                <input type="file" name="package" id="package" />
                            </p>
                            <input type="submit" value="<?php echo osc_esc_html( __('Upload') ) ; ?>" />
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="FlashMessage error">
                        <a class="close" href="#">×</a>
                        <p><?php _e('Cannot install a new language') ; ?></p>
                    </div>
                    <p class="text">
                        <?php _e('The translations folder is not writable on your server and you cannot upload translations from the administration panel. Please make the translation folder writable') ; ?>
                    </p>
                    <p class="text">
                        <?php _e('To make the directory writable under UNIX execute this command from the shell:') ; ?>
                    </p>
                    <pre>chmod a+w <?php echo osc_translations_path() ; ?></pre>
                <?php } ?>
                </div>
                <!-- /add language form -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>