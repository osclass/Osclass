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
        //echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery-ui-1.8.20.min.js').'"></script>';
        echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery.blockUI.js').'"></script>';
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

    function customPageTitle($string) {
        return sprintf(__('Add theme &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path('parts/header.php') ; ?>
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
                    <div class="flashmessage flashmessage-info">
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
                    <div class="flashmessage flashmessage-error">
                        <a class="btn ico btn-mini ico-close" href="#">×</a>
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
                <div id="market_themes" class="available-theme">
                </div>
            </div>
            
            <div id="market_installer" style="display: none">
                <h3><?php _e('OSClass Market'); ?></h3>

                <form action="" method="post">
                    <input type="hidden" name="market_code" id="market_code" value="" />
                    <div class="form-row">
                        <div class="form-label"><?php _e('Name') ; ?></div>
                        <div class="form-controls">
                            <div id="market_name" class="form-label-checkbox"><?php _e("Loading data"); ?></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Version') ; ?></div>
                        <div class="form-controls">
                            <div id="market_version" class="form-label-checkbox"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('Author') ; ?></div>
                        <div class="form-controls">
                            <div id="market_author" class="form-label-checkbox"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label"><?php _e('URL') ; ?></div>
                        <div class="form-controls">
                            <div id="market_url" class="form-label-checkbox"></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button id="market_cancel" class="btn btn-submit" ><?php echo osc_esc_html( __('Cancel') ) ; ?></button>
                        <button id="market_install" class="btn btn-submit" ><?php echo osc_esc_html( __('I understand the risk, continue') ) ; ?></button>
                    </div>
                </form>
            </div>
            
        </div>
        <script>
        $(function() {
            $( "#tabs" ).tabs({ selected: 1 });
            
            $("#market_cancel").on("click", function(){
                $.unblockUI();
                return false;
            });
            
            $("#market_install").on("click", function(){
                $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
                {"code" : $("#market_code").attr("value")},
                function(data){
                    alert(data.message);
                });
                $.unblockUI();
                return false;
            });
            
            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=local_market",
                {"section" : "themes"},
                function(data){
                    $("#market_themes").html(" ");
                    if(data!=null && data.themes!=null) {
                        for(var i=0;i<data.themes.length;i++) {
                            $("#market_themes").append('<div class="theme">'
                                +'<div class="theme-stage">'
                                    +'<img src="" title="'+data.themes[i].s_title+'" alt="'+data.themes[i].s_title+'" />'
                                    +'<div class="theme-actions">'
                                        +'<a href="javascript:market_fetch_data(\''+data.themes[i].s_slug+'\');" class="btn btn-mini btn-green"><?php _e('Install') ; ?></a>'
                                        +'<!-- <a target="_blank" href="" class="btn btn-mini btn-blue"><?php _e('Preview') ; ?></a> -->'
                                    +'</div>'
                                +'</div>'
                                +'<div class="theme-info">'
                                    +'<h3>'+data.themes[i].s_title+' '+data.themes[i].s_version+' <?php _e('by') ; ?> <a target="_blank" href="">'+data.themes[i].s_contact_name+'</a></h3>'
                                +'</div>'
                                +'<div class="theme-description">'
                                    +data.themes[i].s_description
                                +'</div>'
                            +'</div>');
                        }
                    }
                    $("#market_themes").append('<div class="clear"></div>');
                }
            );

        });
        
        function market_fetch_data(slug) {
            $.blockUI({
                message: $("#market_installer"),
                css: { 
                    textAlign: 'left',
                    left: '370px',
                    top: '180px',
                    width: '450px',
                    padding: 10
                },
                onBlock : function(){
                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
                        {"code" : slug},
                        function(data){
                            if(data!=null) {
                                $("#market_code").attr("value", data.s_slug);
                                $("#market_name").html(data.s_title);
                                $("#market_version").html(data.s_version);
                                $("#market_author").html(data.s_author);
                                $("#market_url").html(data.s_source_file);
                            }
                        }
                    );
                }
            });
        }
        
        </script>
    </div>
    <!-- /themes list -->
<?php osc_current_admin_theme_path('parts/footer.php') ; ?>