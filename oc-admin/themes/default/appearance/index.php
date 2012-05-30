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

    //customize Head
    function customHead(){
        echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery.validate.min.js').'"></script>';
        ?>
        <script type="text/javascript">
            $(function() {
                // Here we include specific jQuery, jQuery UI and Datatables functions.
                $("#button_cancel").click(function() {
                    if(confirm('<?php _e('Are you sure you want to cancel?'); ?>')) {
                        setTimeout ("window.location = 'appearance.php';", 100) ;
                    }
                });
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');
?>

<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="content-head">
    <h1><?php _e('Appearance') ; ?></h1>
</div>
<?php osc_show_flash_message('admin') ; ?>
<div id="content-page">
    <div class="grid-system">
        <div class="grid-row grid-first-row grid-100">
            <div class="row-wrapper">
    <form id="market-quick-search" class="quick-search"><input type="text" name="sPattern" placeholder="<?php _e('Search Themes'); ?>" class="input-text float-left"/><input type="Submit" value="Seach" class="btn ico ico-32 ico-search float-left"/><a href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&amp;action=add" class="btn btn-green float-right"><?php _e('Add new theme'); ?></a></form>
                <!-- right container -->
                <!-- themes list -->
                <div class="appearance">
                    <h2 class="render-title"><?php _e('Current theme') ; ?></h2>
                    <div class="current-theme">
                        <div class="theme">
                            <img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo osc_theme() ; ?>/screenshot.png" title="<?php echo $info['name'] ; ?>" alt="<?php echo $info['name'] ; ?>" />
                            <div class="theme-info">
                                <h3><?php echo $info['name'] ; ?> <?php echo $info['version']; ?> <?php _e('by') ; ?> <a target="_blank" href="<?php echo $info['author_url'] ; ?>"><?php echo $info['author_name'] ; ?></a></h3>
                            </div>
                            <div class="theme-description">
                                <?php echo $info['description'] ; ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <h2 class="render-title"><?php _e('Available themes') ; ?></h2>
                    <div class="available-theme">
                        <?php foreach($themes as $theme) { ?>
                        <?php
                                if( $theme == osc_theme() ) {
                                    continue;
                                }
                                $info = WebThemes::newInstance()->loadThemeInfo($theme) ;
                        ?>
                        <div class="theme">
                            <img src="<?php echo osc_base_url() ; ?>/oc-content/themes/<?php echo $theme ; ?>/screenshot.png" title="<?php echo $info['name'] ; ?>" alt="<?php echo $info['name'] ; ?>" />
                            <div class="theme-info">
                                <h3><?php echo $info['name'] ; ?> <?php echo $info['version']; ?> <?php _e('by') ; ?> <a target="_blank" href="<?php echo $info['author_url'] ; ?>"><?php echo $info['author_name'] ; ?></a></h3>
                            </div>
                            <div class="theme-description">
                                <?php echo $info['description'] ; ?>
                            </div>
                            <div class="theme-actions">
                                <a href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=activate&amp;theme=<?php echo $theme ; ?>"><?php _e('Activate') ; ?></a>
                                &middot;
                                <a target="_blank" href="<?php echo osc_base_url(true) ; ?>?theme=<?php echo $theme ; ?>"><?php _e('Preview') ; ?></a>
                                &middot;
                                <a onclick="javascript:return confirm('<?php echo osc_esc_js(__('This action can not be undone. Are you sure you want to continue?')); ?>')" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&amp;action=delete&amp;webtheme=<?php echo $theme ; ?>"><?php _e('Delete') ; ?></a>
                                <?php if(osc_check_update(@$info['theme_update_uri'], @$info['version'])) { ?>
                                    <div id="available_theme_update"><a href='<?php echo osc_admin_base_url(true);?>?page=market&code=<?php echo htmlentities($info['theme_update_uri']); ?>'><?php _e("There's a new version available to update"); ?></a></div>
                                <?php }; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- /themes list -->
            <!-- /right container -->
        </div></div></div>
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>


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

