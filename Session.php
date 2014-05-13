<?php 

namespace Usec;

class Session {

    public function init($sessionPath){
        session_set_cookie_params(0, $sessionPath);
        session_start();
    }

    public function getIP(){
        if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
            return $_SERVER["HTTP_X_FORWARDED_FOR"]; // On récupère la vrai IP
        else
            return $_SERVER["REMOTE_ADDR"]; // On récupère l'IP normal
    }
 
    public function read($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }
 
    public function write($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function del($key) {
        $_SESSION[$key] = null;
    }
 
    public function login($login,$role=null,$groups=null) {

        $_SESSION['authentication_ip'] = self::getIP();
        $_SESSION['login'] = $login;
        if(isset($role))
            $_SESSION['role'] = $role;
        if(isset($groups))
            $_SESSION['groups'] = $groups;
    }

    public function parse(){
        return array(
            'login' => self::read('login'),
            'role' => self::read('role'),
            'groups' => self::read('groups')
        );
    }
 
    public function logout() {
        $_SESSION = array();
        session_unset();
        session_destroy();
    }

    public function flush(){
        $_SESSION = array();
    }
 
    public function isLogged() {
        return isset($_SESSION['login']) && $_SESSION['authentication_ip'] == self::getIP();
    }

    public function setNotif($msg = "", $type = "success"){
		if ($msg != "") {
			$_SESSION['message'] = array('notifs' => array('msg' => $msg, 'type' => $type));
		}
	}
    public function emptyNotif(){
        $_SESSION['message'] = array();
    }
}
