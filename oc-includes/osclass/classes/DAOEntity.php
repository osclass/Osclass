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

/**
 * This class holds the results obtained from database. Not currently in use.
 *
 * @author OSClass
 */
class DAOEntity {

	private $values;

	public function __construct() {
		$this->values = array();
	}

	public function getValue($name) {
		if(isset($values[$name]))
			return $values[$name];
		return null;
	}

	public function getCastedValue($name, $type) {
		$value = $this->getValue($name);
		if($value)
			settype($value, $type);
		return $value;
	}

	public function getBoolean($name) {
		return $this->getCastedValue($name, 'boolean');
	}

	public function getString($name) {
		return $this->getCastedValue($name, 'string');
	}

	public function getInteger($name) {
		return $this->getCastedValue($name, 'integer');
	}
}

