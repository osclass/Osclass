<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseModel
 *
 * @author danielo
 */
abstract class BaseModel
{
    //array for variables needed at the view layer
    private $aExported ;
    //action to execute
    protected $action ;
    //array of css
    protected $aCss ;
    //array of js
    protected $aJs ;
    

    function  __construct() {
        Session::newInstance()->session_start() ;
        $this->action = Params::getParam('action') ;
        $this->aExported = array() ;
        $this->aCss = array() ;
        $this->aJs = array() ;
    }

    //to export variables at the business layer
    function _exportVariableToView($key, $value) {
        $this->aExported[$key] = $value ;
    }

    //to get the exported variables for the view
    function _get($key) {
        return($this->aExported[$key]) ;
    }

    //only for debug
    function _view($key = null) {
        if ($key) {
            print_r($this->aExported[$key]) ;
        } else {
            print_r($this->aExported) ;
        }
    }

    //Funciones que se tendran que reescribir en la clase que extienda de esta
    protected abstract function doModel() ;
    protected abstract function doView($file) ;

    function redirectTo($url) {
        header('Location: ' . $url) ;
        exit ;
    }

    function osc_print_head() {
        require osc_current_web_theme_path() . 'head.php' ;
    }

    function osc_print_header() {
        require osc_current_web_theme_path() . 'header.php' ;
    }

    function osc_print_html($file) {
        require osc_current_web_theme_path() . $file ;
    }

    function osc_print_footer() {
        require osc_current_web_theme_path() . 'footer.php' ;
    }

    function add_css($css_filename) {
        $this->aCss[] = osc_current_admin_theme_styles_url() . $css_filename ;
    }

    function add_js($js_filename) {
        $this->aJs[] = osc_current_admin_theme_js_url() . $js_filename ;
    }

    function add_global_css($css_filename) {
        $this->aCss[] = osc_css_url() . $css_filename ;
    }

    function add_global_js($js_filename) {
        $this->aJs[] = osc_js_url() . $js_filename ;
    }

    function get_css() {
        return ( $this->aCss ) ;
    }

    function get_js() {
        return ( $this->aJs ) ;
    }
}

?>