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
class BaseModel
{
    //generic in all the classes
    private $action ;
    //generic in all the classes
    private $aExported ;

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

    function doModel() {
        //sistema de seguridad para el caso en que estamos en el backoffice (tambien controla grantings)
        /* if(granting guardado no corresponde al de esta clase) {
            die("") || redirect
        } */
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

    function doView($file) {
        //generic things to do...
        $this->osc_print_html($file) ;
    }
}
?>
