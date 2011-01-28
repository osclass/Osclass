<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'oc-load.php';

$managePages = new Page();
$managePreferences = new Preference();

$preferences = $managePreferences->toArray();

if(isset($_REQUEST['id']) && $_REQUEST['id']!='') {
    $page = $managePages->findByPrimaryKey($_REQUEST['id']);
} else if(isset($_REQUEST['slug']) && $_REQUEST['slug']!='') {
    $page = $managePages->findByInternalName($_REQUEST['slug']);
} else {
    osc_redirectTo(osc_indexURL());
}

if(file_exists(ABS_PATH . 'oc-content/themes/' . $preferences['theme'] . '/' . $page['s_internal_name'].".php")) {
    osc_renderHeader();
    osc_renderView($page['s_internal_name'].".php");
    osc_renderFooter();
} else if(file_exists(ABS_PATH . 'oc-content/themes/' . $preferences['theme'] . '/pages/' . $page['s_internal_name'].".php")) {
    osc_renderHeader();
    osc_renderView($page['s_internal_name'].".php");
    osc_renderFooter();
} else {

    if( (count($page) == 0) || $page['b_indelible']) {
        $headerConf = array(
            'pageTitle' => __('Page not found') . ' - '.$preferences['pageTitle'],
        );
        osc_renderHeader($headerConf);
        osc_renderView('errorPage.php');
        osc_renderFooter();
    } else {
        if(isset($_SESSION['locale'])) {
            $locale = $_SESSION['locale'];
        } else {
            $locale = $managePreferences->findValueByName('language');
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

        global $osc_request;
        $osc_request['section'] = $page['s_title'];
        
        $headerConf = array(
            'pageTitle' => $page['s_title'] . ' - '.$preferences['pageTitle'],
        );
        osc_renderHeader($headerConf);
        osc_renderView('page.php');
        osc_renderFooter();
    }
}
?>
