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

abstract class HTMLComponent {

	public abstract function toHTML();
}

/**
 * This class is intended to create HTML inputs of type "checkbox" dynamically.
 */
class HTMLInputCheckbox extends HTMLComponent {
	
	private $checked;
	private $name;
	private $id;
	private $value;
	private $label;

	public function __construct($name, $label) {
		$this->name = $this->id = $name;
		$this->label = $label;
		$this->value = '';
		$this->checked = false;
	}

	public function setChecked($checked) {
		$this->checked = $checked;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function toHTML() {
		echo sprintf('<input type="checkbox" name="%s" id="%s" value="%s" %s /> <label for="%s">%s</label>',
			$this->name, $this->id, $this->value, $this->checked ? 'checked="checked"' : '', $this->id, $this->label
		);
	}
}

/**
 * This class is intended to create HTML inputs of type "text" dynamically.
 */
class HTMLInputText extends HTMLComponent {

	private $name;
	private $id;
	private $value;

	public function __construct($name) {
		$this->name = $this->id = $name;
		$this->value = '';
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function toHTML() {
		echo sprintf('<input type="text" name="%s" id="%s" value="%s" />',
			$this->name, $this->id, $this->value
		);
	}
}

