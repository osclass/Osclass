<?php
if (empty($_REQUEST['list'])) {
	header('Location: phpThumb.demo.demo.php');
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Demo of phpThumb() - thumbnails created by PHP using GD and/or ImageMagick</title>
	<link rel="stylesheet"    type="text/css" href="/style.css" title="style sheet">
	<link rel="shortcut icon" type="image/x-icon" href="http://phpthumb.sourceforge.net/thumb.ico">
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body style="background-color: #C5C5C5;">
<?php
$dh = opendir('.');
while ($file = readdir($dh)) {
	if (is_file($file) && ($file{0} != '.') && ($file != basename(__FILE__))) {
		switch ($file) {
			case 'phpThumb.demo.object.simple.php':
			case 'phpThumb.demo.object.php':
				echo '<tt>'.str_replace(' ', '&nbsp;', str_pad(filesize($file), 10, ' ', STR_PAD_LEFT)).'</tt> '.$file.' (cannot work as a live demo)<br>';
				break;
			default:
				echo '<tt>'.str_replace(' ', '&nbsp;', str_pad(filesize($file), 10, ' ', STR_PAD_LEFT)).'</tt> <a href="'.$file.'">'.$file.'</a><br>';
				break;
		}
	}
}
?>
</body>
</html>