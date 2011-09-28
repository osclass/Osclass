<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

    /*
     *      OSCLass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2010 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
                                'INSERT INTO %s (fk_i_user_id, s_new_email, dt_date) VALUES (%d, \'%s\', \'%s\')'
                                ,$this->getTableName()
                                ,$userEmailTmp['fk_i_user_id']
                                ,  addslashes($userEmailTmp['s_new_email'])
                                , date('Y-m-d H:i:s')
                        ) ;

            if (!$status) {
                $this->conn->osc_dbExec(
                                'UPDATE %s SET s_new_email = \'%s\', dt_date = \'%s\' WHERE fk_i_user_id = %d'
                                , $this->getTableName()
                                , addslashes($userEmailTmp['s_new_email'])
                                , date('Y-m-d H:i:s')
                                , $userEmailTmp['fk_i_user_id']
                        ) ;

            }
        }
    }

?>