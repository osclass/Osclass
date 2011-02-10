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


require_once 'osclass/model/PluginCategory.php';

?>

<div id="form_publish">
    <?php include("inc.search.php"); ?>
    <strong class="publish_button"><a href="<?php echo osc_createItemPostURL($catId); ?>">Publish your ad for free</a></strong>
</div>

<div class="content list">
    
    <div id="main">
        
        <div class="ad_list">
            
            <div id="list_head">
                <div class="inner">
                    <h1><strong><?php _e('Search results') ?></strong></h1>
                    
                    <p class="see_by">
                        <?php _e('Sort by:'); ?>
                        <?php 
                        $i = 0;
                        foreach($orders as $label => $params): ?>
                			<?php if($orderColumn == $params['orderColumn'] && $orderDirection == $params['orderDirection']): ?>
                			    <a class="current" href="<?php echo osc_updateSearchURL($params); ?>"><?php echo $label; ?></a>
                			<?php else: ?>
                			    <a href="<?php echo osc_updateSearchURL($params); ?>"><?php echo $label; ?></a>
                			<?php endif; ?>
                			<?php if ($i != count($orders)-1):?><span>|</span><?php endif; ?>
                			
                			<?php $i++; ?>
                		<?php endforeach; ?>                        
                        <!--<?php _e('Show as:'); ?> <a href="<?php echo osc_updateSearchURL(array('showAs' => 'list')); ?>"><?php _e('List'); ?></a> or <a href="<?php echo osc_updateSearchURL(array('showAs' => 'gallery', 'onlyPic' => 1)); ?>"><?php _e('image gallery'); ?></a>-->
                    </p>
                </div>
            </div>
            
            <!--<div class="searchShowing" ><?php printf('Showing from %d to %d %s of a total of %d results.', ($start + 1), $end, $pattern, $totalItems); ?></div>-->

            <?php if(!isset($items) || !is_array($items) || count($items) == 0): ?>
            	<p class="empty" ><?php printf(__('There are no results matching "%s".'), $pattern); ?></p>
            <?php else: ?>
                <?php osc_renderView($showAs == 'list' ? 'search_list.php' : 'search_gallery.php'); ?>
            <?php endif; ?>
            
            <div class="paginate" >
            <?php
            for($i = 0; $i < $numPages; $i++) {
            	if($i == $page)
            		printf('<a class="searchPaginationSelected" href="%s">%d</a>', osc_updateSearchURL(array('page' => $i)), ($i + 1));
            	else
            		printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', osc_updateSearchURL(array('page' => $i)), ($i + 1));
            }
            ?>
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
                    <h3><strong><?php _e('Location'); ?></strong></h3>
                    <div class="row one_input">
                        <h6><?php _e('City'); ?></h6>
                        <input type="text" id="city" name="city" value="<?php echo $city; ?>" />
                    </div>
                </fieldset>
            
                <fieldset class="box show_only">
                    <h3><strong><?php _e('Show only'); ?></strong></h3>
                
                    <div class="row checkboxes">
                        <ul>
                            <li>
                                <?php if($onlyPic): ?>
                            	    <input type="checkbox" name="withPicture" id="withPicture" onchange="document.location = '<?php echo osc_updateSearchURL(array('onlyPic' => 0)); ?>';" checked="checked" />
                            	<?php else: ?>
                            	    <input type="checkbox" name="withPicture" id="withPicture" value="false" onchange="document.location = '<?php echo osc_updateSearchURL(array('onlyPic' => 1)); ?>';" />
                            	<?php endif; ?>
                            	<label for="withPicture"><?php _e('Show only items with pictures'); ?></label>
                            </li>
                        </ul>
                    </div>
                            	
                    <div class="row two_input">
                        <h6><?php _e('Price'); ?></h6>
                        <?php _e('Min.'); ?>
                        <input type="text" id="priceMin" name="priceMin" value="<?php echo $priceMin; ?>" size="6" maxlength="6" />
                        <?php _e('Max.'); ?>
                        <input type="text" id="priceMax" name="priceMax" value="<?php echo $priceMax; ?>" size="6" maxlength="6" />
                    </div>
                
                    <div class="row checkboxes">
                        <h6><?php _e('Category'); ?></h6>
                        <ul>
                            <?php foreach($categories as $cat): ?>
                            <li>
                            	<?php if(in_array($cat['pk_i_id'], $cats)): ?>
                            	    <input onchange="updateFilter();" type="checkbox" checked="checked" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><strong><?php echo $cat['s_name']; ?></strong></label>
                            	<?php else: ?>
                            	    <input onchange="updateFilter();" type="checkbox" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><strong><?php echo $cat['s_name']; ?></strong></label>
                            	<?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </fieldset>    

                <?php
                    if(isset($_REQUEST['catId'])) {
                    	osc_runHook('search_form', $_REQUEST['catId']);
                    } else {
                    	osc_runHook('search_form');
                    } 
                ?>
            
                <button type="submit"><?php _e('Apply'); ?></button>
            </form>
            
            <?php $search->alertForm(); ?>
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