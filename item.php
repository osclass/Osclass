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
$manager = Item::newInstance();
$theme = $preferences['theme'];
$locales = Locale::newInstance()->listAllEnabled();

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

switch ($action) {
    case 'mark':
        $item = $manager->findByPrimaryKey($_GET['id']) ;

        $column = null;
        switch ($_GET['as']) {
            case 'spam': $column = 'i_num_spam' ;
            break;
            case 'badcat': $column = 'i_num_bad_classified' ;
            break;
            case 'offensive': $column = 'i_num_offensive' ;
            break;
            case 'repeated': $column = 'i_num_repeated' ;
            break;
            case 'expired': $column = 'i_num_expired' ;
            break;
        }
        
        $dao_itemStats = new ItemStats() ;
        $dao_itemStats->increase($column, $_GET['id']) ;
        unset($dao_itemStats) ;
        setcookie("mark_" . $item['pk_i_id'], "1", time() + 86400);
        osc_addFlashMessage(__('Thanks! That helps us.'));
        osc_redirectTo(osc_createItemURL($item));
    break;
    case 'send_friend':
        $item = $manager->findByPrimaryKey($_GET['id']);

        osc_renderHeader();
        osc_renderView('item-send-friend.php');
        osc_renderFooter();
    break;
    case 'send_friend_post':
        $content = Page::newInstance()->findByInternalName('email_send_friend');

        $item = $manager->findByPrimaryKey($_POST['id']);
        $item_url = osc_createItemURL($item, true);

		$words = array();
        $words[] = array('{FRIEND_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{FRIEND_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{COMMENT}', '{ITEM_URL}', '{WEB_TITLE}');
        $words[] = array($_POST['friendName'], $_POST['yourName'], $_POST['yourEmail'], $_POST['friendEmail'], ABS_WEB_URL, $item['s_title'], $_POST['message'], $item_url, $preferences['pageTitle']);
        $title = osc_mailBeauty($content['s_title'], $words);
        $body = osc_mailBeauty($content['s_text'], $words);

        $from = ( isset($_POST['yourEmail']) ) ? $_POST['yourEmail'] : $preferences['contactEmail'];
        $from_name = $_POST['yourName'];

        if (isset($preferences['notify_contact_friends']) && $preferences['notify_contact_friends']) {
            if (isset($preferences['contactEmail'])) {
                $add_bbc = $preferences['contactEmail'];
            }
        }

        $params = array(
            'add_bcc' => $add_bbc,
            'from' => $from,
            'from_name' => $from_name,
            'subject' => $title,
            'to' => $_POST['friendEmail'],
            'to_name' => $_POST['friendName'],
            'body' => $body,
            'alt_body' => $body
        );
        if(osc_sendMail($params)) {
			osc_addFlashMessage(__('We just send your message to ').$_POST['friendName'].".");
		} else {
			osc_addFlashMessage(__('We are very sorry but we could not deliver your message to your friend. Try again later.'));
		}

        osc_redirectTo($item_url);
    break;
    case 'contact':
        $item = $manager->findByPrimaryKey($_GET['id']);

        osc_renderHeader();
        osc_renderView('item-contact.php');
        osc_renderFooter();
    break;
    case 'contact_post':
        $content = Page::newInstance()->findByInternalName('email_item_inquiry');
        $item = $manager->findByPrimaryKey($_POST['id']);
		$words = array();
        $words[] = array('{CONTACT_NAME}', '{USER_NAME}', '{USER_EMAIL}', '{USER_PHONE}', '{WEB_URL}', '{ITEM_NAME}', '{COMMENT}');
        $words[] = array($item['s_contact_name'], $_POST['yourName'], $_POST['yourEmail'], $_POST['phoneNumber'], ABS_WEB_URL, $item['s_title'], $_POST['message']);
        $title = osc_mailBeauty($content['s_title'], $words);
        $body = osc_mailBeauty($content['s_text'], $words);

        $from = ( isset($preferences['contactEmail']) ) ? $preferences['contactEmail'] : 'no-reply@osclass.org';
        $from_name = $preferences['pageTitle'];
        if (isset($preferences['notify_contact_item']) && $preferences['notify_contact_item']) {
            if (isset($preferences['contactEmail'])) {
                $add_bbc = $preferences['contactEmail'];
            }
        }

        $params = array(
            'add_bcc' => $add_bbc,
            'from' => $from,
            'from_name' => $from_name,
            'subject' => $title,
            'to' => $item['s_contact_email'],
            'to_name' => $item['s_contact_name'],
            'body' => $body,
            'alt_body' => $body
        );
        osc_sendMail($params);
        osc_addFlashMessage(__('We\'ve just sent an e-mail to the seller.'));
        osc_redirectTo(osc_createItemURL($item));
    break;
    case 'add_comment':
        $authorName = $_POST['authorName'];
        $authorEmail = $_POST['authorEmail'];
        $body = $_POST['body'];
        $title = $_POST['title'];
        $itemId = $_POST['id'];

        $item = $manager->findByPrimaryKey($itemId);

        $itemURL = osc_createItemURL($item);

        if (isset($preferences['moderate_comments'])) {
            $status = 'INACTIVE';
        } else {
            $status = 'ACTIVE';
        }
        if (isset($preferences['akismetKey']) && !empty($preferences['akismetKey'])) {
            require_once 'Akismet.class.php';
            $akismet = new Akismet(ABS_WEB_URL, $preferences['akismetKey']);
            $akismet->setCommentAuthor($authorName);
            $akismet->setCommentAuthorEmail($authorEmail);
            $akismet->setCommentContent($body);
            $akismet->setPermalink($itemURL);

            $status = $akismet->isCommentSpam() ? 'SPAM' : $status;
        }

        try {
            Comment::newInstance()->insert(array(
                'dt_pub_date' => DB_FUNC_NOW,
                'fk_i_item_id' => $itemId,
                's_author_name' => $authorName,
                's_author_email' => $authorEmail,
                's_title' => $title,
                's_body' => $body,
                'e_status' => $status
            ));

            $prefManager = Preference::newInstance();
            $notify = $prefManager->findValueByName('notify_new_comment');
            $admin_email = $prefManager->findValueByName('contactEmail');
            $prefLocale = $prefManager->findValueByName('language');


            //Notify admin
            if ($notify) {// && $status=="ACTIVE") {

				$content = Page::newInstance()->findByInternalName('email_new_comment_admin');
				$words = array();
				$words[] = array('{COMMENT_AUTHOR}', '{COMMENT_EMAIL}', '{COMMENT_TITLE}', '{COMMENT_TEXT}', '{ITEM_NAME}', '{ITEM_ID}', '{ITEM_URL}');
				$words[] = array($authorName, $authorEmail, $title, $body, $item['s_title'], $itemId, $itemURL);
				$title_email = osc_mailBeauty($content['s_title'], $words);
				$body_email = osc_mailBeauty($content['s_text'], $words);

				$from = ( isset($preferences['contactEmail']) ) ? $preferences['contactEmail'] : 'no-reply@osclass.org';
				$from_name = $preferences['pageTitle'];
				if (isset($preferences['notify_contact_item']) && $preferences['notify_contact_item']) {
				    if (isset($preferences['contactEmail'])) {
				        $add_bbc = $preferences['contactEmail'];
				    }
				}


                $params = array(
                    'from' => $admin_email,
                    'from_name' => 'Admin mail system',
                    'subject' => $title_email,
                    'to' => $admin_email,
                    'to_name' => 'Admin mail system',
                    'body' => $body_email,
                    'alt_body' => $body_email
                );
                osc_sendMail($params);
            }
        } catch (DatabaseException $e) {
            osc_addFlashMessage(__('We are very sorry but could not save your comment. Try again later.'));
        }

        osc_redirectTo($itemURL);
    break;
    case 'post':
        $userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : null;

        if (isset($preferences['reg_user_post'])) {
            if ($preferences['reg_user_post']) {
                if ($userId != null) {
                    //OK
                } else {
                    // NOT OK
                    osc_addFlashMessage(__('You need to log-in in order to post a new item.'));
                    osc_redirectTo(osc_createLoginURL());//'user.php?action=login');
                    break;
                }
            } else {
                //OK
            }
        }

        $categories = Category::newInstance()->toTree();
        $countries = Country::newInstance()->listAll();
        $regions = array();
        if( count($countries) > 0 ) {
            $regions = Region::newInstance()->getByCountry($countries[0]['pk_c_code']);
        }
        $cities = array();
        if( count($regions) > 0 ) {
            $cities = City::newInstance()->listWhere("fk_i_region_id = %d" ,$regions[0]['pk_i_id']) ;
        }
        
        $currencies = Currency::newInstance()->listAll();
        osc_renderHeader(
                array(
                    'pageTitle' => __('Publish your item') . ' - ' . $preferences['pageTitle'],
                    'noindex' => 'true'
                )
        );
        osc_renderView('item-post.php');
        osc_renderFooter();
    break;
    case 'post_item':
        require_once LIB_PATH.'/osclass/items.php';

        if($success) {
            if(!isset($_SESSION['userId'])) {

                $content = Page::newInstance()->findByInternalName('email_new_item_non_register_user');

                $item_url = osc_createItemURL($item, true);
                $edit_link = ABS_WEB_URL."/user.php?action=item_edit&id=".$itemId."&userId=NULL&secret=".$item['s_secret'];
                $delete_link = ABS_WEB_URL."/user.php?action=item_delete&id=".$itemId."&userId=NULL&secret=".$item['s_secret'];

                $words = array();
                $words[] = array('{ITEM_ID}', '{USER_NAME}', '{USER_EMAIL}', '{WEB_URL}', '{ITEM_NAME}', '{ITEM_URL}', '{WEB_TITLE}', '{EDIT_LINK}', '{DELETE_LINK}');
                $words[] = array($itemId, $PcontactName, $PcontactEmail, ABS_WEB_URL, $item['s_title'], $item_url, $preferences['pageTitle'], $edit_link, $delete_link);
                $title = osc_mailBeauty($content['s_title'], $words);
                $body = osc_mailBeauty($content['s_text'], $words);

                $params = array(
                    'subject' => $title,
                    'to' => $PcontactEmail,
                    'to_name' => $PcontactName,
                    'body' => $body,
                    'alt_body' => $body
                );
                osc_sendMail($params);
            }

            $category = Category::newInstance()->findByPrimaryKey($PcatId);
            osc_redirectTo(osc_createCategoryURL($category));
        } else {
            osc_redirectTo('item.php?action=post');
        }
        break;
    case 'activate':
        if (isset($_GET['secret']) && isset($_GET['id'])) {
            $secret = $_GET['secret'];
            $id = $_GET['id'];
            $item = $manager->listWhere("i.s_secret = '%s' AND i.pk_i_id = '%s'", $secret, $id);
            if (count($item) == 1) {
                $item_validated = $manager->listWhere("i.s_secret = '%s' AND i.e_status = '%s' AND i.pk_i_id = '%s'", $secret, 'INACTIVE', $id);
                if (!is_array($item_validated))
                    return false;

                if (count($item_validated) == 1) {
                    $manager->update(
                            array('e_status' => 'ACTIVE'),
                            array('s_secret' => $secret)
                    );
                    CategoryStats::newInstance()->increaseNumItems($item[0]['fk_i_category_id']);
                    osc_addFlashMessage('Item validated');
                    osc_redirectTo(osc_createItemURL($item[0]));
                } else {
                    osc_addFlashMessage('The item was validated before');
                    osc_redirectTo(osc_createItemURL($item[0]));
                }
            }
        }
    break;
    case 'update_cat_stats':
        $conn = getConnection() ;
        $date = date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
        $sql = sprintf("SELECT COUNT(pk_i_id) as total, fk_i_category_id as category FROM `%st_item` WHERE `dt_pub_date` > '%s' GROUP BY fk_i_category_id", DB_TABLE_PREFIX, $date);
        $items = $conn->osc_dbFetchResults($sql);

        foreach ($items as $stats) {
            $category = $total = 0;
            foreach ($stats as $k => $v) {
                if ($k == "category") {
                    $category = $v;
                }
                if ($k == "total") {
                    $total = $v;
                }
            }
            CategoryStats::newInstance()->update(
                    array(
                        'i_num_items' => $total
                    ), array('fk_i_category_id' => $category)
            );
        }

    break;
    default:
        if (!isset($_GET['id'])) {
            osc_redirectTo('index.php');
        }

        $item = $manager->findByPrimaryKey($_GET['id']);
        if ($item['e_status'] == 'ACTIVE') {
            $resources = $manager->findResourcesByID($_GET['id']);
            $comments = ItemComment::newInstance()->findByItemID($_GET['id']);

            foreach($item['locale'] as $k => $v) {
                $item['locale'][$k]['s_title'] = osc_applyFilter('item_title',$v['s_title']);
                $item['locale'][$k]['s_description'] = osc_applyFilter('item_description',$v['s_description']);
            }

            $headerConf = array('pageTitle' => $item['s_title']);
            osc_renderHeader($headerConf);
            osc_renderView('item.php');
            osc_renderFooter();
        } else {
            if (isset($_SESSION['userId']) && $item['fk_i_user_id'] == $_SESSION['userId']) {
                $resources = $manager->findResourcesByID($_GET['id']);
                $comments = ItemComment::newInstance()->findByItemID($_GET['id']);

                $headerConf = array('pageTitle' => $item['s_title']);
                osc_addFlashMessage('This item is NOT validated. You should validate it in order to show this item to the rest of the users. You could do that in your profile menu.');
                osc_renderHeader($headerConf);
                osc_renderView('item.php');
                osc_renderFooter();
            } else {
                /* The issue suggest to show a message: "The item is not validated yet"
                 * but in my opinion, end-users shouldn't have to know that
                 * better to redirect them to index page (as if nothing had happened)
                 */
                osc_redirectTo('index.php');
            }
        }
}

?>
