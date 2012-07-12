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

    $version_length = strlen(osc_version());
    $main_version = substr(osc_version(),0, $version_length-2).".".substr(osc_version(),$version_length-2, 1);

    //customize Head
    function customHead(){
        echo '<script type="text/javascript" src="'.osc_current_admin_theme_js_url('jquery.validate.min.js').'"></script>';
    }
    osc_add_hook('admin_header','customHead');

    function addHelp() {
        echo '<p>' . sprintf(__('Browse and download available OSClass themes from a constantly-updated selection. Remember, you must install the theme after you download it. If you want to design a theme for OSClass, follow these instructions: %s'), '<a href="http://doc.osclass.org/How_to_create_a_theme" target="_blank">http://doc.osclass.org/How_to_create_a_theme</a>') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Appearance') ; ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
        </h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="appearance-page">
    <!-- themes list -->
    <div class="appearance">
        <div id="tabs" class="ui-osc-tabs ui-tabs-right">
            <ul>
                <li><a href="#market"><?php _e('Market'); ?></a></li>
                <li><a href="#available-themes" onclick="window.location = '<?php echo osc_admin_base_url(true) . '?page=appearance'; ?>'; return false; "><?php _e('Available themes') ; ?></a></li>
            </ul>
            <div id="market">
                <h2 class="render-title"><?php _e('Latest themes available') ; ?></h2>
                <div id="market_themes" class="available-theme">
                </div>
                <div id="market_pagination" class="has-pagination">
                </div>
            </div>
            <div id="market_installer" class="has-form-actions hide">
                <form action="" method="post">
                    <input type="hidden" name="market_code" id="market_code" value="" />
                    <div class="osc-modal-content-market">
                        <img src="" id="market_thumb" class="float-left"/>
                        <table class="table" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr class="table-first-row">
                                    <td><?php _e('Name') ; ?></td>
                                    <td><span id="market_name"><?php _e("Loading data"); ?></span></td>
                                </tr>
                                <tr class="even">
                                    <td><?php _e('Version') ; ?></td>
                                    <td><span id="market_version"><?php _e("Loading data"); ?></span></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Author') ; ?></td>
                                    <td><span id="market_author"><?php _e("Loading data"); ?></span></td>
                                </tr>
                                <tr>
                                    <td><?php _e('Compatible with') ; ?></td>
                                    <td><span id="market_compatible"><?php _e("Loading data"); ?></span></td>
                                </tr>
                                <tr class="even">
                                    <td><?php _e('URL') ; ?></td>
                                    <td><a id="market_url" href="#"><?php _e("Download manually"); ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="clear"></div>
                    </div>
                    <div class="form-actions">
                        <div class="wrapper">
                            <button id="market_cancel" class="btn btn-red" ><?php echo osc_esc_html( __('Cancel') ) ; ?></button>
                            <button id="market_install" class="btn btn-submit" ><?php echo osc_esc_html( __('Continue download') ) ; ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                $( "#tabs" ).tabs({ selected: 2 });

                $("#market_cancel").on("click", function(){
                    $(".ui-dialog-content").dialog("close");
                    return false;
                });

                $("#market_install").on("click", function(){
                    $(".ui-dialog-content").dialog("close");
                    $('<div id="downloading"><div class="osc-modal-content"><?php echo osc_esc_js(__('Please wait until the download is completed')); ?></div></div>').dialog({title:'<?php echo osc_esc_js(__('Downloading')); ?>...',modal:true});
                    $.getJSON(
                    "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
                    {"code" : $("#market_code").attr("value"), "section" : 'themes'},
                    function(data) {
                        var content = data.message ;
                        if(data.error == 0) { // no errors
                            content += '<h3><?php echo osc_esc_js(__('The theme have been downloaded correctly, proceed to activate or preview it.')); ?></h3>';
                            content += "<p>";
                            content += '<a class="btn btn-mini btn-green" href="<?php echo osc_admin_base_url(true); ?>?page=appearance&marketError='+data.error+'&slug='+data.data['s_update_url']+'"><?php echo osc_esc_js(__('Ok')); ?></a>';
                            content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php echo osc_esc_js(__('Close')); ?></a>';
                            content += "</p>";
                        } else {
                            content += '<a class="btn btn-mini" href="javascript:location.reload(true)"><?php echo osc_esc_js(__('Close')); ?></a>';
                        }
                        $("#downloading .osc-modal-content").html(content);
                    });
                    return false;
                });

                function getMarketContent(fPage) {
                    // get page
                    var page = 1;
                    if(fPage!="") {
                        page = fPage;
                    }

                    $.getJSON(
                        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=local_market",
                        {"section" : "themes", 'mPage' : page },
                        function(data){
                            $("#market_themes").html(" ");
                            $('#market_pagination').html(" ");
                            if(data!=null && data.themes!=null) {
                                for(var i=0;i<data.themes.length;i++) {
                                    var description = $(data.themes[i].s_description).text();
                                    dots = '';
                                    if(description.length > 80){
                                        dots = '...';
                                    }
                                    var themes_downloaded  = <?php $themes_downloaded = getPreference('themes_downloaded'); if($themes_downloaded != ''){ echo $themes_downloaded; } else { echo 'new Array()'; } ?>;
                                    var themes_to_update    = <?php echo getPreference('themes_to_update'); ?>;
                                    var button = '';

                                    if(jQuery.inArray(data.themes[i].s_update_url, themes_downloaded) >= 0 ) {
                                        if( jQuery.inArray(data.themes[i].s_update_url, themes_to_update) >= 0 ) {
                                            button = '<a href="#'+data.themes[i].s_update_url+'" class="btn btn-mini btn-orange market-popup market_update"><?php echo osc_esc_js(__('Update')); ?></a>';
                                        } else {
                                            button = '<a href="#" class="btn btn-mini btn-disabled" ><?php echo osc_esc_js(__('Already downloaded')); ?></a>';
                                        }
                                    } else {
                                        button = '<a href="#'+data.themes[i].s_update_url+'" class="btn btn-mini btn-green market-popup"><?php echo osc_esc_js(__('Download theme')); ?></a>';
                                    }
                                    if(data.themes[i].s_preview!='') {
                                        button += '<a target="_blank" href="'+data.themes[i].s_preview+'" class="btn btn-mini btn-blue"><?php echo osc_esc_js(__('Preview')); ?></a>';
                                    };

                                    var imgsrc = '<?php echo osc_current_admin_theme("img/marketblank.jpg"); ?>';
                                    if(data.themes[i].s_image!=null) {
                                        imgsrc = data.themes[i].s_image;
                                    }
                                    $("#market_themes").append('<div class="theme">'
                                        +'<div class="theme-stage">'
                                            +'<img src="'+imgsrc+'" title="'+data.themes[i].s_title+'" alt="'+data.themes[i].s_title+'" />'
                                            +'<div class="theme-actions">'
                                                + button
                                            +'</div>'
                                        +'</div>'
                                        +'<div class="theme-info">'
                                            +'<h3>'+data.themes[i].s_title+' '+data.themes[i].s_version+' <?php _e('by') ; ?> '+data.themes[i].s_contact_name+'</h3>'
                                        +'</div>'
                                        +'<div class="theme-description">'
                                            +description.substring(0,80)+dots
                                        +'</div>'
                                    +'</div>');
                                }
                                // add pagination
                                $('#market_pagination').append(data.pagination_content);
                            }
                            $("#market_themes").append('<div class="clear"></div>');
                        }
                    );
                }

                getMarketContent( unescape(self.document.location.hash.substring(1)) );
                // bind pagination to getJSON
                $('#market_pagination a').live('click',function(){
                    var url =$(this).attr('href');
                    url = url.replace("#","");
                    getMarketContent(url);
                });
            });

            $('.market-popup').live('click',function(){
                var update = false;
                if( $(this).hasClass('market_update') ) update = true;
                $.getJSON(
                    "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
                    {"code" : $(this).attr('href').replace('#',''), 'section' : 'themes'},
                    function(data){
                        if(data!=null) {
                            $("#market_thumb").attr('src',data.s_thumbnail);
                            $("#market_code").attr("value", data.s_update_url);
                            $("#market_name").html(data.s_title);
                            $("#market_version").html(data.s_version);
                            $("#market_author").html(data.s_contact_name);
                            if(data.s_compatible.indexOf("<?php echo $main_version; ?>")==-1) {
                                $("#market_compatible").html(data.s_compatible + " - "  + "<?php echo osc_esc_js(sprintf(__('Warning! This theme is not compatible with your current version of OSClass (%s)'), $main_version)); ?>");
                                $("#market_compatible").parent().parent().addClass("flashmessage-error");
                            } else {
                                $("#market_compatible").html(data.s_compatible);
                                $("#market_compatible").parent().parent().removeClass("flashmessage-error");
                            }
                            $("#market_url").attr('href',data.s_source_file);

                            if(update) {
                                $('#market_install').html("<?php echo osc_esc_js( __('Update') ) ; ?>");
                            } else {
                                $('#market_install').html("<?php echo osc_esc_js( __('Continue download') ) ; ?>");
                            }

                            $('#market_installer').dialog({
                                modal:true,
                                title: '<?php echo osc_esc_js( __('OSClass Market') ) ; ?>',
                                width:485
                            });
                        }
                    }
                );
                return false;
            });
        </script>
    </div>
    <!-- /themes list -->
</div>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>