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
try {
	require_once 'oc-load.php';


	$categories = Category::newInstance()->toTree();
	$preferences = Preference::newInstance()->toArray();
	if(isset($_GET['theme'])) $preferences['theme'] = $_GET['theme'];

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;
	switch($action) {
		case 'sitemap':
			//require_once 'osclass/classes/Sitemap.php';
			$sm = new Sitemap;
			//require_once 'osclass/model/Page.php';
			$pages = Page::newInstance()->listAll();
			foreach($pages as $p)
				$sm->addURL(ABS_WEB_URL . osc_createPageURL($p), 'weekly', 0.8);
	
			$categories = Category::newInstance()->listAll();
			foreach($categories as $c)
				$sm->addURL(ABS_WEB_URL . osc_createCategoryURL($c), 'daily', 0.9);
			$sm->toStdout();
			break;
		case 'feed':
			header('Content-type: text/xml; charset=utf-8');

                        //require_once 'osclass/classes/RSSFeed.php';
			$feed = new RSSFeed;
			$feed->setTitle(__('Latest items added') . ' - ' . $preferences["pageTitle"]);
			$feed->setLink(ABS_WEB_URL);
			$feed->setDescription(__('Latest items added in') . ' ' . $preferences["pageTitle"]);

			//require_once 'osclass/model/Item.php';
                        $num_items = (isset($preferences['num_rss_items'])) ? (int) $preferences['num_rss_items'] : 50 ;
                        $items = Item::newInstance()->list_items(null, 0, $num_items, 'ACTIVE');
                        $items = $items['items'];
			foreach($items as $item) {
				$feed->addItem(array(
					'title' => $item['s_title'],
					'link' => osc_createItemURL($item, true),
					'description' => $item['s_description']
				));
			}

			$feed->dumpXML();
			break;
		case 'errorPage':
			osc_renderHeader();
			osc_renderView('errorPage.php');
			osc_renderFooter();
			break;
		case 'setlanguage':
			//require_once 'osclass/utils.php';
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


			$params = array(
				'from' => $yourEmail,
				'from_name' => $yourName,
				'subject' => __('Contact form') . ': ' . $subject,
				'to' => $preferences['contactEmail'],
				'to_name' => __('Administrator'),
				'body' => $message,
				'alt_body' => $message
			);
			osc_sendMail($params);





			osc_addFlashMessage(__('Your message has been sent and will be answered soon, thank you.'));

			//require_once 'osclass/utils.php';
			osc_redirectToReferer(ABS_WEB_URL);
			break;

		default:

            $redirected = Rewrite::newInstance()->doRedirect();

            if($redirected==null) {
    			osc_renderHeader();
    			osc_renderView('home.php');
    			osc_renderFooter();
            } else {
                include_once $redirected;
            }

	}
	
} catch (Exception $e) {
	echo $e->getMessage();
}

?>
