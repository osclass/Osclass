<?php

/**
 * Styles enqueue class.
 *
 * @since 3.1.1
 */
class Styles {

    var $styles = array();

    private static $instance;

    public static function newInstance()
    {
        if(!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $styles = array();
    }

    /**
     * Add style to be loaded
     *
     * @param type $id
     * @param type $url
     */
    public function addStyle($id, $url)
    {
        $this->styles[$id] = $url;
    }

    /**
     * Remove style to not be loaded
     *
     * @param type $id
     */
    public function removeStyle($id)
    {
        unset($this->styles[$id]);
    }

    /**
     * Get the css styles urls
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * Print the HTML tags to load the styles
     */
    public function printStyles()
    {
        foreach($this->styles as $css) {
            echo '<link href="' . osc_apply_filter('style_url', $css) . '" rel="stylesheet" type="text/css" />' . PHP_EOL;
        }
    }
}
