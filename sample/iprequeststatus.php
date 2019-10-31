<?php	require("myserversapi.inc.php");

	if (!isset($_GET["serviceid"]))
		die("Missing parameter: serviceid");
	if (!isset($_GET["requestid"]))
		die("Missing parameter: requestid");

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->serviceId = $_GET["serviceid"];
	$params->requestId = $_GET["requestid"];

	try {
		$status = $MyServersAPI->GetIPRequestStatus($params)->GetIPRequestStatusResult;
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Request Status</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<meta http-equiv="refresh" content="5" />
</head>
<body>
	<h1>IP Request Status</h1>

	<table border="0" cellpadding="2">
		<tr>
			<td>Status:</td>
			<td><?php echo $status->Status; ?></td>
		</tr>
<?php	if ($status->Status == "REJECTED") {	?>
		<tr>
			<td>Reason:</td>
			<td><?php echo $status->RejectReason; ?></td>
		</tr>
<?php	}	?>
	</table>
</body>
</html>

