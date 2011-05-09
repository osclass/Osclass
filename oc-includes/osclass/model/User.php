<?php
/**
 * OSClass – software for creating and publishing online classified advertising platforms
 *
 * Copyright (C) 2010 OSCLASS
 *
 * This program is free software: you can redistribute it and/or modify it under the terms
 * of the GNU Affero General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this program. If not, see <http://www.gnu.org/licenses/>.
 */


class User extends DAO {

	private static $instance ;

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }
    
	public function getTableName()
    {
        return DB_TABLE_PREFIX . 't_user';
    }

    public function getDescriptionTableName()
    {
        return DB_TABLE_PREFIX . 't_user_description';
    }

    public function findByPrimaryKey($id, $locale = null)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName();
        $sql .= ' WHERE ' . $this->getPrimaryKey() . ' = ' . $id;
        $row = $this->conn->osc_dbFetchResult($sql);

        if(is_null($row)) {
            return array();
        }

        $sql_desc = 'SELECT * FROM ';
        $sql_desc .= $this->getDescriptionTableName() . ' WHERE fk_i_user_id = ' . $id;
        if(!is_null($locale)) {
            $sql_desc .= ' AND fk_c_locale_code  = \'' . $locale . '\' ';
        }
        $sub_rows = $this->conn->osc_dbFetchResults($sql_desc);

        $row['locale'] = array();
        foreach($sub_rows as $sub_row) {
            $row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
        }

        return $row;
    }

	public function findByEmail($email)
        {
            $results = $this->listWhere("s_email = '%s'", $email);
            return count($results) == 1 ? $results[0] : null;
	}

	public function findByCredentials($key, $password)
        {
            $results = $this->listWhere("s_email = '%s' AND s_password = '%s'", $key, sha1($password));
            if( count($results) == 1 ) {
                return $results[0] ;
            }
            return null ;
	}

	public function findByIdSecret($id, $secret)
        {
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_secret = '%s'", $this->getTableName(), $id, $secret);
	}

	public function findByIdPasswordSecret($id, $secret)
        {
            if($secret=='') { return null; }
            $date = date("Y-m-d H:i:s", (time()-(24*3600)));
            return $this->conn->osc_dbFetchResult("SELECT * FROM %s WHERE pk_i_id = %d AND s_pass_code = '%s' AND s_pass_date >= '%s'", $this->getTableName(), $id, $secret, $date);
	}
	

    
    
    public function deleteUser($id = null)
        {
	    if($id!=null) {
	        osc_run_hook('delete_user', $id);
	        $items = $this->conn->osc_dbFetchResults("SELECT pk_i_id FROM %st_item WHERE fk_i_user_id = %d", DB_TABLE_PREFIX, $id);
	        $itemManager = Item::newInstance();
	        foreach($items as $item) {
                    $itemManager->deleteByPrimaryKey($item['pk_i_id']);
                }
                $this->conn->osc_dbExec('DELETE FROM %st_user_email_tmp WHERE fk_i_user_id = %d', DB_TABLE_PREFIX, $id);
                $this->conn->osc_dbExec('DELETE FROM %st_user_description WHERE fk_i_user_id = %d', DB_TABLE_PREFIX, $id);
                $this->conn->osc_dbExec('DELETE FROM %st_alerts WHERE fk_i_user_id = %d', DB_TABLE_PREFIX, $id);
                $this->conn->osc_dbExec('DELETE FROM %st_user WHERE pk_i_id = %d', DB_TABLE_PREFIX, $id);
                return true;
	    }
	    return false;
	}

        private function insertDescription($id, $locale, $info)
        {
            $sql = 'INSERT INTO ' . $this->getDescriptionTableName() . ' (fk_i_user_id, fk_c_locale_code, s_info)';
            $sql .= ' VALUES (' . sprintf('%d, \'%s\', \'%s\')', $id, $locale, addslashes($info));

            $this->conn->osc_dBExec($sql);

            if($this->conn->get_affected_rows() == 0) {
                return false;
            }

            return true;
        }

        public function updateDescription($id, $locale, $info)
        {
            $conditions = array('fk_c_locale_code' => $locale, 'fk_i_user_id' => $id);
            $exist= $this->existDescription($conditions);

            if(!$exist) {
                $result = $this->insertDescription($id, $locale, $info);
                return $result;
            }

            $sql = 'UPDATE ' . $this->getDescriptionTableName() . ' SET ';
            $sql .= ' s_info = \'' . addslashes($info) . '\'';
            $sql .= ' WHERE fk_c_locale_code = \'' . $locale . '\' AND fk_i_user_id = ' . $id;

            $this->conn->osc_dbExec($sql);

            $result = $this->conn->get_affected_rows();

            return $result;
        }

        public function existDescription($conditions)
        {
            $where = array();
            foreach($conditions as $key => $value) {
                if($key == DB_CUSTOM_COND)
                    $where[] = $value;
                else
                    $where[] = $key . ' = ' . $this->formatValue($value);
            }
            $where = implode(' AND ', $where);
            $sql  = sprintf("SELECT COUNT(*) FROM %s WHERE " . $where, $this->getDescriptionTableName());

            $result = $this->conn->osc_dbFetchValue($sql);

            return (bool) $result;
        }
}
