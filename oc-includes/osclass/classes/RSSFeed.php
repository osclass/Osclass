<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

    /**
     * This class takes items descriptions and generates a RSS feed from that information.
     * @author Osclass
     */
    class RSSFeed {
        private $title;
        private $link;
        private $description;
        private $items;

        public function __construct() {
            $this->items = array();
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function setLink($link) {
            $this->link = $link;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        public function addItem($item) {
            $this->items[] = $item;
        }

        public function dumpXML() {
            echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
            echo '<rss version="2.0">', PHP_EOL;
            echo '<channel>', PHP_EOL;
            echo '<title>', $this->title, '</title>', PHP_EOL;
            echo '<link>', $this->link, '</link>', PHP_EOL;
            echo '<description>', $this->description, '</description>', PHP_EOL;
            foreach ($this->items as $item) {
                echo '<item>', PHP_EOL;
                echo '<title><![CDATA[', $item['title'], ']]></title>', PHP_EOL;
                echo '<link>', $item['link'], '</link>', PHP_EOL;
                echo '<guid>', $item['link'], '</guid>', PHP_EOL;

                echo '<description><![CDATA[';
                if(@$item['image']) {
                    echo '<a href="'.$item['image']['link'].'" title="'.$item['image']['title'].'" rel="nofollow">';
                    echo '<img style="float:left;border:0px;" src="'.$item['image']['url'].'" alt="'.$item['image']['title'].'"/> </a>';
                }
                echo $item['description'], ']]>';
                echo '</description>', PHP_EOL;

                echo '<country>', $item['country'], '</country>', PHP_EOL;
                echo '<region>', $item['region'], '</region>', PHP_EOL;
                echo '<city>', $item['city'], '</city>', PHP_EOL;
                echo '<cityArea>', $item['city_area'], '</cityArea>', PHP_EOL;
                echo '<category>', $item['category'], '</category>', PHP_EOL;

                echo '<pubDate>', date('r',strtotime($item['dt_pub_date'])) , '</pubDate>', PHP_EOL;
                
                echo '</item>', PHP_EOL;
            }
            echo '</channel>', PHP_EOL;
            echo '</rss>', PHP_EOL;
        }
    }
?>