<?php

require_once 'oc-load.php';
$preferences = Preference::newInstance()->toArray();

//create object
$rewrite = Rewrite::newInstance();
$rewrite->clearRules();

/*****************************
 ********* Add rules *********
 *****************************/

// Clean archive files
$rewrite->addRule('^(.+?).php(.*)$', '$1.php$2');

// Feed rules
$rewrite->addRule('^feed$', 'search?feed');
$rewrite->addRule('^feed/(.+)$', 'search.php?feed=$1');

// Search rules
$rewrite->addRule('^search/(.*)$', 'search.php?pattern=$1');
$rewrite->addRule('^s/(.*)$', 'search.php?pattern=$1');

// Item rules
$rewrite->addRule('^item/mark$', 'item.php?action=mark');
$rewrite->addRule('^item/send-friend/([0-9]+)$', 'item.php?action=send_friend&id=$1');
$rewrite->addRule('^item/send-friend/done$', 'item.php?action=send_friend_post');
$rewrite->addRule('^item/contact/([0-9]+)$', 'item.php?action=contact&id=$1');
$rewrite->addRule('^item/contact/done$', 'item.php?action=contact_post');
$rewrite->addRule('^item/comment$', 'item.php?action=add_comment');
$rewrite->addRule('^item/new$', 'item.php?action=post');
$rewrite->addRule('^item/new/([0-9]+)$', 'item.php?action=post&catId=$1');
$rewrite->addRule('^item/new/done$', 'item.php?action=post_item');
$rewrite->addRule('^item/activate$', 'item.php?action=activate');
$rewrite->addRule('^item/update/stats$', 'item.php?action=update_cat_stats');
$rewrite->addRule('^item/([0-9]+)$', 'item.php?id=$1');
$rewrite->addRule('^item/(.*)$', 'item.php?action=$1');
$rewrite->addRule('^item$', 'item.php');
$rewrite->addRule('^(.+)_([0-9]+)$', 'item.php?id=$2');

// User rules
$rewrite->addRule('^user/login$', 'user.php?action=login');
$rewrite->addRule('^user/logout$', 'user.php?action=logout');
$rewrite->addRule('^user/register$', 'user.php?action=register');
$rewrite->addRule('^user/register/done$', 'user.php?action=register_post');
$rewrite->addRule('^user/send-validation$', 'user.php?action=send-validation');
$rewrite->addRule('^user/validate$', 'user.php?action=validate');
$rewrite->addRule('^user/profile$', 'user.php?action=profile');
$rewrite->addRule('^user/profile/done$', 'user.php?action=profile_post');
$rewrite->addRule('^user/items$', 'user.php?action=items');
$rewrite->addRule('^user/alerts$', 'user.php?action=alerts');
$rewrite->addRule('^user/account$', 'user.php?action=account');
$rewrite->addRule('^user/item/delete$', 'user.php?action=item_delete');
$rewrite->addRule('^user/item/edit$', 'user.php?action=item_edit');
$rewrite->addRule('^user/item/edit/done$', 'user.php?action=item_edit_post');
$rewrite->addRule('^user/resource/delete$', 'user.php?action=deleteResource');
$rewrite->addRule('^user/login/done$', 'user.php?action=login_post');
$rewrite->addRule('^user/alert/unsub$', 'user.php?action=unsub_alert');
$rewrite->addRule('^user/forgot/password$', 'user.php?action=forgot');
$rewrite->addRule('^user/forgot/password/done$', 'user.php?action=forgot_post');
$rewrite->addRule('^user/change/password$', 'user.php?action=forgot_change');
$rewrite->addRule('^user/change/password/done$', 'user.php?action=forgot_change_post');
$rewrite->addRule('^user/options/(.*)', 'user.php?action=options&option=$1');
$rewrite->addRule('^user/options_post/(.*)$', 'user.php?action=options_post&option=$1');
$rewrite->addRule('^user/(.*)$', 'user.php?action=$1');
$rewrite->addRule('^user$', 'user.php');

// Page rules
$rewrite->addRule('^page/([0-9]*)$', 'page.php?id=$1');

// Category rules
$rewrite->addRule('^(.+)$', 'search.php?category=$1');

//Write rule to DB
$rewrite->setRules();



?>
