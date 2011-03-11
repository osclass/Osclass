<?php


/*
 *      OSCLass - software for creating and publishing online classified
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



    // Class: BaseDAO_osclass_t_keywords
    // Table: t_keywords
    // Database: osclass

    abstract class BaseDAO_osclass_t_keywords extends DAO
    {
        /* Attributes */
        var $DO ;
        var $aDO ;

        var $lastid ;                // Attribute to save the new ID of an INSERT
        var $aLastPk ;
        var $noDuplicateFields ;     // Attribute that implements the no duplication of the value of a field that is not a PK
        var $noDuplicateUniqueKey ;
        
        /* Constructor */
        function __construct() {
            $this->getConnection(DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DEBUG_LEVEL, "osclass") ;
        }

        /* Destrutor */
        function __destruct() {
            unset($this->DO) ;
            unset($this->aDO) ;
            unset($this->lastid) ;
            unset($this->aLastPk) ;
            unset($this->noDuplicateFields) ;
            unset($this->noDuplicateUniqueKey) ;
        }

        /* Methods */


        ////////////////////
        // SELECT METHODS //
        ////////////////////

        // Method that selects a row of "t_keywords" using the PK
        function selectBy_pk($s_md5, $fk_c_locale_code, $aCampos="", $where="")
        {
            $this->DO = "" ;
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE s_md5 = '" . addslashes($s_md5) . "' AND fk_c_locale_code = '" . addslashes($fk_c_locale_code) . "'" ;
            if ($where != "") $sql .= " AND " . $where ;

            //Recordset Object
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            $rs->query() ;

            //Saving the results in DO
            if (!$rs->movenext())
            {
                $this->DO = $rs->fetch_array() ;
            }
         }

        //Method that selects a row of "t_keywords" using "s_md5"
        function selectBy_s_md5($s_md5, $order = "", $aCampos="")
        {
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE s_md5 = '" . addslashes($s_md5) . "'";
            if ($order !== "") $sql .= " ORDER BY " . $order ;

            //executing SQL
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            $rs->query() ;

            //we save all the result items into aDO
            $this->aDO = array() ;
            while (!$rs->movenext())
            {
                $this->aDO["pk_" . $rs->value("s_md5") . "_" . $rs->value("fk_c_locale_code")] = $rs->fetch_array() ;
            }
        }
            

        //Method that selects a row of "t_keywords" using "fk_c_locale_code"
        function selectBy_fk_c_locale_code($fk_c_locale_code, $order = "", $aCampos="")
        {
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE fk_c_locale_code = '" . addslashes($fk_c_locale_code) . "'";
            if ($order !== "") $sql .= " ORDER BY " . $order ;

            //executing SQL
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            $rs->query() ;

            //we save all the result items into aDO
            $this->aDO = array() ;
            while (!$rs->movenext())
            {
                $this->aDO["pk_" . $rs->value("s_md5") . "_" . $rs->value("fk_c_locale_code")] = $rs->fetch_array() ;
            }
        }
            
        // Method that selects all the rows of "t_keywords" that satisfies the WHERE conditions
        function selectAll($where = "", $order = "", $inicio = "", $total = "", $aCampos = "", $group = "")
        {
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;

            if ($where) $sql .= " WHERE " . $where ;
            if ($group) $sql .= " GROUP BY " . $group ;
            if ($order) $sql .= " ORDER BY " . $order ;
            if ($inicio !== "" && $total !== "") $sql .= " LIMIT " . $inicio . ", " . $total ;

            //Recordset object
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            $rs->query() ;

            //We save results into aDO
            $this->aDO = array() ;
			
        	$is_set_pk_aCampos = ($aCampos == "" || (is_array($aCampos) && isset($aCampos["s_md5"]) && isset($aCampos["fk_c_locale_code"])));
			
					
            while (!$rs->movenext())
            {
            	if($is_set_pk_aCampos) {
                	$this->aDO["pk_" . $rs->value("s_md5") . "_" . $rs->value("fk_c_locale_code")] = $rs->fetch_array() ;
				} else {
                	$this->aDO[] = $rs->fetch_array() ;
				}
            }
        }


        // Method that return the total of the rows of "t_keywords" that satisfies the WHERE conditions
        function selectTotal($where = "", $group = "")
        {
            $sql  = "SELECT count(s_md5) AS total " ;
            if ($group) $sql.= ", " . $group . " " ;
            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            if ($where) $sql .= " WHERE " . $where ;
            if ($group) $sql .= " GROUP BY " . $group ;

            //Recordset object
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            if ($rs->query()) {
                if ($group) {
                    while (!$rs->movenext())
                    {
                        $this->aDO["gk_".$rs->value($group)] = $rs->value("total") ;
                    }
                    return $this->aDO ;
                } else {
                    $rs->movenext() ;
                    return ($rs->value("total")) ;
                }
            } else return (0);
        }

        ////////////////////
        // INSERT METHODS //
        ////////////////////
        
        function insert($DO)
        {
            $can_insert = true ;
            if (is_array($this->noDuplicateFields) || is_array($this->noDuplicateUniqueKey))
            {
                $where_select = "" ;
                if (is_array($this->noDuplicateFields)) {
                    foreach ($this->noDuplicateFields as $val) {
                        if ($where_select != "") $where_select .= " OR " ;
                        $where_select .= $val . " = '" . addslashes($DO[$val]) . "'" ;
                    }
                } else {
                    foreach ($this->noDuplicateUniqueKey as $val) {
                        if ($where_select != "") $where_select .= " AND " ;
                        $where_select .= $val . " = '" . addslashes($DO[$val]) . "'" ;
                    }
                }

                $can_insert = true ;
                if ($where_select != "") {
                    $sql = "SELECT * FROM " . $this->get_table_name('t_keywords') . " WHERE " . $where_select ;

                    //Recordset object
                    $rs = new DBRecordsetClass($this->conn, $sql) ;
                    $rs->query() ;

                    //If it return results we cannot do the insert
                    if (!$rs->movenext()) {
                        $can_insert = false ;

                        //Useful if we need to do the UPDATE, just in case the register exists
                        $this->aLastPk = array() ;
                        $this->aLastPk["s_md5"] = $rs->value("s_md5") ;
                        $this->aLastPk = array() ;
                        $this->aLastPk["fk_c_locale_code"] = $rs->value("fk_c_locale_code") ;
                    }
                }
            }

            if ($can_insert)
            {
                $sql  = "INSERT INTO " . $this->get_table_name('t_keywords') . " SET " ;

                $cond = "" ;
                if (isset($DO["s_md5"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_md5 = '" . addslashes($DO["s_md5"]) . "'" ;
                }
                if (isset($DO["fk_c_locale_code"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "fk_c_locale_code = '" . addslashes($DO["fk_c_locale_code"]) . "'" ;
                }
                if (isset($DO["s_original_text"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_original_text = '" . addslashes($DO["s_original_text"]) . "'" ;
                }
                if (isset($DO["s_anchor_text"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_anchor_text = '" . addslashes($DO["s_anchor_text"]) . "'" ;
                }
                if (isset($DO["s_normalized_text"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_normalized_text = '" . addslashes($DO["s_normalized_text"]) . "'" ;
                }
                if (isset($DO["fk_i_category_id"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_category_id"] !== "")     $cond .= "fk_i_category_id = " . addslashes($DO["fk_i_category_id"]) ;
                    else $cond .= "fk_i_category_id = NULL" ;
                }
                if (isset($DO["fk_i_city_id"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_id"] !== "")     $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                    else $cond .= "fk_i_city_id = NULL" ;
                }
                $sql .= $cond ;

                //SQL Execution
                $comm = new DBCommandClass($this->conn, $sql) ;
                $status = $comm->execute() ;
                if (!$status && $this->conn->error_level == 1062) $status = -2 ;
                //Only for rows inserted with an auto_increment as a PK
                $this->lastid = $comm->lastid ;
                return($status) ;

            } else {
                return(-2) ;
            }
        }

		function multiple_inserts($mDO) {

			if(count($mDO) == 0) return;

            $sql  = "INSERT INTO " . $this->get_table_name('t_keywords') . " (s_md5,fk_c_locale_code,s_original_text,s_anchor_text,s_normalized_text,fk_i_category_id,fk_i_city_id) VALUES " ;
            $cond = "" ;$primera = true;

						foreach($mDO as $DO) {
							$cond = "";
							if(!$primera) $sql .= ",";
							$sql .= " ( ";

			
		                if (isset($DO["s_md5"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_md5"]) . "'" ; }
		                if (isset($DO["fk_c_locale_code"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["fk_c_locale_code"]) . "'" ; }
		                if (isset($DO["s_original_text"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_original_text"]) . "'" ; }
		                if (isset($DO["s_anchor_text"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_anchor_text"]) . "'" ; }
		                if (isset($DO["s_normalized_text"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_normalized_text"]) . "'" ; }
		                if (isset($DO["fk_i_category_id"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["fk_i_category_id"] !== "")     $cond .= "" . addslashes($DO["fk_i_category_id"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["fk_i_city_id"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["fk_i_city_id"] !== "")     $cond .= "" . addslashes($DO["fk_i_city_id"]) ;
		                    else $cond .= " NULL" ; }
		                $sql .= $cond ;

						$sql .= " ) ";
						$primera = false;
						}

                //SQL Execution
                $comm = new DBCommandClass($this->conn, $sql) ;
                $status = $comm->execute() ;
                if (!$status && $this->conn->error_level == 1062) $status = -2 ;
                //Only for rows inserted with an auto_increment as a PK
                $this->lastid = $comm->lastid ;
                return($status) ;

		}

        ////////////////////
        // UPDATE METHODS //
        ////////////////////

        //Update Methods using PK
        function updateBy_pk($DO, $where="")
        {
            $can_update = true ;
            if (is_array($this->noDuplicateFields) || is_array($this->noDuplicateUniqueKey))
            {
                $where_select = "" ;
                if (is_array($this->noDuplicateFields)) {
                    foreach ($this->noDuplicateFields as $val) {
                        if ($where_select != "") $where_select .= " OR " ;
                        $where_select .= $val . " = '" . addslashes($DO[$val]) . "'" ;
                    }
                } else {
                    foreach ($this->noDuplicateUniqueKey as $val) {
                        if ($where_select != "") $where_select .= " AND " ;
                        $where_select .= $val . " = '" . addslashes($DO[$val]) . "'" ;
                    }
                }

                if ($where_select != "") {
                    $sql = "SELECT * FROM " . $this->get_table_name('t_keywords') . " WHERE (" . $where_select . ") AND NOT (s_md5 = '" . addslashes($DO["s_md5"]) . "' AND fk_c_locale_code = '" . addslashes($DO["fk_c_locale_code"]) . "')";

                    //Recordset Object
                    $rs = new DBRecordsetClass($this->conn, $sql) ;
                    $rs->query() ;

                    //If it returns results we cannot do the update
                    if (!$rs->movenext()) $can_update = false ;
                }
            }

            if ($can_update)
            {
                $sql  = "UPDATE " . $this->get_table_name('t_keywords') . " SET " ;

                $cond = "" ;
                if (isset($DO["s_md5"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_md5 = '" . addslashes($DO["s_md5"]) . "'" ;
                }
                
                if (isset($DO["fk_c_locale_code"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "fk_c_locale_code = '" . addslashes($DO["fk_c_locale_code"]) . "'" ;
                }
                
                if (isset($DO["s_original_text"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_original_text = '" . addslashes($DO["s_original_text"]) . "'" ;
                }
                
                if (isset($DO["s_anchor_text"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_anchor_text = '" . addslashes($DO["s_anchor_text"]) . "'" ;
                }
                
                if (isset($DO["s_normalized_text"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_normalized_text = '" . addslashes($DO["s_normalized_text"]) . "'" ;
                }
                
                if (isset($DO["fk_i_category_id"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_category_id"] != "")     $cond .= "fk_i_category_id = " . addslashes($DO["fk_i_category_id"]) ;
                    else $cond .= "fk_i_category_id = NULL" ;
                }
                
                if (isset($DO["fk_i_city_id"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_id"] != "")     $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                    else $cond .= "fk_i_city_id = NULL" ;
                }
                $sql .= $cond . " WHERE s_md5 = '" . addslashes($DO["s_md5"]) . "' AND fk_c_locale_code = '" . addslashes($DO["fk_c_locale_code"]) . "'" ;
                if ($where != "") $sql .= " AND " . $where ;

                //SQL Execution
                $comm = new DBCommandClass($this->conn, $sql) ;
                $status = $comm->execute() ;
                if (!$status && $this->conn->error_level != 0) {
                    if ($this->conn->error_level == 1062) $status = -2 ;
                    else $status = -1 ;
                }
                return ($status) ;
            } else {
                //It is not atomic
                return (-2) ;
            }
        }
        
        
        //Update method using a where
        function update($DO, $where)
        {
            $sql  = "UPDATE " . $this->get_table_name('t_keywords') . " SET " ;

            $cond = "" ;
            if (isset($DO["s_md5"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_md5 = '" . addslashes($DO["s_md5"]) . "'" ;
            }
                
            if (isset($DO["fk_c_locale_code"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "fk_c_locale_code = '" . addslashes($DO["fk_c_locale_code"]) . "'" ;
            }
                
            if (isset($DO["s_original_text"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_original_text = '" . addslashes($DO["s_original_text"]) . "'" ;
            }
                
            if (isset($DO["s_anchor_text"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_anchor_text = '" . addslashes($DO["s_anchor_text"]) . "'" ;
            }
                
            if (isset($DO["s_normalized_text"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_normalized_text = '" . addslashes($DO["s_normalized_text"]) . "'" ;
            }
                
            if (isset($DO["fk_i_category_id"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["fk_i_category_id"] != "") $cond .= "fk_i_category_id = " . addslashes($DO["fk_i_category_id"]) ;
                else $cond .= "fk_i_category_id = NULL" ;
            }
                
            if (isset($DO["fk_i_city_id"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["fk_i_city_id"] != "") $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                else $cond .= "fk_i_city_id = NULL" ;
            }
                
            $sql .= $cond . " WHERE " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            $status = $comm->execute() ;
            if (!$status && $this->conn->error_level != 0) {
                if ($this->conn->error_level == 1062) $status = -2 ;
                else $status = -1 ;
            }
            return ($status) ;
        }


        ////////////////////
        // DELETE METHODS //
        ////////////////////

        //Deleting using a where
        function delete($where="")
        {
            $sql  = "DELETE " ;
            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            if ($where != "") $sql .= " WHERE " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }
        
        //Delete Method using a PK
        function deleteBy_pk($s_md5, $fk_c_locale_code, $where="")
        {
            $sql  = "DELETE " ;
            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE s_md5 = '" . addslashes($s_md5) . "' AND fk_c_locale_code = '" . addslashes($fk_c_locale_code) . "'" ;
            if ($where != "") $sql .= " AND " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }

        //Delete method using "s_md5"
        function deleteBy_s_md5($s_md5, $where="")
        {
            $sql  = "DELETE " ;
            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE s_md5 = '" . addslashes($s_md5) . "'" ;
            if ($where != "") $sql .= " AND " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }

        //Delete method using "fk_c_locale_code"
        function deleteBy_fk_c_locale_code($fk_c_locale_code, $where="")
        {
            $sql  = "DELETE " ;
            $sql .= "FROM " . $this->get_table_name('t_keywords') . " " ;
            $sql .= "WHERE fk_c_locale_code = '" . addslashes($fk_c_locale_code) . "'" ;
            if ($where != "") $sql .= " AND " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }
    }
?>