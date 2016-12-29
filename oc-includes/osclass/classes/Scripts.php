<?php

/**
 * Scripts enqueue class.
 *
 * @since 3.1.1
 */
class Scripts extends Dependencies {

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
        parent::__construct();
    }

    /**
     * Add script to be loaded
     *
     * @param type $id
     * @param type $url
     * @param type $dependencies mixed, it could be an array or a string
     */
    public function registerScript($id, $url, $dependencies = null)
    {
        $this->register($id, $url, $dependencies);
    }

    /**
     * Remove script to not be loaded
     *
     * @param type $id
     */
    public function unregisterScript($id)
    {
        $this->unregister($id);
    }

    /**
     * Enqueu script to be loaded
     *
     * @param type $id
     */
    public function enqueuScript($id)
    {
        $this->queue[$id] = $id;
    }

    /**
     * Remove script to not be loaded
     *
     * @param type $id
     */
    public function removeScript($id)
    {
        unset($this->queue[$id]);
    }

    /**
     *  Get the scripts urls
     */
    public function getScripts()
    {
        $scripts = array();
        parent::order();
        foreach($this->resolved as $id) {
            if( isset($this->registered[$id]['url']) ) {
                $scripts[] = $this->registered[$id]['url'];
            }
        }
        return $scripts;
    }

    /**
     *  Print the HTML tags to load the scripts
     */
    public function printScripts()
    {
        foreach($this->getScripts() as $script) {
            if($script!='') {
                echo '<script type="text/javascript" src="' . osc_apply_filter('theme_url', $script) . '"></script>' . PHP_EOL;
            }
        }
    }
}

