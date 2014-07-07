<?php
/**
 * Object_Cache class
 */
interface iObject_Cache {

    function add( $key, $data, $expire = 0);
    function delete($key, $force = false);
    function flush();
    function get( $key, $force = false, &$found = null ) ;
    function set($key, $data, $expire = 0);
    function stats();
    

    function __destruct();
}