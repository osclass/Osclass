function installMarketItem(thatItem){
    $(".ui-dialog-content").dialog("close");
    $('<div id="downloading"><div class="osc-modal-content"><div class="spinner"></div>'+theme.langs.wait_download+'</div></div>').dialog({title:theme.langs.downloading+'...',modal:true,width:'auto',position: ['center']});
        var marketCode = thatItem.attr('data-code');
        var marketType = thatItem.attr('data-type')+'s';
        // page to redirect once is installed
        var pageRedirect = 'plugins';
        if(marketType=='plugins') {
            pageRedirect = 'plugins';
        } else if(marketType=='themes') {
            pageRedirect = 'appearance';
        } else if(marketType=='languages') {
            pageRedirect = 'languages';
        }
        $.getJSON(
        theme.marketAjaxUrl,
        {"code" : marketCode, "section" : marketType},
        function(data) {
            var content  = data.message;
            var messages = theme.langs[marketType];
            if(data.error == 0) { // no errors
                content += '<h3>'+messages.download_ok+'</h3>';
                content += "<p>";
                content += '<a class="btn btn-mini btn-green" href="'+theme.adminBaseUrl+'?page='+pageRedirect+'&marketError='+data.error+'&slug='+data.data['s_update_url']+'">'+theme.langs.ok+'</a>';
                content += '<a class="btn btn-mini" href="javascript:location.reload(true)">'+theme.langs.close+'</a>';
                content += "</p>";
            } else {
                content += '<a class="btn btn-mini" href="javascript:location.reload(true)">'+theme.langs.close+'</a>';
            }
            $("#downloading .osc-modal-content").html(content);
            $("#downloading").dialog("option", "position", "center");
        });
}
function checkCompatibility(thatDialog){
    var notCompatible = thatDialog.hasClass('not-compatible');
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

            $(content).dialog({title:theme.langs.sure,modal:true});
    } else {
        installMarketItem(thatDialog);
    }
}
$(function(){
    $("body").on("click", '.ui-dialog-content a.more', function(){
        var notCompatible = $(this).parents('.ui-dialog');
        var thatDialog = $(this);

        $(".ui-dialog-content").dialog("close");

        checkCompatibility(thatDialog);
        return false;
    });

    $("body").on("click", '.ui-dialog-content a.diag-buy-btn', function(e) {
        e.stopPropagation();
        window.location = theme.adminBaseUrl + '?page=market&action=buy&' + theme.CSRFToken + '&url=' + $(this).attr("data-code");
    });


    $('.mk-item-parent').click(function(event){
        event.preventDefault();
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

        var item = null;

        // get json data
        $.getJSON(
            theme.adminBaseUrl+'?page=ajax&action=check_market',
            {"code" : $(this).attr('href').replace('#',''), 'section' : section},
            function(data){
                item = data;

                if(item!=null) {
                    var description = $(item.s_description).text();
                    var dots = '';
                    var versions = item.s_compatible.split(',');
                    var banner = false;
                    var screenshots = '';
                    var textButton = '';
                    var compatibleText = '';
                    var compatibleClass = '';
                    var str_letter = '';
                    if(thatItem.hasClass('not-compatible')) {
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
                        banner = item.s_banner_path+item.s_banner;
                    }
                    if(!banner){
                        banner = theme.themUrl+'images/gr-'+bg+'.png';
                        str_letter = letter;
                    }

                    var preview = '';
                    if(section == 'theme' && item.a_images){
                        if(item.a_images.length > 0){
                            if(item.s_preview != '') {
                                preview = '<a target="_blank" class="btn-market-preview" href="'+item.s_preview+'">'+theme.langs.preview_theme+'</a>';
                            }

                            screenshots = '<tr>'
                                +'<td colspan="3"><h4>'+theme.langs.screenshots+'</h4>';
                                for(i = 0; i < item.a_images.length; i++){
                                    screenshots += '<a class="fancybox screenshot" data-fancybox-group="'+item.s_title+'" href="'+item.a_images[i]['s_image']+'" ><img src="'+item.a_images[i]['s_thumbnail']+'" /></a>';
                                    if(i == 2) break;
                                }
                             screenshots += '</td></tr>';
                        }
                    }
                    if(section == 'language'){
                        str_letter =  item.s_update_url;
                    }

                    var _mod_date   = '';
                    if(new String(item.dt_mod_date)!='null') {
                        _mod_date = new Date(item.dt_mod_date);
                    } else {
                        _mod_date = new Date(item.dt_pub_date);
                    }

                    // format date
                    var date_mod    = _mod_date.getFullYear()+'-';

                    var _month      = new String(_mod_date.getMonth()+1);
                    if( _month.length == 1 ) {
                        date_mod += '0' + _month + '-';
                    } else {
                        date_mod += _month + '-';
                    }

                    var _day    = new String(_mod_date.getDate());
                    if( _day.length == 1 ) {
                        date_mod += '0' + _day;
                    } else {
                        date_mod += _day;
                    }

                    if(item.b_paid==0 && item.s_buy_url!=undefined) {
                        var actions_text = '<a class="diag-buy diag-buy-btn" data-code="'+item.s_buy_url+'" data-type="'+section+'">'+theme.langs.buy+' v.'+item.s_version+'</a>'
                            +'<span class="block"><strong>'+theme.langs.requieres_version+'</strong> '+versions[0]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.compatible_with+'</strong> '+versions[(versions.length-1)]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.downloads+'</strong> '+item.i_total_downloads+'</span>'
                            +'<span class="block"><strong>'+theme.langs.last_update+'</strong> '+date_mod+'</span>'
                            +'<a href="#" data-code="'+item.s_buy_url+'" class="diag-buy-btn manual-buy">'+theme.langs.buy+'</a>';
                    } else {
                        var actions_text = '<a class="more" data-code="'+item.s_update_url+'" data-type="'+section+'">'+theme.langs.download+' v.'+item.s_version+'</a>'
                            +'<span class="block"><strong>'+theme.langs.requieres_version+'</strong> '+versions[0]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.compatible_with+'</strong> '+versions[(versions.length-1)]+'</span>'
                            +'<span class="block"><strong>'+theme.langs.downloads+'</strong> '+item.i_total_downloads+'</span>'
                            +'<span class="block"><strong>'+theme.langs.last_update+'</strong> '+date_mod+'</span>'
                            +'<a href="'+item.s_source_file+'" class="manual">'+theme.langs.download_manually+'</a>';
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
                                        +'<p>'+preview+'</p>'
                                    +'</td>'
                                    +'<td class="spacer">'
                                    +'</td>'
                                    +'<td class="actions">'
                                        + actions_text
                                    +'</td>'
                                +'</tr>'
                                +screenshots
                            +'</table>'
                            +'</div>'
                        +'</div>';
                    var $print = $(print);
                    $print.find('.screenshot:last img').addClass('last');

                    $print.find('a.screenshot').fancybox({
                        openEffect : 'none',
                        closeEffect : 'none',
                        nextEffect : 'fade',
                        prevEffect : 'fade',
                        loop : false,
                        helpers : {
                            title : {
                            type : 'inside'
                            }
                        },
                        tpl: {
                            prev: '<a class="fancybox-nav fancybox-prev"><span></span></a>',
                            next: '<a class="fancybox-nav fancybox-next"><span></span></a>',
                            closeBtn : '<a class="fancybox-item fancybox-close" href="javascript:;"></a>'
                        }
                    });

                    $print.dialog({
                        dialogClass:'market-dialog '+compatibleClass,
                        title: compatibleText,
                        width:sizes[section+'s'].width,
                        overlay: { opacity: 0.5, background: 'black'},
                        modal:true,
                        open: function (){
                            $(this).find('a.manual').blur();
                        }
                    });
                } else {
                    // error trying to retrieve market api response
                    alert(theme.langs.error_item);
                }
            }
        );
        return false;

    });
    $('.mk-item-parent .download-btn').bind('click', function(e) {
        e.stopPropagation();
        checkCompatibility($(this));
    });
    $('.mk-item-parent .buy-btn').bind('click', function(e) {
        e.stopPropagation();
        window.location = theme.adminBaseUrl+'?page=market&action=buy&'+theme.CSRFToken+'&url='+$(this).attr("data-code");
    });
});
