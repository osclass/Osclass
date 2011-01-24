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

require_once  LIB_PATH . 'osclass/model/Page.php';

/**
 * Check if a page id is indelible
 *
 * @param int $id Page id
 * @return true if it's indelible, false in case not
 */
function pageIsIndelible($id)
{
    $pageManager = new Page();
    $page = $pageManager->findByPrimaryKey($id);
    if($page['b_indelible'] == 1) {
        return true;
    }
    return false;
}

/**
 * Delete pages by id number
 *
 * It add flashmessages here
 *
 * @param mixed $id
 * @return int -1 page is indelible | 0 there has been an error | 1 page has beens succesfully deleted
 */
function pageDeleteById($id)
{
    if(is_array($id)) {
        return 0;
    }
    
    $id = (int) $id;
    if(!is_int($id)) {
        return 0;
    }

    if($id < 1) {
        return 0;
    }

    if(pageIsIndelible($id)) {
        return -1;
    }

    $pageManager = new Page();
    $result = $pageManager->deleteByID($id);
    if($result) {
        return 1;
    }
    return 0;
}

/**
 * Check if Internal Name exists with another id
 *
 * @param int $id page id
 * @param string $internalName page internal name
 * @return true if internal name exists, false if not
 */
function pageInternalNameExists($id, $internalName)
{
    $pageManager = new Page();
    $conditions = array('s_internal_name' => $internalName);
    $result = $pageManager->listWhere('s_internal_name = \'' . $internalName . '\' AND pk_i_id <> ' . $id );

    if(count($result) > 0) {
        return true;
    }
    return false;
}

?>