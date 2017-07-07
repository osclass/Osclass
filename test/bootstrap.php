<?php
// autoload.php
use Composer\Autoload\ClassLoader;
/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';


/**
 * Load Classes
 */
require_once __DIR__.'/../oc-includes/osclass/classes/Dependencies.php';
require_once __DIR__.'/../oc-includes/osclass/classes/Scripts.php';
require_once __DIR__.'/../oc-includes/osclass/classes/Styles.php';


return $loader;