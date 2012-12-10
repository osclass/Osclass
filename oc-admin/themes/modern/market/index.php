<?php
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
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
    osc_enqueue_style('market', osc_current_admin_theme_styles_url('market.css'));
    if(!function_exists('addBodyClass')){
        function addBodyClass($array){
                   $array[] = 'market';
            return $array;
        }
    }
    osc_add_filter('admin_body_class','addBodyClass');
    function addHelp() {
        echo '<p>' . __('Browse and download available Osclass plugins, from a constantly-updated selection. After downloading a plugin, you have to install it and configure it to get it up and running.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader() { ?>
        <h1><?php _e('Market') ; ?></h1>
        <ul class="tabs">
            <li class="active"><a href=""><?php _e('Market'); ?></a></li>
            <li><a href=""><?php _e('Plugins'); ?></a></li>
            <li><a href=""><?php _e('Themes'); ?></a></li>
            <li><a href=""><?php _e('Languages'); ?></a></li>
        </ul>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return __('Market');
    }
    osc_add_filter('admin_title', 'customPageTitle');
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="tabs" class="ui-osc-tabs ui-tabs-right">
    HOME MARKET
</div>
<script type="text/javascript">
    $(function() {

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
                        $("#market_compatible").html(data.s_compatible + " - "  + "<?php echo osc_esc_js(sprintf(__('Warning! This plugin is not compatible with your current version of Osclass (%s)'), $main_version)); ?>");
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
                        title: '<?php echo osc_esc_js( __('Osclass Market') ) ; ?>',
                        width:485
                    });
                }
            }
        );

        return false;
    });
</script>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>