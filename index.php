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

    require_once 'oc-load.php' ;

    switch( Params::getParam('page') )
    {
        case ('user'):      //user pages (with security)
                            require_once(osc_base_path() . 'user.php') ;
                            $do = new CWebUser() ;
                            $do->doModel() ;
        break;
        case ('item'):      //item pages (with security)
                            if((Params::getParam("action")=="post" || Params::getParam("action")=="post_item") && osc_reg_user_post()) {
                                require_once(osc_base_path() . 'item-secure.php');
                                $do = new CWebSecItem() ;
                            } else {
                                require_once(osc_base_path() . 'item.php');
                                $do = new CWebItem() ;
                            }
                            $do->doModel() ;
        break;
        case ('search'):    //search pages
                            require_once(osc_base_path() . 'search.php') ;
                            $do = new CWebSearch() ;
                            $do->doModel() ;
        break;
        case ('page'):      //static pages
                            require_once(osc_base_path() . 'page.php') ;
                            $do = new CWebPage() ;
                            $do->doModel() ;
        break;
        case ('register'):  //user pages
                            require_once(osc_base_path() . 'register.php') ;
                            $do = new CWebRegister() ;
                            $do->doModel() ;
        break;
        case ('login'):     //user pages
                            require_once(osc_base_path() . 'login.php') ;
                            $do = new CWebLogin() ;
                            $do->doModel() ;
        break;
        default:            //home and static pages that are mandatory...
                            require_once(osc_base_path() . 'main.php') ;
                            $do = new CWebMain() ;
                            $do->doModel() ;
    }




/*






try {

    require_once 'oc-load.php';

	$categories = Category::newInstance()->toTree();

    if(isset($_GET['theme'])) Preference::newInstance()->set('theme', $_GET['theme']) ;

	$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null ;
	switch($action) {
		case 'sitemap':
			$sm = new Sitemap ;
			$pages = Page::newInstance()->listAll() ;
			foreach($pages as $p)
				$sm->addURL(osc_base_url() . osc_createPageURL($p), 'weekly', 0.8) ;
	
			$categories = Category::newInstance()->listAll() ;
			foreach($categories as $c)
				$sm->addURL(osc_base_url() . osc_search_category_url($c), 'daily', 0.9) ;
			$sm->toStdout();
		break;
		case 'feed':
			header('Content-type: text/xml; charset=utf-8');

			$feed = new RSSFeed ;
			$feed->setTitle(__('Latest items added') . ' - ' . osc_page_title() ) ;
			$feed->setLink(osc_base_url()) ;
			$feed->setDescription(__('Latest items added in') . ' ' . osc_page_title() ) ;

            $num_items = osc_num_rss_items() ;
            $items = Item::newInstance()->list_items(null, 0, $num_items, 'ACTIVE') ;
            $items = $items['items'] ;
			foreach($items as $item) {
				$feed->addItem(array(
					'title' => $item['s_title']
					,'link' => osc_create_item_url($item, true)
					,'description' => $item['s_description']
				));
			}

			$feed->dumpXML() ;
		break;
		case 'errorPage':
			osc_renderHeader() ;
			osc_renderView('errorPage.php') ;
			osc_renderFooter() ;
		break;
		case 'setlanguage':
			$languageCodes = osc_listLanguageCodes() ;
			if(isset($_GET['value']) && in_array($_GET['value'], $languageCodes)) {
				$_SESSION['locale'] = $_GET['value'] ;
			}
			$defaultURL = osc_base_url() ;
			osc_redirectToReferer($defaultURL) ;
		break;
		case 'contact':
			osc_renderHeader() ;
			osc_renderView('contact.php') ;
			osc_renderFooter() ;
		break;
		case 'contact_post':
			$yourName = $_POST['yourName'] ;
			$yourEmail = $_POST['yourEmail'] ;
			$subject = $_POST['subject'] ;
			$message = $_POST['message'] ;


			$params = array(
				'from' => $yourEmail
				,'from_name' => $yourName
				,'subject' => __('Contact form') . ': ' . $subject
				,'to' => osc_contact_email()
				,'to_name' => __('Administrator')
				,'body' => $message
				,'alt_body' => $message
			);
			osc_sendMail($params) ;
			osc_add_flash_message(__('Your message has been sent and will be answered soon, thank you.')) ;
			osc_redirectToReferer(osc_base_url()) ;
        break;
        default:

            global $osc_request ;
            //$redirected = Rewrite::newInstance()->doRedirect();
            //print_r($osc_request);

            if($osc_request['uri'] == null) {
    			osc_renderHeader() ;
    			osc_renderView('home.php') ;
    			osc_renderFooter() ;
            } else {
                include_once $osc_request['uri'] ;
            }

	}
	
} catch (Exception $e) {
	echo $e->getMessage();
}

 */

?>
