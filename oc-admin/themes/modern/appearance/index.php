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

    //getting variables for this view
    $themes = __get("themes") ;
    $info   = __get("info") ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="<?php echo str_replace('_', '-', osc_current_user_locale()) ; ?>">
    <head>
        <?php osc_current_admin_theme_path('head.php') ; ?>
        <script type="text/javascript">
            $(function() {
                // Here we include specific jQuery, jQuery UI and Datatables functions.
                $("#button_cancel").click(function() {
                    if(confirm('<?php echo osc_esc_js ( __('Are you sure you want to cancel?')); ?>')) {
                        setTimeout ("window.location = 'appearance.php';", 100) ;
                    }
                });
            });
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
                    <input type="button" value="<?php echo osc_esc_html( __('Add new theme') ) ; ?>" onclick="window.location.href='<?php echo osc_admin_base_url(true) ; ?>?page=appearance&amp;action=add'" />
                    <h1 class="themes"><?php _e('Appearance') ; ?></h1>
                </div>
                <?php osc_show_flash_message('admin') ; ?>
                <!-- themes list -->
                <div class="appearance">
                    <h2><?php _e('Current theme') ; ?></h2>
                    <div class="current-theme">
                        <img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo osc_theme() ; ?>/screenshot.png" title="<?php echo osc_esc_html ( $info['name'] ) ; ?>" alt="<?php echo osc_esc_html ( $info['name'] ) ; ?>" />
                        <div class="theme-info">
                            <h3><?php echo $info['name'] ; ?> <?php echo $info['version']; ?> <?php _e('by') ; ?> <a target="_blank" href="<?php echo $info['author_url'] ; ?>"><?php echo $info['author_name'] ; ?></a></h3>
                        </div>
                        <div class="theme-description">
                            <?php echo $info['description'] ; ?>
                        </div>
                    </div>
                    <h2><?php _e('Available themes') ; ?></h2>
                    <div class="available-theme">
                        <?php foreach($themes as $theme) { ?>
                        <?php
                                if( $theme == osc_theme() ) {
                                    continue;
                                }
                                $info = WebThemes::newInstance()->loadThemeInfo($theme) ;
                        ?>
                        <div class="theme">
                            <img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo $theme ; ?>/screenshot.png" title="<?php echo osc_esc_html ( $info['name'] ) ; ?>" alt="<?php echo osc_esc_html ( $info['name'] ) ; ?>" />
                            <div class="theme-info">
                                <h3><?php echo $info['name'] ; ?> <?php echo $info['version']; ?> <?php _e('by') ; ?> <a target="_blank" href="<?php echo $info['author_url'] ; ?>"><?php echo $info['author_name'] ; ?></a></h3>
                            </div>
                            <div class="theme-description">
                                <?php echo $info['description'] ; ?>
                            </div>
                            <div class="theme-actions">
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=activate&amp;theme=<?php echo $theme ; ?>"><?php _e('Activate') ; ?></a> &middot;
                                <a target="_blank" href="<?php echo osc_base_url(true) ; ?>?theme=<?php echo $theme ; ?>"><?php _e('Preview') ; ?></a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /themes list -->
            </div>
            <!-- /right container -->
        </div>
        <!-- /container -->
        <?php osc_current_admin_theme_path('footer.php') ; ?>
    </body>
</html>