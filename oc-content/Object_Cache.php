<?php
/**
 *
 */
// tested
function osc_cache_add($key, $data, $expire = 0) {
    return Object_Cache::newInstance()->add($key, $data, $expire);
}
// tested
function osc_cache_close() {
    return Object_Cache::newInstance()->close();
}
// tested
function osc_cache_delete($key) {
    return Object_Cache::newInstance()->delete($key);
}
// tested
function osc_cache_flush() {
    return Object_Cache::newInstance()->flush();
}
// tested
function osc_cache_init() {
    Object_Cache::newInstance();
}

//function osc_cache_replace($key, $data, $expire = 0) {
//    return Object_Cache::newInstance()->replace($key, $data, $expire);
//}

function osc_cache_get($key, $force = false) {
    return Object_Cache::newInstance()->get($key, $force);
}

function osc_cache_set($key, $data, $expire = 0) {
    return Object_Cache::newInstance()->set($key, $data, $expire);
}

osc_add_hook('footer', 'cache_stats', 1);
function cache_stats() {
    Object_Cache::newInstance()->stats();
}
class Object_Cache {

    private static $instance;

    /**
     * Holds the cached objects
     *
     * @var array
     * @access private
     * @since 2.0.0
     */
    var $cache = array ();

    /**
     * The amount of times the cache data was already stored in the cache.
     *
     * @since 2.5.0
     * @access private
     * @var int
     */
    var $cache_hits = 0;

    /**
     * Amount of times the cache did not have the request in cache
     *
     * @var int
     * @access public
     * @since 2.0.0
     */
    var $cache_misses = 0;

    /**
     * The blog prefix to prepend to keys in non-global groups.
     *
     * @var int
     * @access private
     * @since 3.5.0
     */
    var $site_prefix;
    var $multisite;
    var $default_expiration = 60;

    /**
     * Adds data to the cache if it doesn't already exist.
     *
     * @uses WP_Object_Cache::_exists Checks to see if the cache already has data.
     * @uses WP_Object_Cache::set Sets the data after the checking the cache
     *		contents existence.
     *
     * @since 2.0.0
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire When to expire the cache contents
     * @return bool False if cache key and group already exist, true on success
     */
    function add( $key, $data, $expire = 0 ) {
        $id = $key;
        if ( $this->multisite )
            $id = $this->site_prefix . $key;

        if ( is_object( $data ) )
                $data = clone $data;

        $store_data = $data;

        if ( is_array( $data ) )
                $store_data = new ArrayObject( $data );

        if ( $this->_exists( $id ) )
            return false;

        $expire = ( $expire == 0 ) ? $this->default_expiration : $expire;

        $result = apc_add( $id, $store_data, $expire );
        if ( false !== $result ) {
                $this->cache[$key] = $data;
        }

        return $result;
    }

    /**
     * Remove the contents of the cache key in the group
     *
     * If the cache key does not exist in the group and $force parameter is set
     * to false, then nothing will happen. The $force parameter is set to false
     * by default.
     *
     * @since 2.0.0
     *
     * @param int|string $key What the contents in the cache are called
     * @param string $group Where the cache contents are grouped
     * @param bool $force Optional. Whether to force the unsetting of the cache
     *		key in the group
     * @return bool False if the contents weren't deleted and true on success
     */
    function delete($key, $force = false) {

        if ( $this->multisite )
            $key = $this->site_prefix . $key;

        if ( ! $force && ! $this->_exists( $key ) )
            return false;

        $result = apc_delete( $key );

        if ( false !== $result )
                unset( $this->cache[$key] );

        return $result;
    }

    /**
     * Clears the object cache of all data
     *
     * @since 2.0.0
     *
     * @return bool Always returns true
     */
    function flush() {
        $this->cache = array ();
        return apc_clear_cache( 'user' );
    }

    /**
     * Retrieves the cache contents, if it exists
     *
     * The contents will be first attempted to be retrieved by searching by the
     * key in the cache group. If the cache is hit (success) then the contents
     * are returned.
     *
     * On failure, the number of cache misses will be incremented.
     *
     * @since 2.0.0
     *
     * @param int|string $key What the contents in the cache are called
     * @param string $group Where the cache contents are grouped
     * @param string $force Whether to force a refetch rather than relying on the local cache (default is false)
     * @return bool|mixed False on failure to retrieve contents or the cache
     *		contents on success
     */
    function get( $key, $force = false, &$found = null ) {

        if ( $this->multisite )
            $key = $this->site_prefix . $key;

        if ( isset($this->cache[$key]) && ( !$force ) ) {
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
     * Replace the contents in the cache, if contents already exist
     *
     * @since 2.0.0
     * @see WP_Object_Cache::set()
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire When to expire the cache contents
     * @return bool False if not exists, true if contents were replaced
     */
//    function replace( $key, $data, $expire = '' ) {
//
//        $id = $key;
//        if ( $this->multisite )
//            $id = $this->site_prefix . $key;
//
//        if ( ! $this->_exists( $id ) )
//            return false;
//
//        return $this->set( $id, $data, $expire );
//    }

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
     *
     * The cache contents is grouped by the $group parameter followed by the
     * $key. This allows for duplicate ids in unique groups. Therefore, naming of
     * the group should be used with care and should follow normal function
     * naming guidelines outside of core WordPress usage.
     *
     * The $expire parameter is not used, because the cache will automatically
     * expire for each time a page is accessed and PHP finishes. The method is
     * more for cache plugins which use files.
     *
     * @since 2.0.0
     *
     * @param int|string $key What to call the contents in the cache
     * @param mixed $data The contents to store in the cache
     * @param string $group Where to group the cache contents
     * @param int $expire Not Used
     * @return bool Always returns true
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
     *
     * Gives the cache hits, and cache misses. Also prints every cached group,
     * key and the data.
     *
     * @since 2.0.0
     */
    function stats() {
        echo "<div style='position:absolute; width:100%;top:0px;'><div style='float:right;margin-right:30px;margin-top:30px;border: 1px red solid;
border-radius: 17px;
padding: 1em;'><h2>apc stats</h2>";
        echo "<p>";
        echo "<strong>Cache Hits:</strong> {$this->cache_hits}<br />";
        echo "<strong>Cache Misses:</strong> {$this->cache_misses}<br />";
        echo "</p>";
        echo '<ul>';
//        foreach ($this->cache as $key => $cache) {
//            echo "<li><strong>Group:</strong> $key - ( " . number_format( strlen( serialize( $cache ) ) / 1024, 2 ) . 'k )</li>';
//        }
        echo '</ul></div></div>';
    }

    /**
     * Utility function to determine whether a key exists in the cache.
     *
     * @since 3.4.0
     *
     * @access protected
     */
    protected function _exists( $key ) {
        return isset( $this->cache[ $key ] );
    }

    public static function newInstance()
    {
        if( !self::$instance instanceof self ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Sets up object properties; PHP 5 style constructor
     *
     * @since 2.0.8
     * @return null|WP_Object_Cache If cache is disabled, returns null.
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
     * Will save the object cache before object is completely destroyed.
     *
     * Called upon object destruction, which should be when PHP ends.
     *
     * @since  2.0.8
     *
     * @return bool True value. Won't be used by PHP
     */
    function __destruct() {
        return true;
    }
}