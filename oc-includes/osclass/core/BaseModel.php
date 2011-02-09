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

    function  __construct() {
        $this->action = Params::getParam('action') ;
        $this->aExported = array() ;
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

    function osc_print_head() {
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/head.php' ;
    }
    
    function osc_print_header() {
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/header.php' ;
    }

    function osc_print_html($file) {
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/' . $file ;
    }

    function osc_print_footer() {
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/footer.php' ;
    }

    //Funciones que se tendran que reescribir en la clase que extienda de esta
    protected abstract function doModel() ;
    protected abstract function doView($file) ;
}

?>