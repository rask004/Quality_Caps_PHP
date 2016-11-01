<?php
/**
 * Created by PhpStorm.
 * User: Roland
 * Date: 12/10/2016
 * Time: 5:38 PM
 *
 *	Session specific settings
 */
 
 include_once("Common.php");

session_start();

// if no cart is present, create one
if ( !(isset( $_SESSION[\Common\Security::$SessionCartArrayKey] ) && is_array($_SESSION[\Common\Security::$SessionCartArrayKey]) ) )
{
	$_SESSION[\Common\Security::$SessionCartArrayKey] = array();
}