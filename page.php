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


$preferences = Preference::newInstance()->toArray();

$pageId = intval(osc_paramGet('id', 0));
$page = Page::newInstance()->findByPrimaryKey($pageId);
if(is_null($page)) {
	osc_renderError(404, __('The page you are looking for does not exist.'));
} else {
    if(isset($_SESSION['locale'])) {
        $locale = $_SESSION['locale'];
    } else {
		$locale = Preference::newInstance()->findValueByName('language');
	}
	if(isset($page['locale'][$locale])) {
		$page['s_title'] = $page['locale'][$locale]['s_title'];
		$page['s_text'] = $page['locale'][$locale]['s_text'];
	} else {
		$data = current($page['locale']);
		$page['s_title'] = $data['s_title'];
		$page['s_text'] = $data['s_text'];
		unset($data);
	}
	$headerConf = array(
            'pageTitle' => $page['s_title'] . ' - '.$preferences['pageTitle'],
            'pageDescription' => $page['s_title'].','.substr(stripslashes(trim(strip_tags($page['s_text']))),0,139).','.$page['s_title'] ,
        );
	osc_renderHeader($headerConf);
	osc_renderView('page.php');
	osc_renderFooter();
}

?>