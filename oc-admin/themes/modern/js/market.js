$(function(){
    $(".ui-dialog-content a.more").live("click", function(){
        $(".ui-dialog-content").dialog("close");
        $('<div id="downloading"><div class="osc-modal-content">'+theme.langs.wait_download+'</div></div>').dialog({title:theme.langs.downloading+'...',modal:true});
        var marketCode = $(this).attr('data-code');
        var marketType = $(this).attr('data-type')+'s';
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
        return false;
    });




    $('.mk-item').click(function(){
        var sizes = {
                 plugins:{width:645}
                ,themes:{width:445}
            }
        var section = $(this).attr('data-type');
        //marketData
        itemTemp = $('.mk-item-'+section).index($(this));
        var item = marketData[section+'s'][itemTemp];

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
                width:sizes[section+'s'].width,
                open: function (){
                    $(this).find('select, input, textarea, a').first().blur();
                    }
            });
    });
});