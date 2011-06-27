<?php

class SuperToolBar {

    private static $instance ;
    private $options;

	public static function newInstance() { 
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    public function __construct() {
        $this->options = array();
    }

    public function addOption($option) {
        if($option!='' && $option!=null) {
            $this->options[] = $option;
        }
    }

    public function getOptions() {
        return $this->options;
    }

}

?>
