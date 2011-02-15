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
                            ,$userEmailTmp['s_new_email']
                    ) ;

        if (!$status) {
            $this->conn->osc_dbExec(
                            'UPDATE %s SET s_new_email = \'%s\', dt_date = now() WHERE fk_i_user_id = %d'
                            ,$this->getTableName()
                            ,$userEmailTmp['s_new_email']
                            ,$userEmailTmp['fk_i_user_id']
                    ) ;

        }
	}

   /*public function findByEmail($email) {
		$results = $this->listWhere("s_email = '%s'", $email);
		return count($results) == 1 ? $results[0] : null;
	}

        public function findByUsername($username) {
		$results = $this->listWhere("s_username = '%s'", $username);
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByCredentials($userName, $password) {
		$results = $this->listWhere("s_username = '%s' AND s_password = '%s'", $userName, sha1($password));
		return count($results) == 1 ? $results[0] : null;
	}

	public function findByIdSecret($id, $secret) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_secret = '%s'",
			$this->getTableName(), $id, $secret);
	}

	public function updateArray($admin) {
		$this->conn->osc_dbExec("UPDATE %s SET s_name = '%s', s_username = '%s', s_email = '%s', s_password = '%s' WHERE pk_i_id = %d", $this->getTableName(),
			$admin['name'], $admin['userName'], $admin['email'], $admin['password'], $admin['id']);
	}*/
}
?>
