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



    // Class: BaseDAO_osclass_t_user
    // Table: t_user
    // Database: osclass

    abstract class BaseDAO_osclass_t_user extends DAO
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

        // Method that selects a row of "t_user" using the PK
        function selectBy_pk($pk_i_id, $aCampos="", $where="")
        {
            $this->DO = "" ;
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_user') . " " ;
            $sql .= "WHERE pk_i_id = '" . addslashes($pk_i_id) . "'" ;
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
        // Method that selects all the rows of "t_user" that satisfies the WHERE conditions
        function selectAll($where = "", $order = "", $inicio = "", $total = "", $aCampos = "", $group = "")
        {
            $sql  = "SELECT " ;

            if ($aCampos == "") $sql .= "* " ;
            else $sql .= implode (", ", $aCampos) . " " ;

            $sql .= "FROM " . $this->get_table_name('t_user') . " " ;

            if ($where) $sql .= " WHERE " . $where ;
            if ($group) $sql .= " GROUP BY " . $group ;
            if ($order) $sql .= " ORDER BY " . $order ;
            if ($inicio !== "" && $total !== "") $sql .= " LIMIT " . $inicio . ", " . $total ;

            //Recordset object
            $rs = new DBRecordsetClass($this->conn, $sql) ;
            $rs->query() ;

            //We save results into aDO
            $this->aDO = array() ;
			
        	$is_set_pk_aCampos = ($aCampos == "" || (is_array($aCampos) && isset($aCampos["pk_i_id"])));
			
					
            while (!$rs->movenext())
            {
            	if($is_set_pk_aCampos) {
                	$this->aDO["pk_" . $rs->value("pk_i_id")] = $rs->fetch_array() ;
				} else {
                	$this->aDO[] = $rs->fetch_array() ;
				}
            }
        }


        // Method that return the total of the rows of "t_user" that satisfies the WHERE conditions
        function selectTotal($where = "", $group = "")
        {
            $sql  = "SELECT count(pk_i_id) AS total " ;
            if ($group) $sql.= ", " . $group . " " ;
            $sql .= "FROM " . $this->get_table_name('t_user') . " " ;
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
                    $sql = "SELECT * FROM " . $this->get_table_name('t_user') . " WHERE " . $where_select ;

                    //Recordset object
                    $rs = new DBRecordsetClass($this->conn, $sql) ;
                    $rs->query() ;

                    //If it return results we cannot do the insert
                    if (!$rs->movenext()) {
                        $can_insert = false ;

                        //Useful if we need to do the UPDATE, just in case the register exists
                        $this->aLastPk = array() ;
                        $this->aLastPk["pk_i_id"] = $rs->value("pk_i_id") ;
                    }
                }
            }

            if ($can_insert)
            {
                $sql  = "INSERT INTO " . $this->get_table_name('t_user') . " SET " ;

                $cond = "" ;
                if (isset($DO["dt_reg_date"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["dt_reg_date"] !== "")    $cond .= "dt_reg_date = '" . addslashes($DO["dt_reg_date"]) . "'" ;
                    else $cond .= "dt_reg_date = NULL" ;
                }
                if (isset($DO["dt_mod_date"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["dt_mod_date"] !== "")    $cond .= "dt_mod_date = '" . addslashes($DO["dt_mod_date"]) . "'" ;
                    else $cond .= "dt_mod_date = NULL" ;
                }
                if (isset($DO["s_name"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_name = '" . addslashes($DO["s_name"]) . "'" ;
                }
                if (isset($DO["s_username"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_username = '" . addslashes($DO["s_username"]) . "'" ;
                }
                if (isset($DO["s_password"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_password = '" . addslashes($DO["s_password"]) . "'" ;
                }
                if (isset($DO["s_secret"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_secret = '" . addslashes($DO["s_secret"]) . "'" ;
                }
                if (isset($DO["s_email"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_email = '" . addslashes($DO["s_email"]) . "'" ;
                }
                if (isset($DO["s_website"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_website = '" . addslashes($DO["s_website"]) . "'" ;
                }
                if (isset($DO["s_phone_land"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_phone_land = '" . addslashes($DO["s_phone_land"]) . "'" ;
                }
                if (isset($DO["s_phone_mobile"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_phone_mobile = '" . addslashes($DO["s_phone_mobile"]) . "'" ;
                }
                if (isset($DO["b_enabled"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["b_enabled"] !== "")     $cond .= "b_enabled = " . addslashes($DO["b_enabled"]) ;
                    else $cond .= "b_enabled = NULL" ;
                }
                if (isset($DO["s_pass_code"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_pass_code = '" . addslashes($DO["s_pass_code"]) . "'" ;
                }
                if (isset($DO["s_pass_date"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["s_pass_date"] !== "")    $cond .= "s_pass_date = '" . addslashes($DO["s_pass_date"]) . "'" ;
                    else $cond .= "s_pass_date = NULL" ;
                }
                if (isset($DO["s_pass_question"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_pass_question = '" . addslashes($DO["s_pass_question"]) . "'" ;
                }
                if (isset($DO["s_pass_answer"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_pass_answer = '" . addslashes($DO["s_pass_answer"]) . "'" ;
                }
                if (isset($DO["s_pass_ip"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_pass_ip = '" . addslashes($DO["s_pass_ip"]) . "'" ;
                }
                if (isset($DO["fk_c_country_code"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "fk_c_country_code = '" . addslashes($DO["fk_c_country_code"]) . "'" ;
                }
                if (isset($DO["s_country"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_country = '" . addslashes($DO["s_country"]) . "'" ;
                }
                if (isset($DO["s_address"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_address = '" . addslashes($DO["s_address"]) . "'" ;
                }
                if (isset($DO["s_zip"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_zip = '" . addslashes($DO["s_zip"]) . "'" ;
                }
                if (isset($DO["fk_i_region_id"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_region_id"] !== "")     $cond .= "fk_i_region_id = " . addslashes($DO["fk_i_region_id"]) ;
                    else $cond .= "fk_i_region_id = NULL" ;
                }
                if (isset($DO["s_region"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_region = '" . addslashes($DO["s_region"]) . "'" ;
                }
                if (isset($DO["fk_i_city_id"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_id"] !== "")     $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                    else $cond .= "fk_i_city_id = NULL" ;
                }
                if (isset($DO["s_city"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_city = '" . addslashes($DO["s_city"]) . "'" ;
                }
                if (isset($DO["fk_i_city_area_id"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_area_id"] !== "")     $cond .= "fk_i_city_area_id = " . addslashes($DO["fk_i_city_area_id"]) ;
                    else $cond .= "fk_i_city_area_id = NULL" ;
                }
                if (isset($DO["s_city_area"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "s_city_area = '" . addslashes($DO["s_city_area"]) . "'" ;
                }
                if (isset($DO["d_coord_lat"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["d_coord_lat"] !== "")     $cond .= "d_coord_lat = " . addslashes($DO["d_coord_lat"]) ;
                    else $cond .= "d_coord_lat = NULL" ;
                }
                if (isset($DO["d_coord_long"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["d_coord_long"] !== "")     $cond .= "d_coord_long = " . addslashes($DO["d_coord_long"]) ;
                    else $cond .= "d_coord_long = NULL" ;
                }
                if (isset($DO["i_permissions"]))
                {
                    if ($cond != "") $cond .= ", " ;
                       $cond .= "i_permissions = '" . addslashes($DO["i_permissions"]) . "'" ;
                }
                if (isset($DO["b_company"]))
                {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["b_company"] !== "")     $cond .= "b_company = " . addslashes($DO["b_company"]) ;
                    else $cond .= "b_company = NULL" ;
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

            $sql  = "INSERT INTO " . $this->get_table_name('t_user') . " (dt_reg_date,dt_mod_date,s_name,s_username,s_password,s_secret,s_email,s_website,s_phone_land,s_phone_mobile,b_enabled,s_pass_code,s_pass_date,s_pass_question,s_pass_answer,s_pass_ip,fk_c_country_code,s_country,s_address,s_zip,fk_i_region_id,s_region,fk_i_city_id,s_city,fk_i_city_area_id,s_city_area,d_coord_lat,d_coord_long,i_permissions,b_company) VALUES " ;
            $cond = "" ;$primera = true;

						foreach($mDO as $DO) {
							$cond = "";
							if(!$primera) $sql .= ",";
							$sql .= " ( ";

			
		                if (isset($DO["dt_reg_date"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["dt_reg_date"] !== "")    $cond .= "'" . addslashes($DO["dt_reg_date"]) . "'" ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["dt_mod_date"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["dt_mod_date"] !== "")    $cond .= "'" . addslashes($DO["dt_mod_date"]) . "'" ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_name"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_name"]) . "'" ; }
		                if (isset($DO["s_username"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_username"]) . "'" ; }
		                if (isset($DO["s_password"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_password"]) . "'" ; }
		                if (isset($DO["s_secret"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_secret"]) . "'" ; }
		                if (isset($DO["s_email"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_email"]) . "'" ; }
		                if (isset($DO["s_website"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_website"]) . "'" ; }
		                if (isset($DO["s_phone_land"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_phone_land"]) . "'" ; }
		                if (isset($DO["s_phone_mobile"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_phone_mobile"]) . "'" ; }
		                if (isset($DO["b_enabled"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["b_enabled"] !== "")     $cond .= "" . addslashes($DO["b_enabled"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_pass_code"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_pass_code"]) . "'" ; }
		                if (isset($DO["s_pass_date"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["s_pass_date"] !== "")    $cond .= "'" . addslashes($DO["s_pass_date"]) . "'" ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_pass_question"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_pass_question"]) . "'" ; }
		                if (isset($DO["s_pass_answer"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_pass_answer"]) . "'" ; }
		                if (isset($DO["s_pass_ip"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_pass_ip"]) . "'" ; }
		                if (isset($DO["fk_c_country_code"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["fk_c_country_code"]) . "'" ; }
		                if (isset($DO["s_country"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_country"]) . "'" ; }
		                if (isset($DO["s_address"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_address"]) . "'" ; }
		                if (isset($DO["s_zip"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_zip"]) . "'" ; }
		                if (isset($DO["fk_i_region_id"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["fk_i_region_id"] !== "")     $cond .= "" . addslashes($DO["fk_i_region_id"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_region"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_region"]) . "'" ; }
		                if (isset($DO["fk_i_city_id"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["fk_i_city_id"] !== "")     $cond .= "" . addslashes($DO["fk_i_city_id"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_city"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_city"]) . "'" ; }
		                if (isset($DO["fk_i_city_area_id"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["fk_i_city_area_id"] !== "")     $cond .= "" . addslashes($DO["fk_i_city_area_id"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["s_city_area"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["s_city_area"]) . "'" ; }
		                if (isset($DO["d_coord_lat"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["d_coord_lat"] !== "")     $cond .= "" . addslashes($DO["d_coord_lat"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["d_coord_long"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["d_coord_long"] !== "")     $cond .= "" . addslashes($DO["d_coord_long"]) ;
		                    else $cond .= " NULL" ; }
		                if (isset($DO["i_permissions"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                       $cond .= "'" . addslashes($DO["i_permissions"]) . "'" ; }
		                if (isset($DO["b_company"]))
		                {
		                    if ($cond != "") $cond .= ", " ;
		                    if ($DO["b_company"] !== "")     $cond .= "" . addslashes($DO["b_company"]) ;
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
                    $sql = "SELECT * FROM " . $this->get_table_name('t_user') . " WHERE (" . $where_select . ") AND NOT (pk_i_id = '" . addslashes($DO["pk_i_id"]) . "')";

                    //Recordset Object
                    $rs = new DBRecordsetClass($this->conn, $sql) ;
                    $rs->query() ;

                    //If it returns results we cannot do the update
                    if (!$rs->movenext()) $can_update = false ;
                }
            }

            if ($can_update)
            {
                $sql  = "UPDATE " . $this->get_table_name('t_user') . " SET " ;

                $cond = "" ;
                if (isset($DO["dt_reg_date"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["dt_reg_date"] != "")     $cond .= "dt_reg_date = '" . addslashes($DO["dt_reg_date"]) . "'" ;
                    else $cond .= "dt_reg_date = NULL" ;
                }
                
                if (isset($DO["dt_mod_date"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["dt_mod_date"] != "")     $cond .= "dt_mod_date = '" . addslashes($DO["dt_mod_date"]) . "'" ;
                    else $cond .= "dt_mod_date = NULL" ;
                }
                
                if (isset($DO["s_name"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_name = '" . addslashes($DO["s_name"]) . "'" ;
                }
                
                if (isset($DO["s_username"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_username = '" . addslashes($DO["s_username"]) . "'" ;
                }
                
                if (isset($DO["s_password"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_password = '" . addslashes($DO["s_password"]) . "'" ;
                }
                
                if (isset($DO["s_secret"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_secret = '" . addslashes($DO["s_secret"]) . "'" ;
                }
                
                if (isset($DO["s_email"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_email = '" . addslashes($DO["s_email"]) . "'" ;
                }
                
                if (isset($DO["s_website"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_website = '" . addslashes($DO["s_website"]) . "'" ;
                }
                
                if (isset($DO["s_phone_land"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_phone_land = '" . addslashes($DO["s_phone_land"]) . "'" ;
                }
                
                if (isset($DO["s_phone_mobile"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_phone_mobile = '" . addslashes($DO["s_phone_mobile"]) . "'" ;
                }
                
                if (isset($DO["b_enabled"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["b_enabled"] != "")     $cond .= "b_enabled = " . addslashes($DO["b_enabled"]) ;
                    else $cond .= "b_enabled = NULL" ;
                }
                
                if (isset($DO["s_pass_code"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_pass_code = '" . addslashes($DO["s_pass_code"]) . "'" ;
                }
                
                if (isset($DO["s_pass_date"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["s_pass_date"] != "")     $cond .= "s_pass_date = '" . addslashes($DO["s_pass_date"]) . "'" ;
                    else $cond .= "s_pass_date = NULL" ;
                }
                
                if (isset($DO["s_pass_question"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_pass_question = '" . addslashes($DO["s_pass_question"]) . "'" ;
                }
                
                if (isset($DO["s_pass_answer"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_pass_answer = '" . addslashes($DO["s_pass_answer"]) . "'" ;
                }
                
                if (isset($DO["s_pass_ip"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_pass_ip = '" . addslashes($DO["s_pass_ip"]) . "'" ;
                }
                
                if (isset($DO["fk_c_country_code"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "fk_c_country_code = '" . addslashes($DO["fk_c_country_code"]) . "'" ;
                }
                
                if (isset($DO["s_country"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_country = '" . addslashes($DO["s_country"]) . "'" ;
                }
                
                if (isset($DO["s_address"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_address = '" . addslashes($DO["s_address"]) . "'" ;
                }
                
                if (isset($DO["s_zip"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_zip = '" . addslashes($DO["s_zip"]) . "'" ;
                }
                
                if (isset($DO["fk_i_region_id"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_region_id"] != "")     $cond .= "fk_i_region_id = " . addslashes($DO["fk_i_region_id"]) ;
                    else $cond .= "fk_i_region_id = NULL" ;
                }
                
                if (isset($DO["s_region"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_region = '" . addslashes($DO["s_region"]) . "'" ;
                }
                
                if (isset($DO["fk_i_city_id"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_id"] != "")     $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                    else $cond .= "fk_i_city_id = NULL" ;
                }
                
                if (isset($DO["s_city"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_city = '" . addslashes($DO["s_city"]) . "'" ;
                }
                
                if (isset($DO["fk_i_city_area_id"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["fk_i_city_area_id"] != "")     $cond .= "fk_i_city_area_id = " . addslashes($DO["fk_i_city_area_id"]) ;
                    else $cond .= "fk_i_city_area_id = NULL" ;
                }
                
                if (isset($DO["s_city_area"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "s_city_area = '" . addslashes($DO["s_city_area"]) . "'" ;
                }
                
                if (isset($DO["d_coord_lat"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["d_coord_lat"] != "")     $cond .= "d_coord_lat = " . addslashes($DO["d_coord_lat"]) ;
                    else $cond .= "d_coord_lat = NULL" ;
                }
                
                if (isset($DO["d_coord_long"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["d_coord_long"] != "")     $cond .= "d_coord_long = " . addslashes($DO["d_coord_long"]) ;
                    else $cond .= "d_coord_long = NULL" ;
                }
                
                if (isset($DO["i_permissions"])) {
                    if ($cond != "") $cond .= ", " ;
                    $cond .= "i_permissions = '" . addslashes($DO["i_permissions"]) . "'" ;
                }
                
                if (isset($DO["b_company"])) {
                    if ($cond != "") $cond .= ", " ;
                    if ($DO["b_company"] != "")     $cond .= "b_company = " . addslashes($DO["b_company"]) ;
                    else $cond .= "b_company = NULL" ;
                }
                $sql .= $cond . " WHERE pk_i_id = '" . addslashes($DO["pk_i_id"]) . "'" ;
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
            $sql  = "UPDATE " . $this->get_table_name('t_user') . " SET " ;

            $cond = "" ;
            if (isset($DO["dt_reg_date"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["dt_reg_date"] != "") $cond .= "dt_reg_date = '" . addslashes($DO["dt_reg_date"]) . "'" ;
                else $cond .= "dt_reg_date = NULL" ;
            }
                
            if (isset($DO["dt_mod_date"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["dt_mod_date"] != "") $cond .= "dt_mod_date = '" . addslashes($DO["dt_mod_date"]) . "'" ;
                else $cond .= "dt_mod_date = NULL" ;
            }
                
            if (isset($DO["s_name"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_name = '" . addslashes($DO["s_name"]) . "'" ;
            }
                
            if (isset($DO["s_username"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_username = '" . addslashes($DO["s_username"]) . "'" ;
            }
                
            if (isset($DO["s_password"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_password = '" . addslashes($DO["s_password"]) . "'" ;
            }
                
            if (isset($DO["s_secret"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_secret = '" . addslashes($DO["s_secret"]) . "'" ;
            }
                
            if (isset($DO["s_email"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_email = '" . addslashes($DO["s_email"]) . "'" ;
            }
                
            if (isset($DO["s_website"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_website = '" . addslashes($DO["s_website"]) . "'" ;
            }
                
            if (isset($DO["s_phone_land"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_phone_land = '" . addslashes($DO["s_phone_land"]) . "'" ;
            }
                
            if (isset($DO["s_phone_mobile"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_phone_mobile = '" . addslashes($DO["s_phone_mobile"]) . "'" ;
            }
                
            if (isset($DO["b_enabled"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["b_enabled"] != "") $cond .= "b_enabled = " . addslashes($DO["b_enabled"]) ;
                else $cond .= "b_enabled = NULL" ;
            }
                
            if (isset($DO["s_pass_code"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_pass_code = '" . addslashes($DO["s_pass_code"]) . "'" ;
            }
                
            if (isset($DO["s_pass_date"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["s_pass_date"] != "") $cond .= "s_pass_date = '" . addslashes($DO["s_pass_date"]) . "'" ;
                else $cond .= "s_pass_date = NULL" ;
            }
                
            if (isset($DO["s_pass_question"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_pass_question = '" . addslashes($DO["s_pass_question"]) . "'" ;
            }
                
            if (isset($DO["s_pass_answer"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_pass_answer = '" . addslashes($DO["s_pass_answer"]) . "'" ;
            }
                
            if (isset($DO["s_pass_ip"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_pass_ip = '" . addslashes($DO["s_pass_ip"]) . "'" ;
            }
                
            if (isset($DO["fk_c_country_code"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "fk_c_country_code = '" . addslashes($DO["fk_c_country_code"]) . "'" ;
            }
                
            if (isset($DO["s_country"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_country = '" . addslashes($DO["s_country"]) . "'" ;
            }
                
            if (isset($DO["s_address"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_address = '" . addslashes($DO["s_address"]) . "'" ;
            }
                
            if (isset($DO["s_zip"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_zip = '" . addslashes($DO["s_zip"]) . "'" ;
            }
                
            if (isset($DO["fk_i_region_id"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["fk_i_region_id"] != "") $cond .= "fk_i_region_id = " . addslashes($DO["fk_i_region_id"]) ;
                else $cond .= "fk_i_region_id = NULL" ;
            }
                
            if (isset($DO["s_region"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_region = '" . addslashes($DO["s_region"]) . "'" ;
            }
                
            if (isset($DO["fk_i_city_id"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["fk_i_city_id"] != "") $cond .= "fk_i_city_id = " . addslashes($DO["fk_i_city_id"]) ;
                else $cond .= "fk_i_city_id = NULL" ;
            }
                
            if (isset($DO["s_city"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_city = '" . addslashes($DO["s_city"]) . "'" ;
            }
                
            if (isset($DO["fk_i_city_area_id"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["fk_i_city_area_id"] != "") $cond .= "fk_i_city_area_id = " . addslashes($DO["fk_i_city_area_id"]) ;
                else $cond .= "fk_i_city_area_id = NULL" ;
            }
                
            if (isset($DO["s_city_area"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "s_city_area = '" . addslashes($DO["s_city_area"]) . "'" ;
            }
                
            if (isset($DO["d_coord_lat"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["d_coord_lat"] != "") $cond .= "d_coord_lat = " . addslashes($DO["d_coord_lat"]) ;
                else $cond .= "d_coord_lat = NULL" ;
            }
                
            if (isset($DO["d_coord_long"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["d_coord_long"] != "") $cond .= "d_coord_long = " . addslashes($DO["d_coord_long"]) ;
                else $cond .= "d_coord_long = NULL" ;
            }
                
            if (isset($DO["i_permissions"])) {
                if ($cond != "") $cond .= ", " ;
                $cond .= "i_permissions = '" . addslashes($DO["i_permissions"]) . "'" ;
            }
                
            if (isset($DO["b_company"])) {
                if ($cond != "") $cond .= ", " ;
                if ($DO["b_company"] != "") $cond .= "b_company = " . addslashes($DO["b_company"]) ;
                else $cond .= "b_company = NULL" ;
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
            $sql .= "FROM " . $this->get_table_name('t_user') . " " ;
            if ($where != "") $sql .= " WHERE " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }
        
        //Delete Method using a PK
        function deleteBy_pk($pk_i_id, $where="")
        {
            $sql  = "DELETE " ;
            $sql .= "FROM " . $this->get_table_name('t_user') . " " ;
            $sql .= "WHERE pk_i_id = '" . addslashes($pk_i_id) . "'" ;
            if ($where != "") $sql .= " AND " . $where ;

            //SQL Execution
            $comm = new DBCommandClass($this->conn, $sql) ;
            return ($comm->execute()) ;
        }
    }
?>