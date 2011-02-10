<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CAdminAjax
 *
 * @author danielo
 */
class CAdminAjax extends AdminSecBaseModel
{
    function __construct() {
        parent::__construct() ;
    }

    //Business Layer...
    function doModel() {
        parent::doModel() ;

        //specific things for this class
        switch ($this->action)
        {
            case 'bulk_actions':
            break;
        }
    }
}
?>
