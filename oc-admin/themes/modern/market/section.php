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
    function addHelp() {
        echo '<p>' . __('Browse and download available Osclass plugins, from a constantly-updated selection. After downloading a plugin, you have to install it and configure it to get it up and running.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');
    osc_current_admin_theme_path('market/header.php');
    switch (Params::getParam("action")) {
        case 'plugins':
            $section = 'plugins';
            break;

        case 'themes':
            $section = 'themes';
            break;

        default:
            $section = false;
            break;
    }
    $title = array(
        'plugins' => __('Recommended plugins for You'),
        'themes'  => __('Recommended themes for You')
        );
?>
<div>
    <h2 class="section-title"><?php echo $title[$section]; ?></h2>
    <?php

    $marketPage = Params::getParam("mPage");
                    if($marketPage>=1) $marketPage-- ;

    $out    = osc_file_get_contents(osc_market_url($section)."page/".$marketPage);
    $array  = json_decode($out, true);


    $pageActual = $array['page'];
    $totalPages = ceil( $array['total'] / $array['sizePage'] );
    $params     = array(
        'total'    => $totalPages,
        'selected' => $pageActual,
        'url'      => osc_admin_base_url(true).'?page=market'.'&amp;action='.$section.'&amp;mPage={PAGE}',
        'sides'    => 5
    );
    // set pagination
    $pagination = new Pagination($params);
    $aux = $pagination->doPagination();

    foreach($array[$section] as $item){
        drawMarketItem($item);
        $i++;
    }
    echo '<div class="clear"></div><div class="has-pagination">'.$aux.'</div>';
    ?>
</div>
<script type="text/javascript">
$(function(){
    $(".ui-dialog-content a.more").live("click", function(){

        $(".ui-dialog-content").dialog("close");
        $('<div id="downloading"><div class="osc-modal-content"><?php echo osc_esc_js(__('Please wait until the download is completed')); ?></div></div>').dialog({title:'<?php echo osc_esc_js(__('Downloading')); ?>...',modal:true});

        var marketCode = $(this).attr('data-code');
        var marketType = $(this).attr('data-type')+'s';
        $.getJSON(
        "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market",
        {"code" : marketCode, "section" : marketType},
        function(data) {
            var content  = data.message ;
            var messages = theme.langs[marketType];
            if(data.error == 0) { // no errors
                content += '<h3>'+messages.download_ok+'</h3>';
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




    $('.mk-item').click(function(){
        var sizes = {
                 plugins:{width:645}
                ,themes:{width:445}
            }
        var section = $(this).attr('data-type');
        //marketData
        itemTemp = $('.mk-item').index($(this));
        var item = marketData.<?php echo $section; ?>[itemTemp];

        var description = $(item.s_description).text();
        dots = '';
        if(description.length > 120){
            dots = '...';
        }
        versions = item.s_compatible.split(',');
        banner = '';
        if(item.s_banner != null){
            banner = 'http://market.osclass.org/oc-content/uploads/market/'+item.s_banner;
        } else {
            banner = item.s_image;
        }
        screenshots = '';
        if(section == 'theme'){
            screenshots = '<tr>'
                +'<td colspan="3"><h4>'+theme.langs.screenshots+'</h4></td>'
            +'</tr>'
        }
        print =  '<div class="mk-item mk-item-'+section+'">'
                +'<div class="banner" style="background-image:url('+banner+');"></div>'
                +'<div class="mk-info">'
                +'<table>'
                    +'<tr>'
                        +'<td>'
                            +'<h3>'+item.s_title+'</h3>'
                            +'<i>'+theme.langs.by+' '+item.s_contact_name+'</i>'
                            +'<div class="description">'+description.substring(0,150)+dots+'</div>'
                        +'</td>'
                        +'<td class="spacer">'
                        +'</td>'
                        +'<td class="actions">'
                            +'<a class="more" data-code="'+item.s_update_url+'" data-type="'+section+'">'+theme.langs.download+' v.'+item.s_version+'</a>'
                            +'<a href="'+item.s_download+'" class="manual">'+theme.langs.download_manually+'</a>'
                            +'<span class="block"><strong>'+theme.langs.requieres_version+'</strong> '+versions[0]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.compatible_with+'</strong> '+versions[(versions.length-1)]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.downloads+'</strong> '+'22.000'+'</span>'
                        +'</td>'
                    +'</tr>'
                    +screenshots
                +'</table>'
                +'</div>'
            +'</div>';
        $(print).dialog({modal:true,
                dialogClass:'market-dialog',
                width:sizes.<?php echo $section; ?>.width,
                open: function (){
                    $(this).find('select, input, textarea, a').first().blur();
                    }
            });
    });
});
</script>
<?php osc_current_admin_theme_path( 'parts/footer.php' ) ; ?>
