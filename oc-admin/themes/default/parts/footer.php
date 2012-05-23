    	</div><!-- #content-render -->
        <div id="footer">
            <?php osc_run_hook('admin_footer') ; ?>
        </div>
    </div><!-- #content -->
    <!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script>
    /*$(function(){
    	$('#sidebar ul.oscmenu > li > ul').each(function(){
    		var $submenu = $(this);
    		var $submenuTemp;
    		$submenu.parent().mouseenter(function(event){
    			event.stopPropagation();
    			_top = this.offsetTop+50-$('#sidebar').scrollTop();
    			console.log(' ====== '+_top);
    			$submenuSidebar = $('#submenu-sidebar').empty().css({top:_top}).show();
	    		$submenuTemp = $submenu.clone().appendTo($submenuSidebar);
	    	});
    	});
    	$('#content').hover(function() {
			$('#submenu-sidebar').hide();
		});
 		$('#submenu-sidebar').mouseenter(function(event){
			$('#submenu-sidebar').show();
 		});
    });*/
    $(function(){
        $('#sidebar ul.oscmenu > li > ul').each(function(){
            var $submenu = $(this);
            $submenu.parent().hover(function(){
                $submenu.css('margin-top',((80-$submenu.height())/2)-10);
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
            console.log($submenu.height());
            console.log($submenu);
            $(this).addClass('hover');
                        $submenu.css('top',($submenu.height()*-1)).css('margin-top','-22px');

        }).live('mouseleave',function(){
            $(this).removeClass('hover')
        });
    });
    $(window).resize(function(){
        resetLayout();
    }).resize();
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
    //Row actions
    $('.table-row-actions .actions').each(function(){
        var $actions = $(this);
        $(this).parents('tr').mouseenter(function(){
            $actions.css('width',$(this).width()-85);
        });
    });
    $('tr').hover(function(){
        $(this).addClass('hover');
    },function(){
        $(this).removeClass('hover');
    });
    /*
    $('.table-row-actions').each(function(){
        $actions = $(this)
        $(this).parents('tr:first').hover(function(){
            $actions.css('width',$(this).width()-85).show();
        },function(){
            $actions.hide();
        });
    });*/
    //$('.table tr td:last-child, .table tr th:last-child').addClass('table-last')
    </script>
  </body>
</html>