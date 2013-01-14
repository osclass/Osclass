<?php
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
    if(!osc_is_admin_user_logged_in()) { exit('Direct access is not allowed.'); };
	echo '{';
	$count = 1;
	foreach(getFolderListing(CONFIG_SYS_ROOT_PATH) as $k=>$v)
	{
		

		echo (($count > 1)?', ':''). "'" . $v . "':'" . $k . "'"; 
		$count++;
	}
	echo "}";
?>
