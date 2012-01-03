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

    //create object
    $rewrite = Rewrite::newInstance() ;
    $rewrite->clearRules() ;

    /*****************************
     ********* Add rules *********
     *****************************/

    // Contact rules
    $rewrite->addRule('^contact/?$', 'index.php?page=contact');

    // Feed rules
    $rewrite->addRule('^feed/?$', 'index.php?page=search&sFeed=rss');
    $rewrite->addRule('^feed/(.+)$', 'index.php?page=search&sFeed=$1');

    // Language rules
    $rewrite->addRule('^language/(.*?)/?$', 'index.php?page=language&locale=$1');

    // Search rules
    $rewrite->addRule('^search/(.*)$', 'index.php?page=search&sPattern=$1');
    $rewrite->addRule('^s/(.*)$', 'index.php?page=search&sPattern=$1');

    // Item rules
    $rewrite->addRule('^item/mark/(.*?)/([0-9]+)$', 'index.php?page=item&action=mark&as=$1&id=$2');
    $rewrite->addRule('^item/send-friend/([0-9]+)$', 'index.php?page=item&action=send_friend&id=$1');
    $rewrite->addRule('^item/contact/([0-9]+)$', 'index.php?page=item&action=contact&id=$1');
    $rewrite->addRule('^item/new$', 'index.php?page=item&action=item_add');
    $rewrite->addRule('^item/new/([0-9]+)$', 'index.php?page=item&action=item_add&catId=$1');
    $rewrite->addRule('^item/activate/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=activate&id=$1&secret=$2');
    $rewrite->addRule('^item/edit/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_edit&id=$1&secret=$2');
    $rewrite->addRule('^item/delete/([0-9]+)/(.*?)/?$', 'index.php?page=item&action=item_delete&id=$1&secret=$2');
    $rewrite->addRule('^item/resource/delete/([0-9]+)/([0-9]+)/([0-9A-Za-z]+)/?(.*?)/?$', 'index.php?page=item&action=deleteResource&id=$1&item=$2&code=$3&secret=$4');
    $rewrite->addRule('^([a-zA-Z_]{5})_(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$3&lang=$1&comments-page=$4');
    $rewrite->addRule('^(.+)_([0-9]+)\?comments-page=([0-9al]*)$', 'index.php?page=item&id=$2&comments-page=$3');
    $rewrite->addRule('^([a-zA-Z_]{5})_(.+)_([0-9]+)$', 'index.php?page=item&id=$3&lang=$1');
    $rewrite->addRule('^(.+)_([0-9]+)$', 'index.php?page=item&id=$2');

    // User rules
    $rewrite->addRule('^user/login$', 'index.php?page=login');
    $rewrite->addRule('^user/dashboard/?$', 'index.php?page=user&action=dashboard');
    $rewrite->addRule('^user/logout$', 'index.php?page=main&action=logout');
    $rewrite->addRule('^user/register$', 'index.php?page=register&action=register');
    $rewrite->addRule('^user/activate/([0-9]+)/(.*?)/?$', 'index.php?page=register&action=validate&id=$1&code=$2');
    $rewrite->addRule('^user/activate_alert/([a-zA-Z0-9]+)/(.+)$', 'index.php?page=user&action=activate_alert&email=$2&secret=$1');
    $rewrite->addRule('^user/profile$', 'index.php?page=user&action=profile');
    $rewrite->addRule('^user/profile/([0-9]+)$', 'index.php?page=user&action=pub_profile&id=$1');
    $rewrite->addRule('^user/items$', 'index.php?page=user&action=items');
    $rewrite->addRule('^user/alerts$', 'index.php?page=user&action=alerts');
    $rewrite->addRule('^user/recover/?$', 'index.php?page=login&action=recover');
    $rewrite->addRule('^user/forgot/([0-9]+)/(.*)$', 'index.php?page=login&action=forgot&userId=$1&code=$2');
    $rewrite->addRule('^user/change_password$', 'index.php?page=user&action=change_password');
    $rewrite->addRule('^user/change_email$', 'index.php?page=user&action=change_email');
    $rewrite->addRule('^user/change_email_confirm/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');

    // Page rules
    $rewrite->addRule('^(.*?)-p([0-9]*)$', 'index.php?page=page&id=$2');
    $rewrite->addRule('^(.*?)-p([0-9]*)-([a-zA-Z_]*)$', 'index.php?page=page&id=$2&lang=$3');

    // Clean archive files
    $rewrite->addRule('^(.+?)\.php(.*)$', '$1.php$2');

    // Category rules
    $rewrite->addRule('^(.+)$', 'index.php?page=search&sCategory=$1');

    //Write rule to DB
    $rewrite->setRules();

?>