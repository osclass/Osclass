<form action="<?php echo osc_base_url(true) ; ?>" method="post" class="search">
    <input type="hidden" name="page" value="search" />
    <fieldset class="main">
        <input type="text" name="sPattern"  id="sPattern" value="<?php echo ( isset($sPattern) ) ? $sPattern : __("ie. PHP Programmer") ; ?>" />

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
