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

    osc_add_hook('admin_page_header','customPageHeader');
    function customPageHeader(){ ?>
        <h1><?php _e('Appearance') ; ?></h1>
    <?php
    }

    function customPageTitle($string) {
        return sprintf(__('Appearance &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    osc_current_admin_theme_path( 'parts/header.php' ) ; ?>
<div id="appearance-page">
    <form id="market-quick-search" class="quick-search"><input type="text" name="sPattern" placeholder="<?php _e('Search Themes'); ?>" class="input-text float-left"/><input type="Submit" value="Seach" class="btn ico ico-32 ico-search float-left"/><a href="<?php echo osc_admin_base_url(true) ; ?>?page=appearance&amp;action=add" class="btn btn-green float-right"><?php _e('Add new theme'); ?></a></form>
    <!-- themes list -->
    <div class="appearance">
        <div id="tabs" class="ui-osc-tabs ui-tabs-right">
            <ul>
                <li><a href="#market"><?php _e('Market'); ?></a></li>
                <li><a href="#available-themes" onclick="window.location = '<?php echo osc_admin_base_url(true) . '?page=appearance'; ?>'; return false; "><?php _e('Available themes') ; ?></a></li>
            </ul>
            <div id="market">
                <h2 class="render-title"><?php _e('Latest themes on market') ; ?></h2>
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
                            <button id="market_install" class="btn btn-submit" ><?php echo osc_esc_html( __('Continue install') ) ; ?></button>
                        </div>
                    </div>
                </form>
            </div>
            
            </div>
        <script>
        $(function() {
            $( "#tabs" ).tabs({ selected: 2 });
            
            $("#market_cancel").on("click", function(){
                $(".ui-dialog-content").dialog("close");
                return false;
            });
            
            $("#market_install").on("click", function(){
                $(".ui-dialog-content").dialog("close");
                //$(".ui-dialog-content").dialog({title:'Downloading...'}).html('Please wait until the download is completed');
                $('<div id="downloading"><div class="osc-modal-content">Please wait until the download is completed</div></div>').dialog({title:'Installing...',modal:true});
                $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
                {"code" : $("#market_code").attr("value"), "section" : 'plugins'},
                function(data){
                    $("#downloading .osc-modal-content").html(data.message);
                    setTimeout(function(){
                      $(".ui-dialog-content").dialog("close");  
                  },1000);
                });
                return false;
            });
            
            function getMarketContent(fPage) 
            {
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
                                var imgsrc = '<?php echo osc_current_admin_theme("img/marketblank.jpg"); ?>';
                                if(data.themes[i].s_image!=null) {
                                    imgsrc = data.themes[i].s_image;
                                }
                                $("#market_themes").append('<div class="theme">'
                                    +'<div class="theme-stage">'
                                        +'<img src="'+imgsrc+'" title="'+data.themes[i].s_title+'" alt="'+data.themes[i].s_title+'" />'
                                        +'<div class="theme-actions">'
                                            +'<a href="#'+data.themes[i].s_slug+'" class="btn btn-mini btn-green market-popup"><?php _e('Install') ; ?></a>'
                                            +'<a target="_blank" href="'+data.themes[i].s_preview+'" class="btn btn-mini btn-blue"><?php _e('Preview') ; ?></a>'
                                        +'</div>'
                                    +'</div>'
                                    +'<div class="theme-info">'
                                        +'<h3>'+data.themes[i].s_title+' '+data.themes[i].s_version+' <?php _e('by') ; ?> <a target="_blank" href="">'+data.themes[i].s_contact_name+'</a></h3>'
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
            $.getJSON(
                "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_market",
                {"code" : $(this).attr('href').replace('#',''), 'section' : 'plugins'},
                function(data){
                    if(data!=null) {
                        $("#market_thumb").attr('src',data.s_thumbnail);
                        $("#market_code").attr("value", data.s_slug);
                        $("#market_name").html(data.s_title);
                        $("#market_version").html(data.s_version);
                        $("#market_author").html(data.s_contact_name);
                        $("#market_url").attr('href',data.s_source_file);

                        $('#market_installer').dialog({
                            modal:true,
                            title: '<?php echo osc_esc_js( __('OSClass Market') ) ; ?>',
                            class: 'osc-class-test',
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