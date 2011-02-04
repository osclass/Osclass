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


require_once LIB_PATH . 'osclass/model/PluginCategory.php';

?>
<link href="<?php echo osc_base_url() ; ?>/oc-includes/css/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script>
	$(function() {
		function log( message ) {
			$( "<div/>" ).text( message ).prependTo( "#log" );
			$( "#log" ).attr( "scrollTop", 0 );
		}

		$( "#city" ).autocomplete({
			source: "<?php echo osc_base_url() ; ?>/oc-includes/osclass/ajax/location.php",
			minLength: 2,
			select: function( event, ui ) {
				log( ui.item ?
					"Selected: " + ui.item.value + " aka " + ui.item.id :
					"Nothing selected, input was " + this.value );
			}
		});

	});

	</script>



<div class="sectionHeader" ><?php _e('Search results') ?></div>

<div class="searchPagination" >
<?php _e('Page'); ?>:
<?php
for($i = 0; $i < $numPages; $i++) {
	if($i == $page)
		printf('<a class="searchPaginationSelected" href="%s">%d</a>', osc_updateSearchURL(array('page' => $i)), ($i + 1));
	else
		printf('<a class="searchPaginationNonSelected" href="%s">%d</a>', osc_updateSearchURL(array('page' => $i)), ($i + 1));
}
?>
</div>

<div class="searchShowing" ><?php printf('Showing from %d to %d %s of a total of %d results.', ($start + 1), $end, $pattern, $totalItems); ?></div>
<div class="searchOptions" >
	<div class="searchGalleryList" >
		<?php _e('Show as:'); ?> <a href="<?php echo osc_updateSearchURL(array('showAs' => 'list')); ?>"><?php _e('List'); ?></a> or <a href="<?php echo osc_updateSearchURL(array('showAs' => 'gallery', 'onlyPic' => 1)); ?>"><?php _e('image gallery'); ?></a>.
	</div>
	<div class="searchOrder">
		<?php _e('Sort by:'); ?>
		<select name="sort" onchange="document.location = this.value;">
		<?php foreach($orders as $label => $params): ?>
			<?php if($orderColumn == $params['orderColumn'] && $orderDirection == $params['orderDirection']): ?>
			<option selected="selected" value="<?php echo osc_updateSearchURL($params); ?>"><?php echo $label; ?></option>
			<?php else: ?>
			<option value="<?php echo osc_updateSearchURL($params); ?>"><?php echo $label; ?></option>
			<?php endif; ?>
		<?php endforeach; ?>
		</select>
	</div>
	<div style="clear: both;"></div>
</div>

<style type="text/css">
div.hh {
	padding: 4px;
	background: url('<?php echo osc_themeResource('images/gradient.png'); ?>') repeat-x;
}
</style>

<div class="searchFormHolder" >
<form action="<?php echo osc_create_url('search');?>" method="POST" >

<?php
foreach($_REQUEST as $k => $v) {
    if($k!='osclass') {
        echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
    }
}
?>


<div class="searchForm" >
    <?php $search->alertForm(); ?>
	<div class="hh"><label for="city"><?php _e('City'); ?></label></div>
	<div id="d_city" name="d_city" >
	<input type="text" id="city" name="city" value="<?php echo $city; ?>" />
	</div>

	<div class="hh"><?php _e('Price'); ?></div>
	<div>
	<label for="priceMin"><?php _e('Minimum'); ?></label><br />
	<input type="text" id="priceMin" name="priceMin" value="<?php echo $priceMin; ?>" size="6" maxlength="6" /><br />
	<label for="priceMax"><?php _e('Maximum'); ?></label><br />
	<input type="text" id="priceMax" name="priceMax" value="<?php echo $priceMax; ?>" size="6" maxlength="6" /><br />
	</div>

	<div class="hh"><?php _e('Pictures'); ?></div>
	<div>
	<?php if($onlyPic): ?>
	<input type="checkbox" name="withPicture" id="withPicture" onchange="document.location = '<?php echo osc_updateSearchURL(array('onlyPic' => 0)); ?>';" checked="checked" />
	<?php else: ?>
	<input type="checkbox" name="withPicture" id="withPicture" value="false" onchange="document.location = '<?php echo osc_updateSearchURL(array('onlyPic' => 1)); ?>';" />
	<?php endif; ?>
	<label for="withPicture"><?php _e('Show only items with pictures'); ?></label></div>

	<div class="hh"><?php _e('Category'); ?></div>
	<div>
	<?php foreach($categories as $cat): ?>
	<?php if(in_array($cat['pk_i_id'], $cats)): ?>
	<input onchange="updateFilter();" type="checkbox" checked="checked" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><?php echo $cat['s_name']; ?></label><br />
	<?php else: ?>
	<input onchange="updateFilter();" type="checkbox" id="cat<?php echo $cat['pk_i_id']; ?>" /> <label for="cat<?php echo $cat['pk_i_id']; ?>"><?php echo $cat['s_name']; ?></label><br />
	<?php endif; ?>
	<?php endforeach; ?>
	</div>



	<div>
<?php

    if(isset($_REQUEST['catId'])) {
    	osc_run_hook('search_form', $_REQUEST['catId']);
    } else {
    	osc_run_hook('search_form');
    } 

?>
</div>
<input type="submit" value="<?php _e('Apply'); ?>" />
</form>

</div>

<?php if(!isset($items) || !is_array($items) || count($items) == 0): ?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#search_no_result').animate({ backgroundColor: "white"}, 3000);
			$('#search_no_result').animate({ color: "blue"}, 100);
		});
	</script>
	<div id="search_no_result" class="searchResults" ><?php printf(__('There are no results matching "%s".'), $pattern); ?></div>

	<div>
	<script type="text/javascript">
	function doSearch() { document.location = '<?php echo osc_base_url() ; ?>/search.php?pattern=' + encodeURIComponent(document.getElementById('searchPattern').value); }
	</script>
	<input onkeyup="if(event.keyCode == 13) doSearch();" type="text" name="pattern" id="searchPattern" value="<?php echo $pattern; ?>" /> <input type="button" value="<?php _e('Search'); ?>" onclick="doSearch();" />
	</div>
<?php else: ?>
<div class="searchResults" >
<?php osc_renderView($showAs == 'list' ? 'search_list.php' : 'search_gallery.php'); ?>
</div>
<?php endif; ?>

<div style="clear: both;"></div>

</div>
