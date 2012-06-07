$(function(){
	$('#sidebar ul.oscmenu > li > ul').each(function(){
        var $submenu = $(this);
        $submenu.parent().hover(function(){
            $submenu.css('margin-top',((80-$submenu.height())/2)-10);
        },function(){
        });
    });
    $('#sidebar ul.oscmenu > li').each(function(){
        $(this).hover(function(){
            $(this).addClass('hover');
        },function(){
            $(this).removeClass('hover');
        });
    });
    $('#show-more > ul').each(function(){
        var $submenu = $(this);
        $submenu.parent().hover(function(){
            $(this).addClass('hover');
        },function(){
            $(this).removeClass('hover');
        });
    });
    $('#hidden-menus > li').live('mouseenter',function(){
        var $submenu = $(this).find('ul');
        $(this).addClass('hover');
                    $submenu.css('top',($submenu.height()*-1)).css('margin-top','-22px');
    }).live('mouseleave',function(){
        $(this).removeClass('hover')
    });
    //Row actions
    $('.table .actions').each(function(){
        var $actions = $(this);
        var $rowActions = $('#table-row-actions');
        $(this).parents('tr').mouseenter(function(){
            var $containterOffset = $('.table-hast-actions').offset();
            $thisOffset = $(this).offset();
            $rowActions.empty().append($actions.clone()).css({
                width:$(this).width()-85,
                top:($thisOffset.top-$containterOffset.top)+$(this).height()
            }).show();
        });
    });
    $('.table-hast-actions').mouseleave(function(event){
        $('#table-row-actions').hide();
    })
    //Close help
    $('.flashmessage .ico-close').live('click',function(){
        $(this).parents('.flashmessage').hide();
    });
    $('#help-box .ico-close').click(function(){
        $('#help-box').hide();
    });
    $('#content-head .ico-help').click(function(){
        $('#help-box').fadeIn();
    });
    $('#table-row-actions .show-more-trigger').live('click',function(){
        $(this).parent().addClass('hover');
        return false;
    });
    //Selects
	$('select').each(function(){
        selectUi($(this));
    });
    //Set Layout
	$(window).resize(function(){
	    resetLayout();
	}).resize();

    $('#flashmessage:not(:empty)').show('fast',function(){
        //$(this).hide('slow');
    });
    $('.input-has-placeholder input:not([type="hidden"])').each(function(){
        var placeHolder = $(this).prev();
        var input = $(this);
        input.focus(function(){
            placeHolder.hide();
        }).blur(function(){
            if(input.val() == ''){
                placeHolder.show();
            }else{
                placeHolder.hide();
            }
        }).triggerHandler('blur');
        placeHolder.click(function(){
            input.focus();
        });
    });
    oscTab();
});
function oscTab(callback){
    $(".osc-tab").tabs();
}
function selectUi(thatSelect){
    var uiSelect = $('<a href="#" class="select-box-trigger"></a>');
    var uiSelectIcon = $('<span class="select-box-icon"><div class="ico ico-20 ico-drop-down"></div></span>');
    var uiSelected = $('<span class="select-box-label">'+thatSelect.find("option:selected").text()+'</span>');

    thatSelect.css('filter', 'alpha(opacity=40)').css('opacity', '0');
    thatSelect.wrap('<div class="select-box '+thatSelect.attr('class')+'" />');
    

    uiSelect.append(uiSelected).append(uiSelectIcon);
    thatSelect.parent().append(uiSelect);
    uiSelect.click(function(){
        return false;
    });
    thatSelect.change(function(){
        uiSelected.text(thatSelect.find('option:selected').text());
    });
}        
function resetLayout(){
    var headerHeight = 50;
    var menuItemHeight = 80;
    var height  = $(this).height()-headerHeight;
    var visible = Math.floor((height/menuItemHeight)-2)
    $('#sidebar').css('height',height);
    $('#sidebar ul.oscmenu > li').show();
    var hidden = $('#sidebar ul.oscmenu > li:gt('+visible+')');
    $('#hidden-menus').empty().append(hidden.clone()).css('width',(hidden.length*menuItemHeight));
    hidden.hide();
    //show more only if needs
    if((visible+2) > $('#sidebar ul.oscmenu > li').length){
        $('#show-more').hide();
    } else {
       $('#show-more').show(); 
    }
}