$(document).ready(function() {
    $(".menu").accordion({
        active: false,
        collapsible: true,
        navigation: true,
        autoHeight: false,
        navigationFilter: function() {
            var menu_url = this.href.toLowerCase() ;
            var res_url = '' ;
            var loc_url = location.href.toLowerCase() ;

            var matches = loc_url.match(/(.*?)\?(.*)/) ;

            if( matches == null ) {
                return false ;
            }

            if( matches.length != 3 ) {
                return false ;
            }

            var params = matches[2] ;
            var params_res = '' ;
            var obj = params.split('&') ;
            for(var i = 0; i < obj.length; i++) {
                var chunk = obj[i].split('=') ;
                if( chunk.length > 0 ) {
                    if( jQuery.inArray(chunk[0], ['page', 'action', 'file']) >= 0 ) {
                        if( params_res == '' ) {
                            params_res += obj[i] ;
                        } else {
                            params_res += '&' + obj[i] ;
                        }
                    }
                }
            }
            res_url = matches[1] + '?' + params_res ;
            return menu_url === res_url ;
        },
        icons: {'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus'}
    }) ;

    if( jQuery.browser.msie && jQuery.browser.version.substr(0,1) < 7 ) {
        jQuery('#accordion *').css('zoom', '1');
    }

    // close FlashMessage messages
    $(".FlashMessage .close").bind("click", function(e) {
       $(this).parent().fadeOut('slow') ;
    }) ;
}) ;