<?php
/*
 * Copyright 2007-2011 Charles du Jeu <contact (at) cdujeu.me>
 * This file is part of AjaXplorer.
 *
 * AjaXplorer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * AjaXplorer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with AjaXplorer.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://www.ajaxplorer.info/>.
 */
defined('AJXP_EXEC') or die( 'Access not allowed');

/**
 * Bridge with the phpBB users system.
 */
class suAuthDriver extends serialAuthDriver  {
	
	var $driverName = "su";
	
	function isEnabled()
	{
		return true;
	}
	
	function init($options){
		parent::init($options);
		
		if(!DB_user::getSessionUser())
			$this->disconnect();
	}
	
	function disconnect()
	{
		if(!empty($_SESSION["AJXP_USER"])){
			unset($_SESSION["AJXP_USER"]);
			session_destroy();
		}
	}
	
	function isAjxpAdmin($login){ return true; }
	
	function usersEditable() { return false; }
	
	function passwordsEditable() { return false; }
	
	function checkPassword($login, $pass, $seed){
		return (DB_user::getSessionUser());
	}
	
	function preLogUser($sessionId) {
		$user = DB_user::getSessionUser();
		
		if(!$user)
			return false;
		
		$username = $user->getName();
		$password = md5($user->getPassword());
		
		if(!$this->userExists($username)){
			$this->createUser($username, $password);
		}
		
		AuthService::logUser($username, "", true);
		return true;
	}

	function getLoginRedirect(){
		return 'login.php';
	}

	function getLogoutRedirect(){
		return 'logout.php';
	}

}
?>