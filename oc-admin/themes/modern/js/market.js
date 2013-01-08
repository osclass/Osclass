function installMarketItem(thatItem){
    $(".ui-dialog-content").dialog("close");
    $('<div id="downloading"><div class="osc-modal-content">'+theme.langs.wait_download+'</div></div>').dialog({title:theme.langs.downloading+'...',modal:true});
        var marketCode = thatItem.attr('data-code');
        var marketType = thatItem.attr('data-type')+'s';
        $.getJSON(
        theme.adminBaseUrl+"?page=ajax&action=market",
        {"code" : marketCode, "section" : marketType},
        function(data) {
            var content  = data.message ;
            var messages = theme.langs[marketType];
            if(data.error == 0) { // no errors
                content += '<h3>'+messages.download_ok+'</h3>';
                content += "<p>";
                content += '<a class="btn btn-mini btn-green" href="'+theme.adminBaseUrl+'?page=appearance&marketError='+data.error+'&slug='+data.data['s_update_url']+'">'+theme.langs.ok+'</a>';
                content += '<a class="btn btn-mini" href="javascript:location.reload(true)">'+theme.langs.close+'</a>';
                content += "</p>";
            } else {
                content += '<a class="btn btn-mini" href="javascript:location.reload(true)">'+theme.langs.close+'</a>';
            }
            $("#downloading .osc-modal-content").html(content);
        });
}
$(function(){
    $(".ui-dialog-content a.more").live("click", function(){
        var notCompatible = $(this).parents('.ui-dialog').hasClass('not-compatible');
        var thatDialog = $(this);

        $(".ui-dialog-content").dialog("close");
        if(notCompatible){
                content   = $('<div id="not-compatible-prompt"></div>');
                container = $('<div id="not-compatible-prompt"></div>');
                actions   = $('<p></p>');
                btnOk     = $('<a class="btn btn-mini">'+theme.langs.proceed_anyway_btn+'</a>');
                btnClose  = $('<a class="btn btn-mini btn-red">'+theme.langs.close+'</a>');

                btnOk.click(function(){
                    installMarketItem(thatDialog);
                });

                btnClose.click(function(){
                    $(".ui-dialog-content").dialog("close");
                });

                content.append(container.append(theme.langs.proceed_anyway).append(actions.append(btnClose).append(btnOk)));

            $(content).dialog({title:theme.langs.srue,modal:true});
        } else {
            installMarketItem(thatDialog);
        }
        return false;
    });


    $('.mk-item-parent').click(function(){
        var thatItem = $(this);
        var sizes = {
                         plugins:{width:645}
                        ,languages:{width:645}
                        ,themes:{width:445}
                    }
                var section = thatItem.attr('data-type');
                var bg = thatItem.attr('data-gr');
                var letter = thatItem.attr('data-letter');
                itemTemp = $('a[data-type="'+section+'"]').index($(this));
                var item = market_data[section+'s'][section+'s'][itemTemp];
                var description = $(item.s_description).text();
                console.log(item);
                console.log(description);
                console.log(item.s_description);
                var dots = '';
                var versions = item.s_compatible.split(',');
                var banner = false;
                var screenshots = '';
                var textButton = '';
                var compatibleText = '';
                var compatibleClass = '';
                var str_letter = '';

                if(item.s_compatible.indexOf(osc_market.main_version) == -1) {
                    compatibleText = item.s_compatible + " - "  + theme.langs.not_compatible;
                    compatibleClass = 'not-compatible';
                    textButton = theme.langs.update;
                }

                if(description.length > 120){
                    dots = '...';
                }
                if(item.s_image) {
                    banner = item.s_image;
                }
                if(item.s_banner != null){
                    banner = 'http://market.osclass.org/oc-content/uploads/market/'+item.s_banner;
                }
                if(!banner){
                    banner = theme.themUrl+'images/gr-'+bg+'.png';
                    str_letter = letter;
                }
                if(section == 'theme'){
                    if(item.a_images.length > 0){
                    screenshots = '<tr>'
                        +'<td colspan="3"><h4>'+theme.langs.screenshots+'</h4>';
                        for(i = 0; i < item.a_images.length; i++){
                            screenshots += '<a href="'+item.a_images[i]['s_image']+'" class="screnshot"><img src="'+item.a_images[i]['s_thumbnail']+'" /></a>';
                            if(i == 2) break;
                        }
                     screenshots += '</td></tr>';
                    }
                }
                if(section == 'language'){
                    str_letter =  item.s_update_url;
                }
                print = '<div class="mk-item mk-item-'+section+'">'
                        +'<div class="banner" style="background-image:url('+banner+');">'+str_letter+'</div>'
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
                                    +'<span class="block"><strong>'+theme.langs.downloads+'</strong> '+item.i_total_downloads+'</span>'
                                +'</td>'
                            +'</tr>'
                            +screenshots
                        +'</table>'
                        +'</div>'
                    +'</div>';
                var $print = $(print);
                    $print.find('.screnshot:last img').addClass('last');
                    $print.find('a.screnshot').click(function(){
                        $('<img />').attr("src", $(this).attr('href')).load(function(){
                            $(this).dialog({
                                dialogClass:'market-dialog '+compatibleClass,
                                width:'auto',
                                modal:true
                            });
                        });
                        return false;
                    });
                $print.dialog({
                        dialogClass:'market-dialog '+compatibleClass,
                        title: compatibleText,
                        width:sizes[section+'s'].width,
                        overlay: { opacity: 0.5, background: 'black'},
                        modal:true,
                         open: function (){
                            $(this).find('select, input, textarea, a').first().blur();
                            }
                    });
        /*
    $.getJSON(
        theme.adminBaseUrl+'?page=ajax&action=check_market',
        {"code" : $(this).attr('href').replace('#',''), 'section' : 'themes'},
        function(data){
            if(data!=null) {
                var sizes = {
                         plugins:{width:645}
                        ,languages:{width:645}
                        ,themes:{width:445}
                    }
                var section = thatItem.attr('data-type');
                itemTemp = $('a[data-type="'+section+'"]').index($(this));
                var item = data;
                var description = $(item.s_description).text();
                var dots = '';
                var versions = item.s_compatible.split(',');
                var banner = '';
                var screenshots = '';
                var textButton = '';
                var compatibleText = '';
                var compatibleClass = '';

                if(data.s_compatible.indexOf(osc_market.main_version) == -1) {
                    compatibleText = data.s_compatible + " - "  + theme.langs.not_compatible;
                    compatibleClass = 'not-compatible';
                    textButton = theme.langs.update;
                }

                if(description.length > 120){
                    dots = '...';
                }
                if(item.s_banner != null){
                    banner = 'http://market.osclass.org/oc-content/uploads/market/'+item.s_banner;
                } else {
                    banner = item.s_image;
                }
                if(section == 'theme'){
                    screenshots = '<tr>'
                        +'<td colspan="3"><h4>'+theme.langs.screenshots+'</h4></td>'
                    +'</tr>'
                }
                print = '<div class="mk-item mk-item-'+section+'">'
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
                                    +'<span class="block"><strong>'+theme.langs.downloads+'</strong> '+item.i_total_downloads+'</span>'
                                +'</td>'
                            +'</tr>'
                            +screenshots
                        +'</table>'
                        +'</div>'
                    +'</div>';
                $(print).dialog({
                        dialogClass:'market-dialog '+compatibleClass,
                        title: compatibleText,
                        width:sizes[section+'s'].width,
                        overlay: { opacity: 0.5, background: 'black'},
                        modal:true,
                         open: function (){
                            $(this).find('select, input, textarea, a').first().blur();
                            }
                    });


            }
        }
    );*/
    return false;

    });
});