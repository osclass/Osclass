<?php

require_once 'oc-load.php' ;

//create object
$rewrite = Rewrite::newInstance() ;
$rewrite->clearRules() ;

/*****************************
 ********* Add rules *********
 *****************************/

// Clean archive files
$rewrite->addRule('^(.+?).php(.*)$', '$1.php$2');

// Contact rules
$rewrite->addRule('^contact/?$', 'index.php?page=contact');

// Feed rules
$rewrite->addRule('^feed$', 'index.php?page=search&sFeed=rss');
$rewrite->addRule('^feed/(.+)$', 'index.php?page=search&sFeed=$1');

// Language rules
$rewrite->addRule('^language/(.*?)/?$', 'index.php?page=language&locale=$1');

// Search rules
$rewrite->addRule('^search/(.*)$', 'index.php?page=search&sPattern=$1');
$rewrite->addRule('^s/(.*)$', 'index.php?page=search&pattern=$1');

// Item rules
$rewrite->addRule('^item/mark/(.*?)/([0-9]+)$', 'index.php?page=item&action=mark&as=$1&id=$2');
$rewrite->addRule('^item/send-friend/([0-9]+)$', 'index.php?page=item&action=send_friend&id=$1');
$rewrite->addRule('^item/send-friend/done$', 'index.php?page=item&action=send_friend_post');
$rewrite->addRule('^item/contact/([0-9]+)$', 'index.php?page=item&action=contact&id=$1');
$rewrite->addRule('^item/contact/done$', 'index.php?page=item&action=contact_post');
$rewrite->addRule('^item/comment$', 'index.php?page=item&action=add_comment');
$rewrite->addRule('^item/new$', 'index.php?page=item&action=item_add');
$rewrite->addRule('^item/new/([0-9]+)$', 'index.php?page=item&action=item_add&catId=$1');
$rewrite->addRule('^item/new/done$', 'index.php?page=item&action=post_item');
$rewrite->addRule('^item/activate$', 'index.php?page=item&action=activate');
$rewrite->addRule('^item/update/stats$', 'index.php?page=item&action=update_cat_stats');
$rewrite->addRule('^item/([0-9]+)$', 'index.php?page=item&id=$1');
$rewrite->addRule('^item/(.*)$', 'index.php?page=item&action=$1');
$rewrite->addRule('^item$', 'index.php?page=item');
$rewrite->addRule('^(.+)_([0-9]+)$', 'index.php?page=item&id=$2');

// User rules
$rewrite->addRule('^user/login$', 'index.php?page=login');
$rewrite->addRule('^user/logout$', 'index.php?page=main&action=logout');
$rewrite->addRule('^user/register$', 'index.php?page=register&action=register');
$rewrite->addRule('^user/register/done$', 'index.php?page=register&action=register_post');
$rewrite->addRule('^user/send-validation$', 'index.php?page=user&action=send_validation');
$rewrite->addRule('^user/validate$', 'index.php?page=register&action=validate');
$rewrite->addRule('^user/profile$', 'index.php?page=user&action=profile');
$rewrite->addRule('^user/profile/done$', 'index.php?page=user&action=profile_post');
$rewrite->addRule('^user/items$', 'index.php?page=user&action=items');
$rewrite->addRule('^user/alerts$', 'index.php?page=user&action=alerts');
$rewrite->addRule('^user/account$', 'index.php?page=user&action=account');
$rewrite->addRule('^user/item/delete$', 'index.php?page=user&action=item_delete');
$rewrite->addRule('^user/item/edit$', 'index.php?page=user&action=item_edit');
$rewrite->addRule('^user/item/edit/done$', 'index.php?page=user&action=item_edit_post');
$rewrite->addRule('^user/resource/delete$', 'index.php?page=user&action=deleteResource');
$rewrite->addRule('^user/login/done$', 'index.php?page=user&action=login_post');
$rewrite->addRule('^user/forgot/([0-9]+)/(.*)$', 'index.php?page=login&action=forgot&userId=$1&code=$2');
$rewrite->addRule('^user/change/password$', 'index.php?page=user&action=forgot_change');
$rewrite->addRule('^user/change/password/done$', 'index.php?page=user&action=forgot_change_post');
$rewrite->addRule('^user/change_email_confirm/([0-9]+)/(.*?)/?$', 'index.php?page=user&action=change_email_confirm&userId=$1&code=$2');
$rewrite->addRule('^user/options/(.*)', 'index.php?page=user&action=options&option=$1');
$rewrite->addRule('^user/options_post/(.*)$', 'index.php?page=user&action=options_post&option=$1');
$rewrite->addRule('^user/(.*)$', 'index.php?page=user&action=$1');
$rewrite->addRule('^user$', 'index.php?page=user');

// Page rules
$rewrite->addRule('^(.*?)-p([0-9]*)$', 'index.php?page=page&id=$2');

// Category rules
$rewrite->addRule('^(.+)$', 'index.php?page=search&sCategory=$1');

//Write rule to DB
$rewrite->setRules();

?>
