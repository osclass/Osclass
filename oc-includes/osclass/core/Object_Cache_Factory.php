<?php
class Object_Cache_Factory {
    public static function getCache() {
        $cache = 'default';
        if( defined('OSC_CACHE') ) {
            $cache = OSC_CACHE;
        }

        $file = dirname(__FILE__) . '/caches/Object_Cache_' . $cache . '.php';

        if(strpos($file, '../')===false && file_exists($file)) {
            require_once $file;
            $cache_class = 'Object_Cache_'.$cache;
            if(class_exists($cache_class)) {
                return new $cache_class();
            }
        }
        throw new Exception('Unkwon cache');
    }
}