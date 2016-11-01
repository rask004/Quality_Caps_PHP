<?php
/**
 * Created by PhpStorm.
 * User: Roland
 * Date: 15/10/2016
 * Time: 14:24 PM
 */

namespace BusinessLayer;


require_once('DataLayer.php');
require_once('Common.php');


// Admin business object.

class AdminManager
{
	private $_data_manager;	
	
	function __construct()
	{
		$this->_data_manager = new \DataLayer\DataManager;
	}
	
	/*
		check that a supplied login matches an actual admin
	*/
	function findMatchingLogin($login)
	{
		if ($this->_data_manager->matchAdminByLogin($login));
		{
			return true;
		}
		
		return false;
	}
	
	/*
		retrieve a admin using their id.
		can return an empty array if admin does not exist.
	*/
	function findAdmin($id)
	{
		return $this->_data_manager->selectSingleAdmin($id);
	}
	
	/*
		retrieve a admin using their login.
		can return an empty array if admin does not exist.
	*/
	function findAdminByLogin($login)
	{
		return $this->_data_manager->selectSingleAdminByLogin($login);
	}
	
	/*
		check that a supplied login and password matches an actual admin
	*/
	function checkMatchingPasswordForAdminLogin($login, $password)
	{
		// there is no match if there is no admin.
		if (!$this->findMatchingLogin($login))
		{
			return false;
		}
		
		$data = $this->_data_manager->requestAdminPasswordSaltAndHash($login);
		$salt = $data['passwordsalt'];
		$expected_hash = $data['passwordhash'];
		
		$comparison_hash = \Common\Security::generatePasswordHash($password, $salt);
		
		if ($comparison_hash === $expected_hash)
		{
			return true;	
		}
		
		return false;
	}	
}