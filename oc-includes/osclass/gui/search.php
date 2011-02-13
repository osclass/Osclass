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


//require_once 'osclass/model/PluginCategory.php';

?>
<?php
    $sCategory = $this->_get('sCategory') ;
    $aCategories = $this->_get('aCategories') ;
    $orders = $this->_get('aOrders') ;
    $iOrderType = $this->_get('iOrderType') ;
    $sOrder = $this->_get('sOrder') ;
    $sPattern = $this->_get('sPattern') ;
    $iNumPages = $this->_get('iNumPages') ;
    $bPic = $this->_get('bPic') ;
    $sCity = $this->_get('sCity') ;
    $sPriceMin = $this->_get('sPriceMin') ;
    $sPriceMax = $this->_get('sPriceMax') ;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
    <head>
        <?php $this->osc_print_head() ; ?>
    </head>
    <body>

        <div class="container">

            <?php $this->osc_print_header() ; ?>

            <div id="form_publish">
                <?php include("inc.search.php"); ?>
                <strong class="publish_button"><a href="<?php echo osc_item_post_url($sCategory) ; ?>">Publish your ad for free</a></strong>
            </div>

            <div class="content list">

                <div id="main">

                    <div class="ad_list">

                        <div id="list_head">
                            <div class="inner">
                                <h1><strong><?php _e('Search results') ; ?></strong></h1>

                                <p class="see_by">
                                    <?php _e('Sort by:') ; ?>
                                    <?php $i = 0 ; ?>
                                    <?php foreach($orders as $label => $params) { ?>
                                        <?php if($sOrder == $params['sOrder'] && $iOrderType == $params['iOrderType']) { ?>
                                            <a class="current" href="<?php echo $this->osc_update_search_url($params) ; ?>"><?php echo $label; ?></a>
                                        <?php } else { ?>
                                            <a href="<?php echo $this->osc_update_search_url($params) ; ?>"><?php echo $label; ?></a>
                                        <?php } ?>
                                        <?php if ($i != count($orders)-1) { ?>
                                            <span>|</span>
                                        <?php } ?>
                                        <?php $i++ ; ?>
                                    <?php } ?>
                                    <!--<?php _e('Show as:'); ?> <a href="<?php echo $this->osc_update_search_url(array('showAs' => 'list')); ?>"><?php _e('List'); ?></a> or <a href="<?php echo $this->osc_update_search_url(array('showAs' => 'gallery', 'onlyPic' => 1)); ?>"><?php _e('image gallery'); ?></a>-->
                                </p>
                            </div>
                        </div>

                        <!--<div class="searchShowing" ><?php printf('Showing from %d to %d %s of a total of %d results.', ($start + 1), $end, $pattern, $totalItems); ?></div>-->

                        <?php if(!isset($items) || !is_array($items) || count($items) == 0) { ?>
                            <p class="empty" ><?php printf(__('There are no results matching "%s".'), $sPattern) ; ?></p>
                        <?php } else { ?>
                            <?php osc_renderView($showAs == 'list' ? 'search_list.php' : 'search_gallery.php') ; ?>
                        <?php } ?>

                        <div class="paginate" >
                        <?php for($i = 0 ; $i < $iNumPages ; $i++) {
                            if($i == $page) {
                                printf('<a class="searchPaginationSelected" href="%s">%d</a>', $this->osc_update_search_url(array('page' => $i)), ($i + 1));
                            } else {
                                printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', $this->osc_update_search_url(array('page' => $i)), ($i + 1));
                            }
                        } ?>
                        </div>
                    </div>
                </div>

                <div id="sidebar">

                    <div class="filters">
                        <form action="search.php" method="post">
                            <?php
                            foreach($_REQUEST as $k => $v) {
                                if($k!='osclass') {
                                    echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
                                }
                            }
                            ?>

                            <fieldset class="box location">
                                <h3><strong><?php _e('Location') ; ?></strong></h3>
                                <div class="row one_input">
                                    <h6><?php _e('City'); ?></h6>
                                    <input type="text" id="city" name="city" value="<?php echo $sCity ; ?>" />
                                </div>
                            </fieldset>

                            <fieldset class="box show_only">
                                <h3><strong><?php _e('Show only') ; ?></strong></h3>

                                <div class="row checkboxes">
                                    <ul>
                                        <li>
                                            <?php if($bPic) { ?>
                                                <input type="checkbox" name="bPic" id="withPicture" onchange="document.location = '<?php echo $this->osc_update_search_url(array('bPic' => 0)); ?>';" checked="checked" />
                                            <?php } else { ?>
                                                <input type="checkbox" name="bPic" id="withPicture" value="false" onchange="document.location = '<?php echo $this->osc_update_search_url(array('bPic' => 1)); ?>';" />
                                            <?php } ?>
                                            <label for="withPicture"><?php _e('Show only items with pictures') ; ?></label>
                                        </li>
                                    </ul>
                                </div>

                                <div class="row two_input">
                                    <h6><?php _e('Price') ; ?></h6>
                                    <?php _e('Min.') ; ?>
                                    <input type="text" id="priceMin" name="sPriceMin" value="<?php echo $sPriceMin ; ?>" size="6" maxlength="6" />
                                    <?php _e('Max.') ; ?>
                                    <input type="text" id="priceMax" name="sPriceMax" value="<?php echo $sPriceMax ; ?>" size="6" maxlength="6" />
                                </div>

                                <div class="row checkboxes">
                                    <h6><?php _e('Category'); ?></h6>
                                    <ul>
                                        <?php foreach($aCategories as $cat) { ?>
                                            <li>
                                                <?php if(in_array($cat['pk_i_id'], $cats)) { ?>
                                                    <input onchange="updateFilter();" type="checkbox" checked="checked" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><strong><?php echo $cat['s_name']; ?></strong></label>
                                                <?php } else { ?>
                                                    <input onchange="updateFilter();" type="checkbox" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><strong><?php echo $cat['s_name']; ?></strong></label>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </fieldset>

                            <?php
                                if($sCategory != '') {
                                    osc_run_hook('search_form', $sCategory) ;
                                } else {
                                    osc_run_hook('search_form') ;
                                }
                            ?>

                            <button type="submit"><?php _e('Apply') ; ?></button>
                        </form>

                        <?php $this->alert_form() ; ?>
                        
                    </div>
                </div>

                <script>
                $(function() {
                    function log( message ) {
                        $( "<div/>" ).text( message ).prependTo( "#log" );
                        $( "#log" ).attr( "scrollTop", 0 );
                    }

                    $( "#city" ).autocomplete({
                        source: "<?php echo WEB_PATH; ?>/oc-includes/osclass/ajax/location.php",
                        minLength: 2,
                        select: function( event, ui ) {
                            log( ui.item ?
                                "Selected: " + ui.item.value + " aka " + ui.item.id :
                                "Nothing selected, input was " + this.value );
                        }
                    });

                });

                </script>

            </div>

            <?php $this->osc_print_footer() ; ?>

        </div>

        <?php osc_show_flash_message() ; ?>

    </body>

</html>