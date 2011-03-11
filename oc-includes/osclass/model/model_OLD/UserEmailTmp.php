<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserEmailTmp
 *
 * @author danielo
 */
class UserEmailTmp extends DAO {

    private static $instance ;

	public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

	public function getTableName() { return DB_TABLE_PREFIX . 't_user_email_tmp' ; }

    public function findByPk($id) {
		$results = $this->listWhere("fk_i_user_id = '%s'", $id) ;
		return count($results) == 1 ? $results[0] : null ;
	}

    public function insertOrUpdate($userEmailTmp) {

        $status = $this->conn->osc_dbExec(
                            'INSERT INTO %s (fk_i_user_id, s_new_email, dt_date) VALUES (%d, \'%s\', now())'
                            ,$this->getTableName()
                            ,$userEmailTmp['fk_i_user_id']
                            ,  addslashes($userEmailTmp['s_new_email'])
                    ) ;

        if (!$status) {
            $this->conn->osc_dbExec(
                            'UPDATE %s SET s_new_email = \'%s\', dt_date = now() WHERE fk_i_user_id = %d'
                            ,$this->getTableName()
                            ,  addslashes($userEmailTmp['s_new_email'])
                            ,$userEmailTmp['fk_i_user_id']
                    ) ;

        }
	}
}
?>
