<?php
/**
 * Object_Cache_apc class
 */
class Object_Cache_apc implements iObject_Cache{

   /**
     * Holds the cached objects
     *
     * @var array
     * @access private
     * @since 3.4
     */
    var $cache = array ();

    /**
     * The amount of times the cache data was already stored in the cache.
     *
     * @since 3.4
     * @access private
     * @var int
     */
    var $cache_hits = 0;

    /**
     * Amount of times the cache did not have the request in cache
     *
     * @var int
     * @access public
     * @since 3.4
     */
    var $cache_misses = 0;

    /**
     * The blog prefix to prepend to keys in non-global groups.
     *
     * @var int
     * @access private
     * @since 3.4
     */
    var $site_prefix;
    var $multisite;
    var $default_expiration = 60;

    /**
     * Adds data to the cache if it doesn't already exist.
     * @since 3.4
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param int $expire When to expire the cache contents
     * @return bool False if cache key and group already exist, true on success
     */
    function add( $key, $data, $expire = 0 ) {
        $id = $key;
        if ( $this->multisite ) {
            $id = $this->site_prefix . $key;
        }

        if ( is_object( $data ) ) {
                $data = clone $data;
        }

        $store_data = $data;

        if ( is_array( $data ) ) {
            $store_data = new ArrayObject( $data );
        }

        $expire = ( $expire == 0 ) ? $this->default_expiration : $expire;
        $result = apc_add( $id, $store_data, $expire );
        if ( false !== $result ) {
                $this->cache[$key] = $data;
        }

        return $result;
    }

    /**
     * Remove the contents of the cache key in the group
     * @since 3.4
     *
     * @param int|string $key What the contents in the cache are called
     * @return bool False if the contents weren't deleted and true on success
     */
    function delete($key) {

        if ( $this->multisite ) {
            $key = $this->site_prefix . $key;
        }

        $result = apc_delete( $key );
        if ( false !== $result ) {
                unset( $this->cache[$key] );
        }
        return $result;
    }

    /**
     * Clears the object cache of all data
     * @since 3.4
     *
     * @return bool Always returns true
     */
    function flush() {
        $this->cache = array ();
        if (extension_loaded('apcu')) {
            return apc_clear_cache();
        } else {
            return apc_clear_cache('user');
        }
        return true;
    }

    /**
     * Retrieves the cache contents, if it exists
     * @since 3.4
     *
     * @param int|string $key What the contents in the cache are called
     * @param bool $found if can be retrieved from cache
     * @return bool|mixed False on failure to retrieve contents or the cache
     *		contents on success
     */
    function get( $key, &$found = null ) {

        if ( $this->multisite )
            $key = $this->site_prefix . $key;

        if ( isset($this->cache[$key])) {
            if ( is_object( $this->cache[$key] ) ) {
                $value = clone $this->cache[$key];
            } else {
                $value = $this->cache[$key];
            }
            $this->cache_hits += 1;
            $return = $value;
        } else {
            $value = apc_fetch( $key , $found);

            if ( is_object( $value ) && 'ArrayObject' == get_class( $value ) ) {
                $value = $value->getArrayCopy();
            }
            if ( NULL === $value ) {
                $value = false;
            }
            $this->cache[$key] = ( is_object( $value ) ) ? clone $value : $value;
            if($found) {
                $this->cache_hits += 1;
                $return = $this->cache[$key];
            } else {
                $this->cache_misses += 1;
                $return = false;
            }
        }
        return $return;

    }

    /**
     * Reset keys
     *
     * @since 3.0.0
     * @deprecated 3.5.0
     */
    function reset() {
        $this->cache = array();

    }

    /**
     * Sets the data contents into the cache
     * @since 3.4
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param int $expire Not Used
     * @return bool Always returns true on success, false on failure
     */
    function set($key, $data, $expire = 0) {
        if ($this->multisite)
            $key = $this->site_prefix . $key;

        if (is_object($data))
            $data = clone $data;

        $store_data = $data;

        if (is_array($data))
            $store_data = new ArrayObject($data);

        $this->cache[$key] = $data;

        $expire = ( $expire == 0 ) ? $this->default_expiration : $expire;
        $result = apc_store($key, $store_data, $expire);

        return $result;
    }

    /**
     * Echoes the stats of the caching.
     * Gives the cache hits, and cache misses.
     *
     * @since 3.4
     */
    function stats() {
        echo "<div style='position:absolute; width:200px;top:0px;'><div style='float:right;margin-right:30px;margin-top:15px;border: 1px red solid;
border-radius: 17px;
padding: 1em;'><h2>APC stats</h2>";
        echo "<p>";
        echo "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
        echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
        echo "</p>";
        echo '<ul>';
        echo '</ul></div></div>';
    }

    /**
     * Utility function to determine whether a key exists in the cache.
     * @since 3.4
     *
     * @access protected
     */
    protected function _exists( $key ) {
        return isset( $this->cache[ $key ] );
    }

    /**
     * Sets up object properties; PHP 5 style constructor
     *
     * @since 3.4
     */
    function __construct() {

        $this->multisite = false;
//        if(SiteInfo::newInstance()->siteInfo!=array()) {
//            $info       = SiteInfo::newInstance()->siteInfo;
//            $site_id    = osc_sanitizeString($info);
//            $this->multisite = true;
//        }
        $site_id = '';
        $this->site_prefix =  $this->multisite ? $site_id . ':' : '';
    }

    /**
     * is_supported()
     *
     * Check to see if APC is available on this system, bail if it isn't.
     */
    static function is_supported()
    {
        if ( ! extension_loaded('apc') OR ini_get('apc.enabled') != "1") {
            error_log('The APC PHP extension must be loaded to use APC Cache.');
            return false;
        }
        return true;
    }

    function __destruct() {
        return true;
    }

    function _get_cache() {
        return 'apc';
    }
}