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

class CAdminItemComments extends AdminSecBaseModel
{
    private $itemCommentManager ;
    
    function __construct() {
        parent::__construct() ;

        //specific things for this class
        $this->itemCommentManager = ItemComment::newInstance() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {
            case 'bulk_actions':
            break;
        }
    }
}




define('ABS_PATH', dirname(dirname(__FILE__)) . '/');

require_once ABS_PATH . 'oc-admin/oc-load.php';

$manager = ItemComment::newInstance();

$action = Params::getParam('action');
switch ($action) {
    case 'bulk_actions':
        switch ($_POST['bulk_actions']) {
            case 'delete_all':
                $id = osc_paramRequest('id', false);
                try {
                    if ($id) {
                        $manager->delete(array(
                            DB_CUSTOM_COND => 'pk_i_id IN (' . implode(', ', $id) . ')'
                        ));
                    }
                    osc_add_flash_message(__('The comments have been deleted.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                break;

            case 'activate_all':
                $id = osc_paramRequest('id', false);
                $value = 'ACTIVE';
                try {
                    if ($id) {
                        foreach ($id as $_id) {
                            $manager->update(
                                    array('e_status' => $value),
                                    array('pk_i_id' => $_id)
                            );
                        }
                    }
                    osc_add_flash_message(__('The comments have been activated.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                break;

            case 'deactivate_all':
                $id = osc_paramRequest('id', false);
                $value = 'INACTIVE';
                try {
                    if ($id) {
                        foreach ($id as $_id) {
                            $manager->update(
                                    array('e_status' => $value),
                                    array('pk_i_id' => $_id)
                            );
                        }
                    }
                    osc_add_flash_message(__('The comments have been deactivated.'));
                } catch (Exception $e) {
                    osc_add_flash_message(__('Error: ') . $e->getMessage());
                }
                break;
        }
        osc_redirectTo('comments.php');
        break;

    case 'status':
        $id = osc_paramRequest('id', false);
        $value = osc_paramRequest('value', false);

        if (!$id) return false;
        $id = (int) $id;
        if (!is_numeric($id)) return false;
        if (!in_array($value, array('ACTIVE', 'INACTIVE'))) return false;

        try {
            $manager->update(
                    array('e_status' => $value),
                    array('pk_i_id' => $id)
            );
            if($value=='ACTIVE') {
                osc_add_flash_message(__('The comment has been activated.'));
            } else {
                osc_add_flash_message(__('The comment has been deactivated.'));
            }
        } catch (Exception $e) {
            osc_add_flash_message(__('Error: ') . $e->getMessage());
        }
        osc_redirectTo('comments.php');
        break;
    case 'comment_edit':
        $itemId = null;

        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) $itemId = $_GET['id'];

        $comment = Comment::newInstance()->findByPrimaryKey($itemId);
        osc_renderAdminSection('comments/frm.php', __('Comments'));
        break;
    case 'comment_edit_post':

        $manager->update(array(
            's_title' => $_REQUEST['s_title'],
            's_body' => $_REQUEST['s_body'],
            's_author_name' => $_REQUEST['s_author_name'],
            's_author_email' => $_REQUEST['s_author_email']
                ), array('pk_i_id' => $_REQUEST['id']));

        osc_run_hook('item_edit_post');

        osc_add_flash_message(__('Great! We\'ve just update your item.'));
        osc_redirectTo('comments.php');
        break;
    case 'delete':
        $manager->deleteByID($_GET['id']);
    default:
        $itemId = null;

        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) $itemId = $_GET['id'];
        !is_null($itemId) ? $comments = $manager->getAllComments($itemId) : $comments = $manager->getAllComments();
        osc_renderAdminSection('comments/index.php', __('Comments'));
}
?>
