<?php
/**
 * Object_Cache class
 */
interface iObject_Cache {

    function add( $key, $data, $expire = 0);
    function set($key, $data, $expire = 0);
    function get( $key, &$found = null ) ;
    function delete($key);
    function flush();
    function stats();
    function _get_cache(); // return string 
    static function is_supported();


    function __destruct();
}