<?php
/**
 * Created by PhpStorm.
 * User: Roland
 * Date: 11/10/2016
 * Time: 10:16 PM
 */

include_once('../../Includes/Common.php');
include_once('../../Includes/Session.php');

$customerId = "VISITOR";
if(isset($_SESSION[\Common\SecurityConstraints::$SessionUserIdKey]))
{
    $customerId = $_SESSION[\Common\SecurityConstraints::$SessionUserIdKey];
}

// If no error, redirect to home.
if( !(isset( $_SESSION["last_Error"]) && $_SESSION["last_Error"] == "DB_Error_Generic"))
{
	header("Location: http://dochyper.unitec.ac.nz/AskewR04/PHP_Assignment/Pages/home.php");
	exit;
}

\Common\Logging::Log('sessionId=' . session_id() . '; customer='
    . $customerId . '; Error Message=' . $_SESSION["Error_MSG"] . "\r\n");

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quality Caps - ERROR, Database Connection</title>
    <link rel="stylesheet" type="text/css" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../css/Common.css">
    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script type="text/javascript">
		// show error page for a while, then try to load home page
		function doCountdown()
		{
			var count = parseInt($("#lblCountdown").html());
			if (!count)
			{
				$("#lblCountdown").html("60");
			}
			else
			{
				count = count - 1;
				if (count <= 0)
				{
					window.location.replace("../home.php")
				}
				else
				{
					$("#lblCountdown").html(count);
				}
			}
			setTimeout(doCountdown, 1000);
		}
    	
	</script>
</head>

<body>
	<div class="container-fluid">
        <div class="row" style="margin: auto 20px">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <H3 style="color:red">
                    Database Error
                </H3>
            </div>
        </div>
        <br/>
        <br/>
        <div class="row" style="margin: auto 20px">
            <div class="col-xs-12 col-sm-12 col-md-12">
            	<?php
				
					if (!isset($_SESSION["Error_MSG"]))
					{
						$dbErrorMsg = $_SESSION["Error_MSG"];
					}
					else
					{
						$dbErrorMsg = "NO MESSAGE";
					}
					
					$receiverEmail = \Common\Constants::$EmailAdminDefault;
					$subject = "Quality Caps ERROR, Database query";
					$body = wordwrap("An Error was experienced during a database query.\r\nError Message : " . $dbErrorMsg . "\r\n\r\n", 70, "\r\n");
					$headers = "Content-Type: text/html; charset=TIS-620 \n";
					$headers .= "MIME-Version: 1.0 \r\n";
					
					mail($receiverEmail, $subject, $body, $headers);
					
				?>
                
                An error occurred processing a database request.
                
                Information on the request has been emailed to the Admin Team.
                
            </div>
        </div>
        
        <br/>
        <br/>
        <div class="row" style="margin: auto 20px">
            <div class="col-xs-12 col-sm-12 col-md-12">
            	You will be redirected to the home page in <label id="lblCountdown"></label> seconds.
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
		doCountdown();
	</script>
    
</body>
</html>