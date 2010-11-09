<?php
/*
Plugin Name: Time elapsed
Plugin URI: http://www.osclass.org/
Description: This plugin shows the times takes to render each page.
Version: 1.0
Author: OSClass
Author URI: http://www.osclass.org/
Plugin update URI: http://www.osclass.org/files/plugins/time_elapsed/update.php
*/

function time_elapsed_info() {
	return array(
		'name' => 'Time elapsed',
		'description' => 'This plugin shows the times takes to render each page.',
		'version' => 1.0,

		'author_name' => 'OSClass',
		'author_url' => 'http://www.osclass.org/',

		'hooks' => array('header', 'footer')
	);
}

$timer = null;

function time_elapsed_header() {
	global $timer;
	$timer = microtime();
}

function time_elapsed_footer() {
	global $timer;
	echo '<!-- time to load: ', microtime() - $timer , ' -->', PHP_EOL;
}

osc_registerPlugin(__FILE__, '');
osc_addHook('footer', 'time_elapsed_footer');
osc_addHook('header', 'time_elapsed_header');

