$(document).ready(function() {
    $(".menu").accordion({
        active: false,
        collapsible: true,
        navigation: true,
        autoHeight: false,
        icons: { 'header': 'ui-icon-plus', 'headerSelected': 'ui-icon-minus' }
    }) ;

    if( jQuery.browser.msie && jQuery.browser.version.substr(0,1) < 7 ) {
        jQuery('#accordion *').css('zoom', '1');
    }

    // close alert messages
    $(".alert .close").bind("click", function(e) {
       $(this).parent().fadeOut('slow') ;
    }) ;
}) ;