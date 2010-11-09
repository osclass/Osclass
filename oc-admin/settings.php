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

$prefManager = Preference::newInstance();
$preferences = $prefManager->toArray();

$action = osc_readAction();

switch ($action) {
    case 'spamNbots':
        osc_renderAdminSection('settings/spamNbots.php', __('Settings'));
        break;
    case 'spamNbots_post':
        $akismetKey = trim($_POST['akismetKey']);
        if (empty($akismetKey)) {
            $prefManager->delete(array('s_name' => 'akismetKey'));
        } else {
            $prefManager->delete(array('s_name' => 'akismetKey')); // @TODO remove
            $prefManager->insert(array('s_section' => 'osclass', 's_name' => 'akismetKey', 's_value' => $akismetKey, 'e_type' => 'STRING'));
        }

        $recaptchaPrivKey = trim($_POST['recaptchaPrivKey']);
        $recaptchaPubKey = trim($_POST['recaptchaPubKey']);
        if (empty($recaptchaPrivKey) || empty($recaptchaPubKey)) {
            $prefManager->delete(array('s_name' => 'recaptchaPrivKey'));
            $prefManager->delete(array('s_name' => 'recaptchaPubKey'));
        } else {
            $prefManager->delete(array('s_name' => 'recaptchaPrivKey')); // @TODO remove
            $prefManager->delete(array('s_name' => 'recaptchaPubKey')); // @TODO remove
            $prefManager->insert(array('s_section' => 'osclass', 's_name' => 'recaptchaPrivKey', 's_value' => $recaptchaPrivKey, 'e_type' => 'STRING'));
            $prefManager->insert(array('s_section' => 'osclass', 's_name' => 'recaptchaPubKey', 's_value' => $recaptchaPubKey, 'e_type' => 'STRING'));
        }

        osc_redirectTo('settings.php?action=spamNbots');
        break;
    case 'registry':
        $preferencesTable = $prefManager->listAll();
        osc_renderAdminSection('settings/registry.php', __('Settings'));
        break;
    case 'currencies':
        $currencies = Currency::newInstance()->listAll();
        osc_renderAdminSection('settings/currencies.php', __('Settings'));
        break;
    case 'addCurrency':
        osc_renderAdminSection('settings/addCurrency.php', __('Settings'));
        break;
    case 'addCurrency_post':
        try {
            Currency::newInstance()->insert($_POST);
        } catch (DatabaseException $e) {
            osc_addFlashMessage($e->getMessage());
        }
        osc_redirectTo('settings.php?action=currencies');
        break;
    case 'editCurrency':
        if(isset($_GET['code'])) {
            $currency = Currency::newInstance()->findByCode($_GET['code']);
            osc_renderAdminSection('settings/editCurrency.php', __('Settings'));
        } else {
            osc_redirectTo('settings.php?action=currencies');
        }
        break;
    case 'editCurrency_post':
        try {
            Currency::newInstance()->update(array('s_name' => $_POST['s_name'], 's_description' => $_POST['s_description']), array('pk_c_code' => $_POST['pk_c_code']));
        } catch (DatabaseException $e) {
            osc_addFlashMessage($e->getMessage());
        }
        osc_redirectTo('settings.php?action=currencies');
        break;
    case 'deleteCurrency':
        $codes = $_GET['code'];

        isset($_POST['id']) ? $codes = $_POST['id'] : '';

        foreach ($codes as &$code)
            $code = "'$code'";
        unset($code);
        $cond = 'pk_c_code IN (' . implode(', ', $codes) . ')';
        Currency::newInstance()->delete(array(DB_CUSTOM_COND => $cond));
        osc_redirectTo('settings.php?action=currencies');
        break;
    case 'functionalities':
        osc_renderAdminSection('settings/functionalities.php', __('Functionalities'));
        break;
    case 'functionalities_post':
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_comments']) ? true : false),
                array('s_name' => 'enabled_comments')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_recaptcha_items']) ? true : false),
                array('s_name' => 'enabled_recaptcha_items')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false),
                array('s_name' => 'enabled_item_validation')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['moderate_comments']) ? true : false),
                array('s_name' => 'moderate_comments')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['reg_user_post']) ? true : false),
                array('s_name' => 'reg_user_post')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['auto_cron']) ? true : false),
                array('s_name' => 'auto_cron')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=functionalities');
        break;
    case 'notifications':
        osc_renderAdminSection('settings/notifications.php', __('Notifications'));
        break;
    case 'notifications_post':
        $prefManager->update(
                array('s_value' => isset($_POST['notify_new_item']) ? true : false),
                array('s_name' => 'notify_new_item')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_contact_friends']) ? true : false),
                array('s_name' => 'notify_contact_friends')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_new_comment']) ? true : false),
                array('s_name' => 'notify_new_comment')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_contact_item']) ? true : false),
                array('s_name' => 'notify_contact_item')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false),
                array('s_name' => 'enabled_item_validation')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=notifications');
        break;
    case 'mailserver':
        osc_renderAdminSection('settings/mailserver.php', __('Functionalities'));
        break;
    case 'mailserver_post':
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_auth']) ? true : false),
                array('s_name' => 'mailserver_auth')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_type']) ? $_POST['mailserver_type'] : 'custom'),
                array('s_name' => 'mailserver_type')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_host']) ? $_POST['mailserver_host'] : ''),
                array('s_name' => 'mailserver_host')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_port']) ? $_POST['mailserver_port'] : ''),
                array('s_name' => 'mailserver_port')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_username']) ? $_POST['mailserver_username'] : ''),
                array('s_name' => 'mailserver_username')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['mailserver_password']) ? $_POST['mailserver_password'] : ''),
                array('s_name' => 'mailserver_password')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=mailserver');
        break;
    case 'notifications':
        osc_renderAdminSection('settings/notifications.php', __('Notifications'));
        break;
    case 'permalinks':
        osc_renderAdminSection('settings/permalinks.php', __('Settings'));
        break;
    case 'permalinks_post':
        $prefManager->update(
                array('s_value' => $_REQUEST['value'] ? true : false),
                array('s_name' => 'rewriteEnabled')
        );
        osc_redirectTo('settings.php?action=permalinks');
    case 'items':
        osc_renderAdminSection('settings/items.php', __('Settings'));
        break;
    case 'comments':
        osc_renderAdminSection('settings/comments.php', __('Settings'));
        break;
    case 'cron':
        osc_renderAdminSection('settings/cron.php', __('Settings'));
        break;
    case 'cron_post':
        $prefManager->update(
                array('s_value' => isset($_POST['auto_cron']) ? true : false),
                array('s_name' => 'auto_cron')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=cron');
        break;
    case 'comments_post':
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_comments']) ? true : false),
                array('s_name' => 'enabled_comments')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['moderate_comments']) ? true : false),
                array('s_name' => 'moderate_comments')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_new_comment']) ? true : false),
                array('s_name' => 'notify_new_comment')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=comments');
        break;
    case 'items_post':
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_recaptcha_items']) ? true : false),
                array('s_name' => 'enabled_recaptcha_items')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false),
                array('s_name' => 'enabled_item_validation')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['reg_user_post']) ? true : false),
                array('s_name' => 'reg_user_post')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_new_item']) ? true : false),
                array('s_name' => 'notify_new_item')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_contact_friends']) ? true : false),
                array('s_name' => 'notify_contact_friends')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['notify_contact_item']) ? true : false),
                array('s_name' => 'notify_contact_item')
        );
        $prefManager->update(
                array('s_value' => isset($_POST['enabled_item_validation']) ? true : false),
                array('s_name' => 'enabled_item_validation')
        );
        $preferences = $prefManager->toArray();
        osc_redirectTo('settings.php?action=items');
        break;
    case 'update':
        foreach ($_POST as $key => $value) {
            $prefManager->update(
                    array('s_value' => $value),
                    array('s_section' => 'osclass', 's_name' => $key)
            );
        }
        $preferences = $prefManager->toArray();

    default:
        $languages = Locale::newInstance()->listAllEnabled();

        osc_renderAdminSection('settings/index.php', __('General settings'));
}

?>
