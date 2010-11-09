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
 * This is a helper class for Admin Panel Themes  
 * Before making any serious changes please contact me. 
 * oc-admin theme 'modern' is relying on this class.
 *
 * @author Max <max.podumal@gmail.com>
 */
class AdminThemes {
	
	private $theme;
	private $theme_path;
	private $absolute_path;

	public function __construct() {
		$this->theme = null;
		$this->theme_path = null;
		$this->absolute_path = null;
	}

	/* PRIVATE */
	private function setCurrentThemePath() {
		if(is_null($this->theme)) return false;
		$this->theme_path = WEB_PATH . '/oc-admin/themes/' . $this->theme;
	}
	
	private function setCurrentThemeAbsolutePath() {
		if(is_null($this->theme)) return false;
		$this->absolute_path = realpath(dirname(__FILE__)) . "/../../oc-admin/themes/" . $this->theme; //XXX: must take data from defined global var.
	}
	
	/* PUBLIC */
	public function setCurrentTheme($theme) {
		if(intval($theme) || floatval($theme)) return false; //XXX: not sure if this check is really needed.
		$this->theme = $theme;
		$this->setCurrentThemePath();
		$this->setCurrentThemeAbsolutePath();
	}
	
	public function getCurrentTheme() {
		return $this->theme;
	}
	
	public function getCurrentThemePath() {
		return $this->theme_path;
	}
	
	public function getCurrentThemeAbsolutePath() {
		return $this->absolute_path;
	}
	
	public function getCurrentThemeStyles() {
		return $this->theme_path . '/styles';
	}
}

