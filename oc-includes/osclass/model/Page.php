<?php
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


class Page extends DAO {

	public static function newInstance() { return new Page(); }

	public function getTableName() { return DB_TABLE_PREFIX . 't_pages'; }


	public function extendDataSingle($item) {

		if(isset($_SESSION['locale'])) {
			$prefLocale = $_SESSION['locale'];
		} else {
			$prefLocale = Preference::newInstance()->findValueByName('language');
		}

		$descriptions = $this->conn->osc_dbFetchResults('SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %d', DB_TABLE_PREFIX, $item['pk_i_id']);
		$item['locale'] = array();
		foreach($descriptions as $desc) {
			if($desc['s_title']!="" || $desc['s_text']!="") {
				$item['locale'][$desc['fk_c_locale_code']] = $desc;
			}
		}
		if(isset($item['locale'][$prefLocale])) {
			$item['s_title'] = $item['locale'][$prefLocale]['s_title'];
			$item['s_text'] = $item['locale'][$prefLocale]['s_text'];
		} else {
			$data = current($item['locale']);
			$item['s_title'] = $data['s_title'];
			$item['s_text'] = $data['s_text'];
			unset($data);
		}
		return $item;
	}

	public function listAll() {
		return $this->conn->osc_dbFetchResults('SELECT * FROM %s as t, %st_pages_description as d WHERE d.fk_i_pages_id = t.pk_i_id ORDER BY t.pk_i_id, d.fk_c_locale_code', $this->getTableName(), DB_TABLE_PREFIX );
	}

	public function findByInternalName($intName) {
		return $this->extendDataSingle($this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id", $this->getTableName(), DB_TABLE_PREFIX, $intName ));
	}


	public function listIndelibles() {
		return $this->conn->osc_dbFetchResults("SELECT * FROM %s as t, %st_pages_description as d WHERE t.b_indelible = 1 AND d.fk_i_pages_id = t.pk_i_id", $this->getTableName(), DB_TABLE_PREFIX );
	}

	public function findByInternalNameLocale($intName, $locale) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $intName, $locale );
	}

	public function findByIDLocale($id, $locale) {
		return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.pk_i_id = %s AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $id, $locale );
	}

	public function findByInternalNameLocaleSecure($intName, $locale) {

		$data = $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $intName, $locale );
		if(count($data)>0) {
			return $data;
		} else {
			if(isset($_SESSION['locale'])) {
				$prefLocale = $_SESSION['locale'];
			} else {
				$prefLocale = Preference::newInstance()->findValueByName('language');
			}

			if($locale!=$prefLocale) {
				$data = $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $intName, $prefLocale );
				if(count($data)>0) {
					return $data;
				} else {
					return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id LIMIT 1", $this->getTableName(), DB_TABLE_PREFIX, $intName );
				}
			} else {
				return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.s_internal_name = '%s' AND d.fk_i_pages_id = t.pk_i_id LIMIT 1", $this->getTableName(), DB_TABLE_PREFIX, $intName );
			}

		}
	}

	public function findByIDLocaleSecure($id, $locale) {

		$data = $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.pk_i_id = %s AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $id, $locale );
		if(count($data)>0) {
			return $data;
		} else {
			if(isset($_SESSION['locale'])) {
				$prefLocale = $_SESSION['locale'];
			} else {
				$prefLocale = Preference::newInstance()->findValueByName('language');
			}

			if($locale!=$prefLocale) {
				$data = $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.pk_i_id = %s AND d.fk_i_pages_id = t.pk_i_id AND d.fk_c_locale_code = '%s'", $this->getTableName(), DB_TABLE_PREFIX, $id, $prefLocale );
				if(count($data)>0) {
					return $data;
				} else {
					return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.pk_i_id = %s AND d.fk_i_pages_id = t.pk_i_id LIMIT 1", $this->getTableName(), DB_TABLE_PREFIX, $id );
				}
			} else {
				return $this->conn->osc_dbFetchResult("SELECT * FROM %s as t, %st_pages_description as d WHERE t.pk_i_id = %s AND d.fk_i_pages_id = t.pk_i_id LIMIT 1", $this->getTableName(), DB_TABLE_PREFIX, $id );
			}

		}
	}


	public function listAllObject() {
		$rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s ORDER BY pk_i_id', $this->getTableName());

		$data = array();
		foreach($rows as $row) {
			$sub_rows = $this->conn->osc_dbFetchResults('SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s ORDER BY fk_c_locale_code', DB_TABLE_PREFIX, $row['pk_i_id']);
			$row['locale'] = array();
			foreach($sub_rows as $sub_row) {
				$row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
			}
			$data[] = $row;
		}
		return $data;
	}

	public function findByIDObject($id) {
		$rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s WHERE pk_i_id = %s ORDER BY pk_i_id', $this->getTableName(), $id);

		$data = array();
		foreach($rows as $row) {
			$sub_rows = $this->conn->osc_dbFetchResults('SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s ORDER BY fk_c_locale_code', DB_TABLE_PREFIX, $row['pk_i_id']);
			$row['locale'] = array();
			foreach($sub_rows as $sub_row) {
				$row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
			}
			$data = $row;
		}
		return $data;
	}

	public function findByInternalNameObject($intName) {
		$rows = $this->conn->osc_dbFetchResults("SELECT * FROM %s WHERE s_internal_name = '%s' ORDER BY pk_i_id", $this->getTableName(), $intName);

		$data = array();
		foreach($rows as $row) {
			$sub_rows = $this->conn->osc_dbFetchResults('SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s ORDER BY fk_c_locale_code', DB_TABLE_PREFIX, $row['pk_i_id']);
			$row['locale'] = array();
			foreach($sub_rows as $sub_row) {
				$row['locale'][$sub_row['fk_c_locale_code']] = $sub_row;
			}
			$data = $row;
		}
		return $data;
	}


	public function listAllLocaleObject($locale = null) {
		if($locale==null) {
			if(isset($_SESSION['locale'])) {
				$locale = $_SESSION['locale'];
			} else {
				$locale = Preference::newInstance()->findValueByName('language');
			}
		}

		$rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s ORDER BY pk_i_id', $this->getTableName());

		$data = array();
		foreach($rows as $row) {
			$sub_rows = $this->conn->osc_dbFetchResults("SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $row['pk_i_id'], $locale);
			foreach($sub_rows as $sub_row) {
				$row['s_title'] = $sub_row['s_title'];
				$row['s_text'] = $sub_row['s_text'];
			}
			$data[] = $row;
		}
		return $data;
	}

	public function listAllLocaleSecureObject($locale = null) {
		if($locale==null) {
			if(isset($_SESSION['locale'])) {
				$locale = $_SESSION['locale'];
			} else {
				$locale = Preference::newInstance()->findValueByName('language');
			}
		}


		$rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s ORDER BY pk_i_id', $this->getTableName());

		$data = array();
		foreach($rows as $row) {
			$sub_row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $row['pk_i_id'], $locale);
			if(count($sub_row)>0) {
				$row['s_title'] = $sub_row['s_title'];
				$row['s_text'] = $sub_row['s_text'];
				$data[] = $row;
			} else {
				$sub_row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s LIMIT 1", DB_TABLE_PREFIX, $row['pk_i_id']);
				if(count($sub_row)>0) {
					$row['s_title'] = $sub_row['s_title'];
					$row['s_text'] = $sub_row['s_text'];
					$data[] = $row;
				}
			}
		}
		return $data;
	}


	public function listNotIndeliblesLocaleSecureObject($locale = null) {
		if($locale==null) {
			if(isset($_SESSION['locale'])) {
				$locale = $_SESSION['locale'];
			} else {
				$locale = Preference::newInstance()->findValueByName('language');
			}
		}

		$rows = $this->conn->osc_dbFetchResults('SELECT * FROM %s WHERE b_indelible = 0 ORDER BY pk_i_id', $this->getTableName());

		$data = array();
		foreach($rows as $row) {
			$sub_row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s AND fk_c_locale_code = '%s'", DB_TABLE_PREFIX, $row['pk_i_id'], $locale);
			if(count($sub_row)>0) {
				$row['s_title'] = $sub_row['s_title'];
				$row['s_text'] = $sub_row['s_text'];
				$data[] = $row;
			} else {
				$sub_row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages_description WHERE fk_i_pages_id = %s LIMIT 1", DB_TABLE_PREFIX, $row['pk_i_id']);
				if(count($sub_row)>0) {
					$row['s_title'] = $sub_row['s_title'];
					$row['s_text'] = $sub_row['s_text'];
					$data[] = $row;
				}
			}
		}
		return $data;
	}



	public function listNotIndelibles() {
		if(isset($_SESSION['locale'])) {
			$locale = $_SESSION['locale'];
		} else {
			$locale = Preference::newInstance()->findValueByName('language');
		}
		return $this->listNotIndeliblesLocaleSecureObject($locale);
	}

	public function findByPrimaryKey($id) {
		/*if(isset($_SESSION['locale'])) {
			$locale = $_SESSION['locale'];
		} else {
			$locale = Preference::newInstance()->findValueByName('language');
		}*/
		return $this->findByIDObject($id);
	}





	public function deleteByID($id) {
		$row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages WHERE pk_i_id = %s", DB_TABLE_PREFIX, $id);
		if($row['b_indelible']==0) {
			$this->conn->osc_dbExec('DELETE FROM %st_pages_description WHERE fk_i_pages_id = %s', DB_TABLE_PREFIX, $id);
			return $this->delete(array('pk_i_id' => $id));
		} else {
			return false;
		}
	}

	public function deleteByInternalName($intName) {
		$row = $this->conn->osc_dbFetchResult("SELECT * FROM %st_pages WHERE s_internal_name = '%s'", DB_TABLE_PREFIX, $intName);
		if($row['b_indelible']==0) {
			$this->conn->osc_dbExec('DELETE FROM %st_pages_description WHERE fk_i_pages_id = %s', DB_TABLE_PREFIX, $row['pk_i_id']);
			return $this->delete(array('s_internal_name' => $intName));
		} else {
			return false;
		}
	}


	public function updateLocale($id, $locale, $title, $text) {
		$sql = sprintf("UPDATE %st_pages_description SET `s_title` = '%s', `s_text` = '%s' WHERE `fk_c_locale_code` = '%s' AND `fk_i_pages_id` = %s", DB_TABLE_PREFIX, $title, $text, $locale, $id);
		$this->conn->osc_dbExec($sql);
		$date = date('Y-m-d H:i:s');
		$sql = sprintf("UPDATE %st_pages SET `dt_mod_date` = '%s' WHERE `pk_i_id` = %s", DB_TABLE_PREFIX, $date, $id);
		return $this->conn->osc_dbExec($sql);
	}

	public function updateLocaleForce($id, $locale, $title, $text) {
		$sql = sprintf("REPLACE INTO %st_pages_description SET `s_title` = '%s', `s_text` = '%s', `fk_c_locale_code` = '%s', `fk_i_pages_id` = %s", DB_TABLE_PREFIX, $title, $text, $locale, $id);
		$this->conn->osc_dbExec($sql);
		$date = date('Y-m-d H:i:s');
		$sql = sprintf("UPDATE %st_pages SET `dt_mod_date` = '%s' WHERE `pk_i_id` = %s", DB_TABLE_PREFIX, $date, $id);
		return $this->conn->osc_dbExec($sql);
	}

	public function insert($intName, $aFieldsDescription = null) {
		$date = date('Y-m-d H:i:s');
		$sql = sprintf("SELECT s_internal_name FROM %st_pages WHERE `s_internal_name` = '%s'", DB_TABLE_PREFIX, $intName);
		$result = $this->conn->osc_dbFetchResult($sql);
        //if(!isset($result['s_internal_name'])) {
    		$sql = sprintf("INSERT INTO %st_pages (`s_internal_name` ,`b_indelible` ,`dt_pub_date` ,`dt_mod_date`) VALUES ('%s', '0', '%s', '%s')", DB_TABLE_PREFIX, $intName, $date, $date);
	    	$this->conn->osc_dbExec($sql);
		    $sql = sprintf("SELECT pk_i_id FROM %st_pages ORDER BY pk_i_id DESC LIMIT 1", DB_TABLE_PREFIX);
		    return $this->conn->osc_dbFetchResult($sql);
        /*} else {
            return array();
        }*/
	}

	public function insertDescriptionDraft($id, $locale, $title, $text) {
		$sql = sprintf("REPLACE INTO %st_pages_draft_description SET `s_title` = '%s', `s_text` = '%s', `fk_c_locale_code` = '%s', `fk_i_pages_id` = %s", DB_TABLE_PREFIX, $title, $text, $locale, $id);
		$this->conn->osc_dbExec($sql);
		$date = date('Y-m-d H:i:s');
		$sql = sprintf("UPDATE %st_pages_draft SET `dt_mod_date` = '%s' WHERE `pk_i_id` = %s", DB_TABLE_PREFIX, $date, $id);
		return $this->conn->osc_dbExec($sql);
	}

	public function insertDraft($id, $intName) {
		$date = date('Y-m-d H:i:s');
        $sql = sprintf("REPLACE INTO %st_pages_draft (`pk_i_id`,`s_internal_name` ,`b_indelible` ,`dt_pub_date` ,`dt_mod_date`) VALUES (%d, '%s', '0', '%s', '%s')", DB_TABLE_PREFIX, $id, $intName, $date, $date);
        return $this->conn->osc_dbExec($sql);
	}

	public function deleteDraft($id) {
        $this->conn->osc_dbExec('DELETE FROM %st_pages_draft WHERE pk_i_id = %s', DB_TABLE_PREFIX, $id);
        return $this->conn->osc_dbExec('DELETE FROM %st_pages_draft_description WHERE fk_i_pages_id = %s', DB_TABLE_PREFIX, $id);
	}

	public function updateInternalName($id, $intName) {
        $sql = sprintf("REPLACE INTO %st_pages (`pk_i_id`,`s_internal_name`) VALUES (%d, '%s')", DB_TABLE_PREFIX, $id, $intName);
        return $this->conn->osc_dbExec($sql);
	}
}

