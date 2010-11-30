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

require_once 'oc-load.php';
global $search;
$search = Search::newInstance();

function osc_updateSearchURL($params, $delimiter = '&amp;') {
	$merged = array_merge($_REQUEST, $params);
	return WEB_PATH . '/search.php?' . http_build_query($merged, '', $delimiter);
}

$categories = Category::newInstance()->findRootCategories();
$preferences = Preference::newInstance()->toArray();

$pattern = strip_tags(osc_paramRequest('pattern', ''));
$page = intval(osc_paramRequest('page', 0));


// NOT SURE WHAT DOES THIS 
$cats = array();
foreach($categories as $cat)
	$cats[] = $cat['pk_i_id'];
if(isset($_REQUEST['cats']))
	$cats = $_REQUEST['cats'];
// UNKNOW CODE ENDS


if(isset($_REQUEST['catId'])) {
    $search->addCategory((int)($_REQUEST['catId']));
}

if(isset($_REQUEST['category'])) {
    $s_categories = $_REQUEST['category'];
    $s_categories = preg_replace('|/$|','',$s_categories);
    $slug_categories = explode('/', $s_categories);

    $search->addCategory($slug_categories[count($slug_categories) - 1]);
}

$onlyPic = false;
if(isset($_REQUEST['onlyPic'])) {
    $search->withPicture(true);
    //next line is deprecated
	$onlyPic = $_REQUEST['onlyPic'] == 1;
}


// ORDER
$orderColumn = 'dt_pub_date';
if(isset($_REQUEST['orderColumn']) && !empty($_REQUEST['orderColumn'])) 
	$orderColumn = $_REQUEST['orderColumn'];

$orderDirection = 'DESC';
if(isset($_REQUEST['orderDirection']) && !empty($_REQUEST['orderDirection']))
	$orderDirection = $_REQUEST['orderDirection'];

$search->order($orderColumn, $orderDirection);
// END OF ORDER

if(!isset($_REQUEST['pagesize'])) {
    if(isset($_REQUEST['feed'])) {
        $itemsPerPage = (isset($preferences['num_rss_items'])) ? (int) $preferences['num_rss_items'] : 50 ;
    } else {
        $itemsPerPage = 10;
    }
} else {
    $itemsPerPage = (is_int((int)($_REQUEST['pagesize'])))?$_REQUEST['pagesize']:10;
}

$search->page($page, $itemsPerPage);

// COMPABILITY ISSUES (DEPRECATED)
global $conditions;
global $search_tables;
global $plugins_tables;
$conditions = array();
$plugins_tables = "";
$search_tables = array();

$search->addConditions(sprintf("(d.s_title LIKE '%%%s%%' OR d.s_description LIKE '%%%s%%')", $pattern, $pattern));

$priceMin = osc_paramRequest('priceMin', null);
/*if(!is_null($priceMin) && $priceMin!="")
	$conditions[] = sprintf("f_price >= %f", $priceMin);*/

$priceMax = osc_paramRequest('priceMax', null);
/*if(!is_null($priceMax) && $priceMax!="")
	$conditions[] = sprintf("f_price <= %f", $priceMax);*/

$search->priceRange($priceMin, $priceMax);

osc_runHook('search_conditions', $_REQUEST);

$city = osc_paramRequest('city');
if(isset($_REQUEST['cities']) && count($_REQUEST['cities'])>0) {
    foreach($_REQUEST['cities'] as $city) {
        $search->addCity($city);
    }
//ELSE IF COMPABILITY ISSUES
} else if(isset($city) && $city!="") {
    $search->addCity($city);
}

$search->addConditions(sprintf("%st_item.e_status = 'ACTIVE' ", DB_TABLE_PREFIX));


// RETRIEVE ITEMS AND TOTAL
$totalItems = $search->count();
$items = $search->search();

if(!isset($_REQUEST['feed'])) {

    // NORMAL SEARCH
    // FANCYNESS
    $start = $page * $itemsPerPage;
    $end = min(($page+1) * $itemsPerPage, $totalItems);

    $orders = array(
	    __('Newly listed') => array('orderColumn' => 'dt_pub_date', 'orderDirection' => 'DESC'),
	    __('Lower price first') => array('orderColumn' => 'f_price', 'orderDirection' => 'ASC'),
	    __('Higher price first') => array('orderColumn' => 'f_price', 'orderDirection' => 'DESC'),
	    __('Best match') => array('orderColumn' => null, 'orderDirection' => null)
    );


    $numPages = ceil($totalItems / $itemsPerPage);


    $validShowValues = array('list', 'gallery');
    $showAs = 'list';
    if(isset($_REQUEST['showAs']) && in_array($_REQUEST['showAs'], $validShowValues))
	    $showAs = $_REQUEST['showAs'];


    osc_renderHeader(array('pageTitle' => sprintf(__('Search results for %s'), $pattern)));
    osc_renderView('search.php');
    osc_renderFooter();

} else {

    if($_REQUEST['feed']==null) {
        // FEED REQUESTED!
        header('Content-type: text/xml; charset=utf-8');

        $feed = new RSSFeed;
        $feed->setTitle(__('Latest items added') . ' - ' . $preferences["pageTitle"]);
        $feed->setLink(ABS_WEB_URL);
        $feed->setDescription(__('Latest items added in') . ' ' . $preferences["pageTitle"]);

        foreach($items as $item) {
            $feed->addItem(array(
                'title' => $item['s_title'],
                'link' => osc_createItemURL($item, true),
                'description' => $item['s_description']
            ));
        }

        $feed->dumpXML();
    } else {
        osc_runHook('feed_'.$_REQUEST['feed'], $items);
    }

}
?>
