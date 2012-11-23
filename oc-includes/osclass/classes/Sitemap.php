<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
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
     * This class dynamically creates a XML Sitemap ready to send to Google, Yahoo and others.
     * @author  Osclass
     */
    class Sitemap {

        private $urls;
        private $validFrequencies = array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never');

        public function __construct() {
            $this->urls = array();
        }

        public function addURL($loc, $changeFreq = 'daily', $priority = 0.7, $lastMod = null) {
            $this->urls[] = array(
                'loc' => $loc,
                'lastMod' => $lastMod,
                'changeFreq' => $changeFreq,
                'priority' => $priority
            );
        }

        public function toStdout() {
            header('Content-type: text/xml; charset=utf-8');
            echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL;
            echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', PHP_EOL;

            foreach($this->urls as $url) {
                echo '<url>', PHP_EOL;
                echo '<loc>', $url['loc'], '</loc>', PHP_EOL;
                echo '<lastmod>', $url['lastMod'], '</lastmod>', PHP_EOL;
                echo '<changefreq>', $url['changeFreq'], '</changefreq>', PHP_EOL;
                echo '<priority>', $url['priority'], '</priority>', PHP_EOL;
                echo '</url>', PHP_EOL;
            }

            echo '</urlset>', PHP_EOL;
        }
    }

?>