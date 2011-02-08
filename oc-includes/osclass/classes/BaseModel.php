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
    //generic in all the classes
    function _exportVariableToView($key, $value) {
        $this->aExported[$key] = $value ;
    }

    //generic in all the classes
    function _get($key) {
        return($this->aExported[$key]) ;
    }

    //generic in all the classes
    function _view($key = null) {
        if ($key) {
            print_r($this->aExported[$key]) ;
        } else {
            print_r($this->aExported) ;
        }
    }

    function doModel() {
        //generic things to do...
    }

    function doView() {
        //generic things to do...
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/header.php' ;
        if(!is_null($title)) {
            $header = $title ;
            if(!is_null($subTitle))
                $header .= ': ' . $subTitle ;
            echo '<div class="Header">' . $header . '</div>' ;
        }

        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/' . $file ;
        require 'themes/' . AdminThemes::newInstance()->getCurrentTheme() . '/footer.php' ;
    }
}
?>
