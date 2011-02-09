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
class SecBaseModel extends BaseModel
{
    function __construct()
    {
        //Checking granting...
        if (!$this->isLogged()) {
            //If we are not logged or we do not have permissions -> go to the login page
            $this->showAuthFailPage() ;
        }

        parent::__construct () ;
    }
    
    //granting methods
    function setGranting($grant) {
        $this->grant = $grant ;
    }

    //destroying current session
    function logout() {
        //destroying session
        Session::newInstance()->session_destroy() ;
    }

    function doModel() {}

    function doView($file) {}
}
?>
