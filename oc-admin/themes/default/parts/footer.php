    	</div><!-- #content-render -->
        <div id="footer">
            <?php osc_run_hook('admin_footer') ; ?>
        </div>
    </div><!-- #content -->
    <!-- javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo osc_current_admin_theme_js_url('jquery.min.js') ; ?>"></script>
        <script>
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
        $('#help-box .ico-close').click(function(){
            $('#help-box').hide();
        });
        $('#content-head .ico-help').click(function(){
            $('#help-box').fadeIn();
        });
        $('#table-row-actions .show-more').live('click',function(){
            $(this).addClass('hover');
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
    </script>
  </body>
</html>