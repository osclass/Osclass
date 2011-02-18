<script type="text/javascript">
    var sQuery = '<?php _e("ie. PHP Programmer") ; ?>' ;

    $(document).ready(function(){
        if($('input[name=sPattern]').val() == sQuery) {
            $('input[name=sPattern]').css('color', 'gray');
        }
        $('input[name=sPattern]').click(function(){
            if($('input[name=sPattern]').val() == sQuery) {
                $('input[name=sPattern]').val('');
                $('input[name=sPattern]').css('color', '');
            }
        });
        $('input[name=sPattern]').blur(function(){
            if($('input[name=sPattern]').val() == '') {
                $('input[name=sPattern]').val(sQuery);
                $('input[name=sPattern]').css('color', 'gray');
            }
        });
        $('input[name=sPattern]').keypress(function(){
            $('input[name=sPattern]').css('background','');
        })
    });
    function doSearch() {
        if($('input[name=sPattern]').val() == sQuery){
            return false;
        }
        if($('input[name=sPattern]').val().length < 3) {
            $('input[name=sPattern]').css('background', '#FFC6C6');
            return false;
        }
        return true;
    }
</script>


<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search" onsubmit="javascript:return doSearch();">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="sPattern"  id="sPattern" value="<?php echo ( osc_get_param('sPattern') ) ? osc_get_param('sPattern') : __("ie. PHP Programmer") ; ?>" />

        <?php if ( osc_count_categories() ) { ?>

            <?php osc_goto_first_category() ; ?>

            <select name="sCategory" id="sCategory">
                    <option value=""><?php _e("Select a category") ; ?></option>

                    <?php while ( osc_has_categories() ) { ?>
                        <option value="<?php echo osc_category_id() ; ?>"><?php echo osc_category_name() ; ?></option>

                        <?php if ( osc_count_subcategories() > 0 ) { ?>
                            <ul>
                                <?php while ( osc_has_subcategories() ) { ?>
                                    <option value="<?php echo osc_category_id() ; ?>">&nbsp;&nbsp;&nbsp;<?php echo osc_category_name() ; ?></option>
                                <?php } ?>
                            </ul>
                        <?php } ?>

                    <?php } ?>
            </select>
        <?php } ?>
        
        <button type="submit"><?php _e('Send') ; ?></button>
    </fieldset>
</form>
