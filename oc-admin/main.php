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

$numUsers = User::newInstance()->count();
$numAdmins = Admin::newInstance()->count();
$numItems = Item::newInstance()->count();
$numItemsPerCategory = CategoryStats::newInstance()->toNumItemsMap();
$categories = Category::newInstance()->listAll();
$newsList = osc_listNews();
$comments = ItemComment::newInstance()->getLastComments(5); //XXX: must take from config?

osc_renderAdminSection('main/index.php', __('Dashboard'));

?>