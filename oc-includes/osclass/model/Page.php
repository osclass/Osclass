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

class Page extends DAO
{
    /**
     * The columns defined in page table.
     *
     * @access private
     * @var array 
     */
    private $columns;

    /**
     * The columns defined in page description table.
     *
     * @access private
     * @var array
     */
    private $columns_desc;

    private static $instance ;

    public function __construct() {
        parent::__construct();

        $this->columns      = array('pk_i_id', 's_internal_name', 'b_indelible', 'dt_pub_date, dt_mod_date');
        $this->columns_desc = array('fk_i_pages_id', 'fk_c_locale_code', 's_title', 's_text');
    }

    public static function newInstance() {
        if(!self::$instance instanceof self) {
            self::$instance = new self ;
        }
        return self::$instance ;
    }

    /**
     * Return's the name of the table.
     *
     * @return string table name.
     */
    public function getTableName()
    {
        return DB_TABLE_PREFIX . 't_pages';
    }

    /**
     * Return's the name of the description table.
     *
     * @return string description table name.
     */
    public function getDescriptionTableName()
    {
        return DB_TABLE_PREFIX . 't_pages_description';
    }

    /**
     * Find a page by page id.
     *
     * @param int $id Page id.
     * @param string $locale By default is null but you can specify locale code.
     * @return array Page information. If there's no information, return an empty array.
     */
    public function findByPrimaryKey($id, $locale = null)
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->getTableName();
        $sql .= ' WHERE ' . $this->getPrimaryKey() . ' = ' . $id;
        $row = $this->conn->osc_dbFetchResult($sql);

        if(is_null($row)) {
            return array();
        }

        $sql_desc = 'SELECT ' . implode(', ', $this->columns_desc) . ' FROM ';
        $sql_desc .= $this->getDescriptionTableName() . ' WHERE fk_i_pages_id = ' . $id;
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

    /**
     * Find a page by internal name.
     *
     * @param string $intName Internal name of the page to find.
     * @param string $locale Locale string.
     * @return array It returns page fields. If it has no results, it returns an empty array.
     */
    public function findByInternalName($intName, $locale = null)
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->getTableName();
        $sql .= ' WHERE s_internal_name = \'' . $intName . '\'';
        $row = $this->conn->osc_dbFetchResult($sql);

        if(count($row) == 0) {
            return array();
        }

        $result = $this->extendDescription($row, $locale);

        return $result;
    }

    /**
     * Get all the pages with the parameters you choose.
     *
     * @param bool $indelible It's true if the page is indelible and false if not.
     * @param string $locale It's
     * @param int $start 
     * @param int $limit
     * @return array Return all the pages that have been found with the criteria selected. If there's no pages, the
     * result is an empty array.
     */
    public function listAll($indelible = null, $locale = null, $start = null, $limit = null)
    {
        $sql = 'SELECT ' . implode(', ', $this->columns) . ' FROM ' . $this->getTableName();
        $aConditions = array();
        if(!is_null($indelible)) {
            $aConditions['b_indelible'] = ($indelible) ? '1' : '0';
        }

        if(count($aConditions) > 0) {
            $sql .= ' WHERE ';
            $aResultConditions = array();
            foreach($aConditions as $k => $v) {
                $aResultConditions[] = $k . ' = \'' . $v . '\'';
            }
            $sql .= implode(' AND ', $aResultConditions);
        }

        if(!is_null($limit)) {
            $sql .= ' LIMIT ';
            if(!is_null($start)) {
                $sql .= $start . ',';
            }
            $sql .= $limit;
        }
        
        $aPages = $this->conn->osc_dbFetchResults($sql);

        if(count($aPages) == 0) {
            return array();
        }

        $result = array();
        foreach($aPages as $aPage) {
            $data = $this->extendDescription($aPage, $locale);
            if(count($data) > 0) {
                $result[] = $data;
            }
            unset($data);
        }

        return $result;
    }

    /**
     * An array with data of some page, returns the title and description in every language available
     *
     * @param array $aPage
     * @return array Page information, title and description in every language available
     */
    public function extendDescription($aPage, $locale = null)
    {
        $sql = sprintf('SELECT * FROM %s ', $this->getDescriptionTableName());
        $sql .= sprintf('WHERE fk_i_pages_id = %d', $aPage['pk_i_id']);
        if(!is_null($locale)) {
            $sql .= ' AND fk_c_locale_code = \'' . $locale . '\'';
        }
        
        $descriptions = $this->conn->osc_dbFetchResults($sql);

        if(count($descriptions) == 0) {
            return array();
        }

        $aPage['locale'] = array();
        foreach($descriptions as $desc) {
            if( !empty($desc['s_title']) || !empty($desc['s_text']) ) {
                $aPage['locale'][$desc['fk_c_locale_code']] = $desc;
            }
        }

        return $aPage;
    }

    /**
     * Delete a page by id number.
     *
     * @param int $id Page id which is going to be deleted
     * @return bool True on successful removal, false on failure
     */
    public function deleteByPrimaryKey($id)
    {
        $this->conn->osc_dbExec('DELETE FROM %s WHERE fk_i_pages_id = %d', $this->getDescriptionTableName(), $id);
        $result = $this->delete(array('pk_i_id' => $id));
        if($result > 0) {
            return true;
        }
        return false;
    }

    /**
     * Delete a page by internal name.
     *
     * @param string $intName Page internal name which is going to be deleted
     * @return bool True on successful removal, false on failure
     */
    public function deleteByInternalName($intName)
    {
        $row = $this->conn->findByInternalName($intName);

        if(!isset($row)) {
            return false;
        }

        return $this->deleteByPrimaryKey($id);
    }

    /**
     * Insert a new page. You have to pass all the parameters
     *
     * @param array $aFields Fields to be inserted in pages table
     * @param array $aFieldsDescription An array with the titles and descriptions in every language.
     * @return boolean True if the insert has been done well and false if not.
     */
    public function insert($aFields, $aFieldsDescription = null)
    {
        $sql = 'INSERT INTO ' . $this->getTableName() . ' (s_internal_name, b_indelible, dt_pub_date, dt_mod_date)';
        $sql .= ' VALUES (\'' . $aFields['s_internal_name'] . '\', ' . '\'' . $aFields['b_indelible'] . '\'';
        $sql .= ', NOW(), NOW())';

        $this->conn->osc_dBExec($sql);

        $id = $this->conn->get_last_id();

        if($this->conn->get_affected_rows() == 0) {
            return false;
        }

        foreach($aFieldsDescription as $k => $v) {
            $affected_rows = $this->insertDescription($id, $k, $v['s_title'], $v['s_text']);
            if(!$affected_rows) {
                return false;
            }
        }

        return true;
    }

    /**
     * Insert the content (title and description) of a page.
     *
     * @param int $id Id of the page, it would be the foreign key
     * @param string $locale Locale code of the language
     * @param string $title Text to be inserted in s_title
     * @param string $text Text to be inserted in s_text
     * @return bool True if the insert has been done well and false if not.
     */
    private function insertDescription($id, $locale, $title, $text)
    {
        $title = addslashes($title);
        $text  = addslashes($text);

        $sql = 'INSERT INTO ' . $this->getDescriptionTableName() . ' (fk_i_pages_id, fk_c_locale_code, s_title, ';
        $sql .= 's_text) VALUES (' . sprintf('%d, \'%s\', \'%s\', \'%s\')', $id, $locale, $title, $text);

        $this->conn->osc_dBExec($sql);

        if($this->conn->get_affected_rows() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Update the content (title and description) of a page
     *
     * @param int $id Id of the page id is going to be modified
     * @param string $locale Locale code of the language
     * @param string $title Text to be updated in s_title
     * @param string $text Text to be updated in s_text
     * @return int Number of affected rows.
     */
    public function updateDescription($id, $locale, $title, $text)
    {
        $conditions = array('fk_c_locale_code' => $locale, 'fk_i_pages_id' => $id);
        $exist= $this->existDescription($conditions);

        if(!$exist) {
            $result = $this->insertDescription($id, $locale, $title, $text);
            return $result;
        }

        $sql = 'UPDATE ' . $this->getDescriptionTableName() . ' SET ';
        $sql .= ' s_title = \'' . addslashes($title) . '\', s_text = \'' . addslashes($text) . '\'';
        $sql .= ' WHERE fk_c_locale_code = \'' . $locale . '\' AND fk_i_pages_id = ' . $id;

        $this->conn->osc_dbExec($sql);

        $result = $this->conn->get_affected_rows();

        return $result;
    }

    /**
     * Check if depending the conditions, the row exists in de DB.
     *
     * @param array $conditions
     * @return bool Return true if exists and false if not.
     */
    public function existDescription($conditions) {
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

    /**
     * It change the internal name of a page. Here you don't check if in indelible or not the page.
     *
     * @param int $id The id of the page to be changed.
     * @param string $intName The new internal name.
     * @return int Number of affected rows.
     */
    public function updateInternalName($id, $intName)
    {
        $fields = array('s_internal_name' => $intName,
                         'dt_mod_date'    => DB_FUNC_NOW);
        $where  = array('pk_i_id' => $id);

        $result = $this->update($fields, $where);
        
        return $result;
    }
    
    /**
     * Check if a page id is indelible
     *
     * @param int $id Page id
     * @return true if it's indelible, false in case not
     */
    function isIndelible($id)
    {
        $page = $this->findByPrimaryKey($id);
        if($page['b_indelible'] == 1) {
            return true;
        }
        return false;
    }

    /**
     * Check if Internal Name exists with another id
     *
     * @param int $id page id
     * @param string $internalName page internal name
     * @return true if internal name exists, false if not
     */
    function internalNameExists($id, $internalName)
    {
        $result = $this->listWhere('s_internal_name = \'' . $internalName . '\' AND pk_i_id <> ' . $id );
        if(count($result) > 0) {
            return true;
        }
        return false;
    }
    
}

?>
