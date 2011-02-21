<?php

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

?>

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
        <input type="text" name="sPattern"  id="sPattern" value="<?php echo ( osc_search_pattern()!='' ) ? osc_search_pattern() : __("ie. PHP Programmer") ; ?>" />

        <?php  if ( osc_count_categories() ) { ?>

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
        <?php  } ?>
        
        <button type="submit"><?php _e('Search') ; ?></button>
    </fieldset>
</form>
