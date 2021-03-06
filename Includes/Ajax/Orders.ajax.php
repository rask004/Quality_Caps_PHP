<?php

/**
 * Created by Dreamweaver.
 * User: Roland
 * Date: 28/10/2016
 * Time: 8:00 PM
 *
 *  Ajax page for Orders.
 */

include_once('../Session.php');

// for timeout
$_SESSION[\Common\SecurityConstraints::$SessionTimestampLastVisit] = time();


include_once("../Common.php");
include_once('../OrderManager.php');

use \BusinessLayer\OrderManager;

$customerId = "VISITOR";
if(isset($_SESSION[\Common\SecurityConstraints::$SessionUserIdKey]))
{
	$customerId = $_SESSION[\Common\SecurityConstraints::$SessionUserIdKey];
}

\Common\Logging::Log('Executing Ajax. sessionId=' . session_id() . '; customer='
    . $customerId . "\r\n");

// Check for correct parameters. redirect to ajax error page if malformed.
if (!isset($_REQUEST["p"]))
{
	// redirect to AJAX error page.
	$_SESSION["last_Error"] = "AJAX_Error";
	$_SESSION["Error_MSG"] = "Orders ajax page: ";
	if (count($_REQUEST) == 0)
	{
		$_SESSION["Error_MSG"] .= "Empty Query String.";
	}
	else
	{
		foreach($_REQUEST as $key=>$value)
		{
			$_SESSION["Error_MSG"] .= $key . "=" . $value . "; ";		
		}
	}
	
	header("Location: http://dochyper.unitec.ac.nz/AskewR04/PHP_Assignment/Pages/Error/AJAX_Error.php");
	exit;
	
}
else 
{
	// required for loading orders and pagination.
	$ordersManager = new OrderManager();

	$page = (integer) ($_REQUEST["p"] + 0);
	
	$pageSize = \Common\Constants::$OrdersTablePageSize;
	
	if ($page < 1)
	{
		$page = 1;
	}
	
	$start = ($page - 1) * \Common\Constants::$OrdersTablePageSize;
	$id = $_SESSION[\Common\SecurityConstraints::$SessionUserIdKey];
	
	// table headers
	echo '<tr><th>Id</th><th>Date Placed</th><th>Status</th><th>Total Items</th><th>Total Cost ($)</th></tr>';
	
	// pagination: use database LIMIT command to retrieve subpage of data.
	$orderSummaries = $ordersManager->GetAllOrderSummariesForCustomer($id, $start, $pageSize);
	
	foreach($orderSummaries as $summary)
	{
		$totalPrice = (float) ($summary['totalPrice']) * \Common\Constants::$GstRate + (float) ($summary['totalPrice']);

		$date_parts = explode(" ", $summary['datePlaced']);
		echo "<tr><td>". $summary['id'] ."</td><td>". $date_parts[0] ."</td><td>". $summary['status'] .
		"</td><td>". $summary['totalQuantity'] ."</td><td>". number_format($totalPrice, 2, '.', '') ."</td><td></tr>";
	}
	
	// placeholders to retain page layout
	if( count($orderSummaries) < $pageSize)
	{
		$c = $pageSize - count($orderSummaries);
		
		while( $c > 0)
		{
			echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp; </td><td>&nbsp; </td><td>&nbsp; </td></tr>";
			$c -= 1;	
		}
	}
	
}

?>
