<?php

/**
 * The base MySQL settings of Osclass
 */
define('MULTISITE', intval($_ENV['OSCLASS_MULTISITE']));

$url = "mysql://user:password@hostname:port/dbname";
foreach(array('CLEARDB_DATABASE_URL', 'JAWSDB_DATABASE_URL', 'DATABASE_URL') as $db_key){
  if(array_key_exists($db_key, $_ENV)){
    $url = $_ENV[$db_key];
    break;
  }
}
$url_parts = parse_url($url);

/** MySQL database name for Osclass */
$url_parts["path"] = substr($url_parts["path"], 1); // path includes the leading '/' like '/foo', which we don't want
define('DB_NAME', $url_parts["path"]);

/** MySQL database username */
define('DB_USER', $url_parts["user"]);

/** MySQL database password */
define('DB_PASSWORD', $url_parts["pass"]);

/** MySQL hostname */
define('DB_HOST', $url_parts["host"]); // TODO: handle port?

/** Database Table prefix */
$table_prefix = 'oc_';
if(array_key_exists('OSCLASS_DB_TABLE_PREFIX', $_ENV)){
  $table_prefix = $_ENV["OSCLASS_DB_TABLE_PREFIX"];
}
define('DB_TABLE_PREFIX', $table_prefix);

/** web paths **/
define('REL_WEB_URL', "/");
$heroku_url = $_ENV["HEROKU_URL"];
if(strcmp(substr($heroku_url, -1), "/") !== 0){
  $heroku_url .= "/"; # make sure we have a trailing slash otherwise includes break
}
define('WEB_PATH', $heroku_url);

?>
