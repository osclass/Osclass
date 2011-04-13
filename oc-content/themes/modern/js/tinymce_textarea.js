tinyMCE.init({
    fix_list_elements : true,
    valid_elements : "b,strong,i,em,u,ul,ol,li,p[style],br,a[href|title],span[style]",
    entity_encoding : "raw",
    mode : "textareas",
    theme : "advanced",
    plugins : "autosave,inlinepopups,visualchars,wordcount",
    convert_urls : true,
    theme_advanced_buttons1 : "bold,italic,underline,|,forecolor,backcolor,|,link,unlink,|,fontsizeselect,fontselect",
    theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,justifyleft,justifycenter,justifyright,justifyfull,|,removeformat,code",
    theme_advanced_buttons3 : "",
    theme_advanced_buttons4 : "",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    formats : {
        alignleft : [
            {selector : 'p,ul,ol,li', styles : {textAlign : 'left'}}
        ],
        aligncenter : [
            {selector : 'p,ul,ol,li', styles : {textAlign : 'center'}}
        ],
        alignright : [
            {selector : 'p,ul,ol,li', styles : {textAlign : 'right'}}
        ],
        alignfull : [
            {selector : 'p,ul,ol,li', styles : {textAlign : 'justify'}}
        ]
    }
});
