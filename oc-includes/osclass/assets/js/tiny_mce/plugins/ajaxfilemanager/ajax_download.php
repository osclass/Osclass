<?php
	/**
	 * delete selected files
	 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */

	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
    if(!osc_is_admin_user_logged_in()) { exit('Direct access is not allowed.'); };
	if(!empty($_GET['path']) && file_exists($_GET['path']) && is_file($_GET['path']) && isUnderRoot($_GET['path']))
	{
			
			$path = $_GET['path'];
			//check if the file size
			$fileSize = @filesize($path);
			
			if($fileSize > getMemoryLimit())
			{//larger then the php memory limit, redirect to the file
				
				osc_redirect_to($path);
			}else
			{//open it up and send out with php 
				downloadFile($path);	
				 			
			}
	}else 
	{
		die(ERR_DOWNLOAD_FILE_NOT_FOUND);
	}
?>