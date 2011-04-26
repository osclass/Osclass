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

/**
 * This functions retrieves a news list from http://osclass.org. It uses the Cache services to speed up the process.
 */
function osc_listNews() {
    require_once LIB_PATH . 'osclass/classes/Cache.php';

    $cache = new Cache('admin-blog_news', 900);
    if ($cache->check()) {
        return $cache->retrieve();
    } else {
        $list = array();

        $content = osc_file_get_contents('http://osclass.org/feed/');
        if ($content) {
            $xml = simplexml_load_string($content);
            foreach ($xml->channel->item as $item) {
                $list[] = array(
                    'link' => strval($item->link)
                    , 'title' => strval($item->title)
                    , 'pubDate' => strval($item->pubDate));
            }
        }

        $cache->store($list);
    }

    return $list;
}

?>