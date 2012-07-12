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

    function addHelp() {
        echo '<p>' . __('Browse and download available OSClass plugins, from a constantly-updated selection. After downloading a plugin, you have to install it and configure it to get it up and running.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader() { ?>
        <h1><?php _e('Manage Plugins') ; ?>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true); ?>?page=plugins&amp;action=add" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add plugin') ; ?></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Plugins &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('input:hidden[name="installed"]').each(function() {
                    $(this).parent().parent().children().css('background', 'none') ;
                    if( $(this).val() == '1' ) {
                        if( $(this).attr("enabled") == 1 ) {
                            $(this).parent().parent().css('background-color', '#EDFFDF') ;
                        } else {
                            $(this).parent().parent().css('background-color', '#FFFFDF') ;
                        }
                    } else {
                        $(this).parent().parent().css('background-color', '#FFF0DF') ;
                    }
                }) ;
            });
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead');

    $iDisplayLength = __get('iDisplayLength');
    $aData          = __get('aPlugins'); 

    $version_length = strlen(osc_version());
    $main_version   = substr(osc_version(), 0, $version_length - 2) . "." . substr(osc_version(), $version_length - 2, 1);

    $tab_index = 0;
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="tabs" class="ui-osc-tabs ui-tabs-right">
    <ul>
        <?php 
            $aPluginsToUpdate = json_decode( getPreference('plugins_to_update') );
            $bPluginsToUpdate = is_array($aPluginsToUpdate)?true:false;
            if($bPluginsToUpdate && count($aPluginsToUpdate) > 0) {
                $tab_index = 1;
        ?>
        <li><a href="#update-plugins" onclick="window.location = '<?php echo osc_admin_base_url(true) . '?page=plugins#update-plugins'; ?>'; return false; "><?php _e('Updates'); ?></a></li>
        <?php } ?>
        <li><a href="#market"><?php _e('Market'); ?></a></li>
        <li><a href="#upload-plugins" onclick="window.location = '<?php echo osc_admin_base_url(true) . '?page=plugins'; ?>'; return false; "><?php _e('Available plugins') ; ?></a></li>
    </ul>
    <div id="market">
        <h2 class="render-title"><?php _e('Latest plugins available') ; ?></h2>
        <table id="market_plugins" class="table available-theme">
        </table>
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
        $( "#tabs" ).tabs({ selected: <?php echo $tab_index; ?> });

        $("#market_cancel").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            return false;
        });

        $("#market_install").on("click", function(){
            $(".ui-dialog-content").dialog("close");
            $('<div id="downloading"><div class="osc-modal-content"><?php echo osc_esc_js(__('Please wait until the download is completed')); ?></div></div>').dialog({ 
                title:'<?php echo osc_esc_js(__('Downloading')); ?>...',
                modal:true
            });

            $.getJSON(
            "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
            {"code" : $("#market_code").attr("value"), "section" : 'plugins'},
            function(data){
                var content = data.message ;
                if(data.error == 0) { // no errors
                    content += '<h3><?php echo osc_esc_js(__('The plugin has been downloaded correctly, proceed to install and configure.')); ?></h3>';
                    content += "<p>";
                    content += '<a class="btn btn-mini btn-green" href="<?php echo osc_admin_base_url(true); ?>?page=plugins&marketError='+data.error+'&slug='+data.data['s_update_url']+'"><?php echo osc_esc_js(__('Ok')); ?></a>';
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
                {"section" : "plugins", 'mPage' : page },
                function(data){
                    $("#market_plugins").html(" ");
                    $('#market_pagination').html(" ");
                    if(data!=null && data.plugins!=null) {
                        for(var i=0;i<data.plugins.length;i++) {
                            var description = $(data.plugins[i].s_description).text();
                            dots = '';
                            if(description.length > 80){
                                dots = '...';
                            }

                            var plugins_downloaded  = <?php $plugins_downloaded = getPreference('plugins_downloaded'); if($plugins_downloaded != ''){ echo $plugins_downloaded; } else { echo 'new Array()'; } ?>;
                            var plugin_to_update    = <?php echo getPreference('plugins_to_update'); ?>;
                            var button = '';

                            if(jQuery.inArray(data.plugins[i].s_update_url, plugins_downloaded) >= 0 ) {
                                if( jQuery.inArray(data.plugins[i].s_update_url, plugin_to_update) >= 0 ) {
                                    button = '<a href="#'+data.plugins[i].s_update_url+'" class="btn btn-mini btn-orange market-popup market_update"><?php echo osc_esc_js(__('Update')); ?></a>';
                                } else {
                                    button = '<a href="#" class="btn btn-mini btn-blue btn-disabled" ><?php echo osc_esc_js(__('Already downloaded')); ?></a>';
                                }
                            } else {
                                button = '<a href="#'+data.plugins[i].s_update_url+'" class="btn btn-mini btn-green market-popup"><?php echo osc_esc_js(__('Download plugin')); ?></a>';
                            }
                            even = '';
                            if (i%2 == 0){
                                even = 'even';
                            }
                            if(i==0){
                                even = even+' table-first-row';
                            }
                            $("#market_plugins").append('<tr class="plugin '+even+'">'
                                +'<td>'
                                +'<div class="plugin-info">'
                                    +'<h3>'+data.plugins[i].s_title+' '+data.plugins[i].s_version+' <?php _e('by') ; ?> '+data.plugins[i].s_contact_name+'</h3>'
                                +'</div>'
                                +'<div class="plugin-description">'
                                    +description.substring(0,80)+dots
                                +'</div>'
                                +'<div class="plugin-stage">'
                                    +'<div class="plugin-actions">'
                                        + button
                                    +'</div>'
                                +'</div>'
                                +'</td>'
                            +'</tr>');
                        }
                        // add pagination
                        $('#market_pagination').append(data.pagination_content);
                    }

                    $("#market_plugins").append('<div class="clear"></div>');
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
            {"code" : $(this).attr('href').replace('#',''), 'section' : 'plugins'},
            function(data){
                if(data!=null) {
                    $("#market_thumb").attr('src',data.s_thumbnail);
                    $("#market_code").attr("value", data.s_update_url);
                    $("#market_name").html(data.s_title);
                    $("#market_version").html(data.s_version);
                    $("#market_author").html(data.s_contact_name);
                    if(data.s_compatible.indexOf("<?php echo $main_version; ?>")==-1) {
                        $("#market_compatible").html(data.s_compatible + " - "  + "<?php echo osc_esc_js(sprintf(__('Warning! This plugin is not compatible with your current version of OSClass (%s)'), $main_version)); ?>");
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
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>