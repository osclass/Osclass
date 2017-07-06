<?php
// autoload.php
use Composer\Autoload\ClassLoader;
/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../oc-includes/osclass/classes/Dependencies.php';


return $loader;