<?php
// ================================================
// PHP image browser - iBrowser
// ================================================
// iBrowser - language class
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see readme.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 2005/04/27
// ================================================

class PLUG_Lang {
	// current language
	var $lang;
	// accessors
	function setLang( $value ) {
		$this -> lang = $value;
	}
	function getLang() {
		$this -> lang = $value;
	}

	// variable to hold current language block
	var $block;
	// accessors
	function setBlock( $value ) {
		$this -> block = $value;
	}
	function getBlock() {
		return $this -> block;
	}

	// charset for the current language
	var $charset;
	// accessors
	function getCharset() {
		return $this -> charset;
	}

	// text direction for the current language
	var $dir;
	// accessors
	function getDir() {
		return $this -> dir;
	}

	// language data
	var $lang_data;
	// default language data
	var $default_lang_data;

	// constructor
	function PLUG_Lang( $lang = '' ) {
		global $cfg;
		if ( $lang == '' ) {
			$this -> lang = $cfg['lang'];
		} else {
			$this -> lang = $lang;
		}
		$this -> loadData();
	}

	// load language data
	function loadData() {
		global $cfg;
		include( dirname(__FILE__) . '/' . $this -> lang.'.php' );
		$this -> charset = $lang_charset;
		$this -> dir = $lang_direction;
		$this -> lang_data = $lang_data;
		unset( $lang_data );
		include( dirname(__FILE__) . '/' . $cfg['lang'].'.php' );
		$this -> default_lang_data = $lang_data;
	}

	// return message
	function showMessage( $message, $block = '' ) {
		$_block = ( $block == '' ) ? $this -> block: $block;
		if ( !empty( $this -> lang_data[ $_block][ $message ] ) ) {
			// return message
			return $this -> lang_data[ $_block][ $message ];
		} else {
			// if message is not present in current language data
			// return message from default language
			return ( isset( $this -> default_lang_data[ $_block][ $message ] ) ? $this -> default_lang_data[ $_block][ $message ] : '' );
		}
	}

	// shortcut for showMessage
	function m( $message, $block = '' ) {
		return $this -> showMessage( $message, $block );
	}

	// sets the root point for the data
	function setRoot( $block = '' ) {
		// if no block passed -> reload data
		if ( $block == '' ) {
			$this -> loadData();
		} else {
			// "move pointer"
			$this -> lang_data = $this -> lang_data[ $block ];
			$this -> default_lang_data = $this -> default_lang_data[ $block ];
		}
	}
}
?>