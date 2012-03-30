<?php
/*
 * This is a base listener class for the PHP Talker client. Create your own listener class
 * that extends BaseListener, and overwrite any of the event methods that you want to handle.
 *  *
 * @package Talker.php
 * @author Joseph Szobody <jszobody@gmail.com>
 */
class BaseListener {
	protected $talker;

	public function setTalker($talker) {
		$this->talker = $talker;
	}
	
	public function on_connected($user) {}
	
	public function on_presence($users) {}
	
	public function on_message($user, $message) {}
	
	public function on_private_message($user, $message) {}
	
	public function on_join($user) {}
	
	public function on_idle($user) {}
	
	public function on_back($user) {}
	
	public function on_leave($user) {}
	
	public function on_close($user) {}
	
	public function on_event($event) {}
}
?>