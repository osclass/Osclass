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

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1 class="dashboard"><?php _e('Appearance') ; ?></h1>
<?php
    }
?>
<?php osc_current_admin_theme_path('parts/header.php') ; ?>
    <!-- themes list -->
    <div class="appearance">
        <h2 class="render-title"><?php _e('Add new theme') ; ?></h2>
        <div id="tabs" class="ui-osc-tabs ui-tabs-right">
            <ul>
                <li><a href="#market"><?php _e('Market'); ?></a></li>
                <li><a href="#upload-themes"><?php _e('Upload theme') ; ?></a></li>
            </ul>
            <div id="upload-themes" class="ui-osc-tabs-panel">
                <h2 class="render-title"><?php _e('Upload theme') ; ?></h2>
                <div class="form-horizontal">
                <?php if( is_writable( osc_themes_path() ) ) { ?>
                    <div class="flashmessage info">
                        <p class="info"><?php printf( __('Download more themes at %s'), '<a href="https://sourceforge.net/projects/osclass/files/Themes/" target="_blank">Sourceforge</a>') ; ?></p>
                    </div>
                    <form action="<?php echo osc_admin_base_url(true) ; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="add_post" />
                        <input type="hidden" name="page" value="appearance" />

                        <div class="form-row">
                            <div class="form-label"><?php _e('Theme package (.zip)') ; ?></div>
                            <div class="form-controls">
                                <div class="form-label-checkbox"><input type="file" name="package" id="package" /></div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <input type="submit" value="<?php echo osc_esc_html( __('Upload') ) ; ?>" class="btn btn-submit" />
                        </div>
                    </form>
                <?php } else { ?>
                    <div class="flashmessage error">
                        <a class="close" href="#">×</a>
                        <p><?php _e('Cannot install a new theme') ; ?></p>
                    </div>
                    <p class="text">
                        <?php _e('The theme folder is not writable on your server and you cannot upload themes from the administration panel. Please make the theme folder writable') ; ?>
                    </p>
                    <p class="text">
                        <?php _e('To make the directory writable under UNIX execute this command from the shell:') ; ?>
                    </p>
                    <pre>chmod a+w <?php echo osc_themes_path() ; ?></pre>
                <?php } ?>
                </div>
            </div>
            <div id="market">
                <h2 class="render-title"><?php _e('Latest themes on market') ; ?></h2>
                <?php /* SOME STUFF */ ?>
            </div>
        </div>
        <script>
        $(function() {
            $( "#tabs" ).tabs({ selected: 1 });
        });
        </script>
    </div>
    <!-- /themes list -->
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>