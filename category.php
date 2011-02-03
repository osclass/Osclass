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

$_P = Preference::newInstance() ;

$category_manager = Category::newInstance();
$item_manager = Item::newInstance();

$theme = $_P->get('theme') ;

$limit = 10 ;
if (isset($_GET["page"])) {
    if ($_GET['page'] > 0) {
        $page = (int) $_GET["page"];
        $start = ((int) $_GET["page"] - 1 ) * 10;
    } else {
        $page = 1;
        $start = 0;
    }
} else {
    $page = 1;
    $start = 0;
}

if( isset($_GET['id']) ) {
    $category = $category_manager->findByPrimaryKey($_GET['id']);
} else {
    $s_categories = $_GET['slug'];
    $s_categories = preg_replace('|/$|','',$s_categories);
    $slug_categories = explode('/', $s_categories);

    $category = $category_manager->find_by_slug($slug_categories[count($slug_categories) - 1]);
}

if( count($category) > 0 ) {
    $ads = $item_manager->list_items($category, $start, $limit, 'ACTIVE');
    $ads_total = $item_manager->total_items($category);
    $ads = $ads['items'];
    $subCats = Category::newInstance()->findSubcategories($category);
    $subCats_ads = Category::newInstance()->findSubcategories($category, true);

    $headerConf = array('pageTitle' => $category['s_name'], 'category' => $category);
    osc_renderHeader($headerConf);
    osc_renderView('category.php');
    osc_renderFooter();
} else {
    osc_renderHeader($headerConf);
    osc_renderView('404.php');
    osc_renderFooter();
}

?>
