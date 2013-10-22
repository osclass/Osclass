<?php
/*
 * This is a real-time PHP client for the Talker group chat application (talkerapp.com).
 * 
 * Uses the standalone NetSocket class, which is taken and modified from PEAR's
 * Net_Socket package.
 *
 * @package Talker.php
 * @author Joseph Szobody <jszobody@gmail.com>
 */
require_once("NetSocket.php");

class Talker {
	private $socket;
	private $listener;

	private $host = "talkerapp.com";
	private $port = 8500;
	private $timeout = 10;
	
	private $connected = false;
	public $user;
	public $users = array();
	
	function __construct() {
		$this->socket = new NetSocket();
	}
	
	public function connect($room = "Main", $token) {
		if(!is_object($this)) {
			$talker = new Talker();
			return $talker->connect($room, $token);
		}
		
		// Connect to server
		try {
			$this->socket->connect($this->host, $this->port, false, $this->timeout);
		} catch(Exception $e) {
			Throw new Exception("Unable to connect");
		}
		
		$this->socket->enableCrypto(true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
		$this->socket->setBlocking(false);
		
		// Authenticate
		$auth = array("type" => "connect", "room" => $room, "token" => $token);
		try {
			$this->send($auth);
		} catch(Exception $e) {
			throw new Exception("Unable to send authentication request");
		}
		
		$result = json_decode($this->socket->readLine(), true);
		
		if($result['type'] == 'error') {
			// Oh yay. Hope someone is catching these exceptions.
			throw new Exception("Error authenticating: " . $result['message']);
		} else if($result['type'] == 'connected') {
			$this->connected = true;
			$this->user = $result['user'];
			
			// Awesome, we're in. Now get the list of users.
			$result = json_decode($this->socket->readLine(), true);
			if($result['type'] == 'users') {
				foreach($result['users'] AS $user) {
					$this->users[$user['name']] = $user['id'];
				}
			}
		} else {
			throw new Exception("Unexpected response from server: " . print_r($result,true));
		}
	}
	
	public function send_message($message) {
		if(!$this->connected) throw new Exception("Not connected");
		
		$message = array("type" => "message", "content" => $message);
		try {
			$result = $this->send($message);
		} catch(Exception $e) {
			throw new Exception("Unable to send message: " . $e->getMessage());
		}
		return true;
	}
	
	public function send_private_message($user_name, $message) {
		if(!$this->connected) throw new Exception("Not connected");
		if(!$this->users[$user_name]) throw new Exception("Invalid user");
		
		$message = array("type" => "message", "content" => $message, "to" => $this->users[$user_name]['id']);
		try {
			$result = $this->send($message);
		} catch(Exception $e) { 
			throw new Exception("Unable to send message: " . $e->getMessage());
		}
		return true;
	}
	
	public function close() {
		$this->listening = false;
		$this->connected = false;
		$this->socket->disconnect();
	}
	
	public function leave() {
		$this->listening = false;
		$this->send(array("type" => "close"));
		$this->close();
	}
	
	public function user() {
		return $this->user;
	}
	
	public function users() {
		return $this->users;
	}

	public function listen($listener) {
		if(!($listener instanceof BaseListener)) throw new Exception("Invalid listener");
		$listener->setTalker($this);
		
		$this->listener = $listener;
		$this->listening = true;
		$this->socket->setTimeout($this->timeout, 0);
		
		while($this->connected && $this->listening) {
			$this->socket->setBlocking(false);
			$result = json_decode($this->socket->readLine(), true);
			
			if($this->socket->eof()) {
				$this->connected = false;
				return false;
			}
			
			if(is_array($result) && $this->listening) $this->incoming($result);
			
			$this->ping();
		}
	}
	
	private function ping() {
		if(!$this->connected) return false;
		$this->send(array("type" => "ping"));
	}
	
	
	private function send($data) {
		return $this->socket->writeLine(json_encode($data));
	}
	
	private function incoming($result) {
		$this->listener->on_event($result);
		switch($result['type']) {
			case 'message':
				if($result['private']) $this->listener->on_private_message($result['user'],$result['content']);
				else $this->listener->on_message($result['user'],$result['content']);
				break;
			case 'users':
				if(is_array($results['users'])) {
					foreach($results['users'] AS $user) {
						$this->users[$user['name']] = $user['id'];
					}
				}
				@$this->listener->on_presence($result['users']);
				break;
			case 'join':
				@$this->listener->on_join($result['user']);
				break;
			case 'idle':
				@$this->listener->on_idle($result['user']);
				break;
			case 'back':
				@$this->listener->on_back($result['back']);
				break;
			case 'leave':
				@$this->listener->on_leave($result['user']);
				break;
		
			return true;
		}
	}
}
?>