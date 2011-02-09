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
    //atributos...
		public $comprobar ;


		/**
		* M√©todo constructor de la clase.
		* Recibe como par√°metros de entrada un booleano que indica si se debe comprobar
		* si el usuario esta autentificado en el sistema. En caso que haya que comprobarlo
		* y el usuario no este autentificado en el sistema se cede el control al m√≥dulo
		* AUTH_FAIL_MODULE.
		*
		* @param bolean -> Indica si se debe comprobar la autentificaci√≥n del usuario.
		*/
		function __construct($comprobar = true)
		{
            echo "constructor de SecBaseModel" ;
			//Checking granting...
            if (!$this->isLogged()) {
                //If we are not logged or we do not have permissions -> go to the login page
                $this->showAuthFailPage() ;
            }

			parent::__construct () ;
		}

		//abstract function isLogged() ;
		//abstract function showAuthFailPage() ;

		//granting methods
		function setGranting($grant) {
			$this->grant = $grant ;
		}

	    //destroying current session
		function logout() {
			//destroying session
			echo "logout de SecBaseModel" ;
            Session::newInstance()->session_destroy() ;
		}

        function doModel() {}

        function doView() {}	
}
?>
