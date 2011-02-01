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

global $preferences;
$mPreferences = new Preference();
$preferences = $mPreferences->toArray();
$categories = Category::newInstance()->toTree();
if(isset($_GET['theme'])) $preferences['theme'] = $_GET['theme'];

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
switch($action) {
    case 'sitemap':
        osc_runHook('before_sitemap');
        $sm = new Sitemap();
        $mPages = new Page();
        $aPages = $mPages->listAll(false);

        foreach($aPages as $page) {
            $sm->addURL(osc_createPageURL($page), 'weekly', 0.8);
        }

        $categories = Category::newInstance()->listAll();
        foreach($categories as $c) {
            $sm->addURL(osc_createCategoryURL($c), 'daily', 0.9);
        }
        $sm->toStdout();
        osc_runHook('after_sitemap');
        break;
    case 'feed':
        header('Content-type: text/xml; charset=utf-8');

        $feed = new RSSFeed();
        $feed->setTitle(__('Latest items added') . ' - ' . $preferences["pageTitle"]);
        $feed->setLink(ABS_WEB_URL);
        $feed->setDescription(__('Latest items added in') . ' ' . $preferences["pageTitle"]);

        $num_items = (isset($preferences['num_rss_items'])) ? (int) $preferences['num_rss_items'] : 50 ;
        $items = Item::newInstance()->list_items(null, 0, $num_items, 'ACTIVE');
        $items = $items['items'];
        foreach($items as $item) {
            $feed->addItem(array('title'       => $item['s_title'],
                                 'link'        => osc_createItemURL($item),
                                 'description' => $item['s_description']));
        }

        $feed->dumpXML();
        break;
    case 'errorPage':
        osc_runHook('before_error_page');
        osc_renderHeader();
        osc_renderView('404.php');
        osc_renderFooter();
        osc_runHook('after_error_page');
        break;
    case 'setlanguage':
        $languageCodes = osc_listLanguageCodes();
        if(isset($_GET['value']) && in_array($_GET['value'], $languageCodes)) {
            $_SESSION['locale'] = $_GET['value'];
        }
        $defaultURL = ABS_WEB_URL;
        osc_redirectToReferer($defaultURL);
        break;
    case 'contact':
        osc_renderHeader();
        osc_renderView('contact.php');
        osc_renderFooter();
        break;
    case 'contact_post':
        $yourName = $_POST['yourName'];
        $yourEmail = $_POST['yourEmail'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $path = '';

        if($preferences['contact_attachment']) {
            $resourceName = $_FILES['attachment']['name'];
            $tmpName = $_FILES['attachment']['tmp_name'];
            $resourceType = $_FILES['attachment']['type'];
            $path = ABS_PATH . 'oc-content/uploads/' . time() . '_' . $resourceName;

            if(!is_writable(ABS_PATH . 'oc-content/uploads/')) {
                osc_addFlashMessage('There has been some erro sending the message');
                osc_redirectToReferer(ABS_WEB_URL);
            }

            if(!move_uploaded_file($tmpName, $path)){
                unset($path);
            }
        }

        $params = array('from'       => $yourEmail,
                        'from_name'  => $yourName,
                        'subject'    => __('Contact form') . ': ' . $subject,
                        'to'         => $preferences['contactEmail'],
                        'to_name'    => __('Administrator'),
                        'body'       => $message,
                        'alt_body'   => $message);

        if(isset($path)) {
            $params['attachment'] = $path;
        }

        osc_sendMail($params);
        @unlink($path);
        osc_addFlashMessage(__('Your message has been sent and will be answered soon, thank you.'));
        osc_redirectTo(ABS_WEB_URL);
        break;
    default:
        global $osc_request;

        if($osc_request['uri'] == null) {
            osc_renderHeader();
            osc_renderView('home.php');
            osc_renderFooter();
        } else {
            include_once $osc_request['uri'];
        }
        break;
}

?>
