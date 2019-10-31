<?php	require("myserversapi.inc.php");

	if (!isset($_GET["requestid"]))
		die("Missing parameter: requestid");

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->requestid = $_GET["requestid"];

	try {
		$status = $MyServersAPI->GetRequestStatusUpdate($params)->GetRequestStatusUpdateResult;
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}

	// If this request was for a Kvm/Recovery session which has now completed, redirect to the page showing the login details
	if (($status->Status == "COMPLETED") && (($status->RequestType == "Recovery") || ($status->RequestType == "KVM"))) {
		header("Location: kvmsession.php?requestid=" . $_GET["requestid"]);
		exit();
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
	<h1>Request Status</h1>

	<table border="0" cellpadding="2">
		<tr>
			<td>Service:</td>
			<td><a href="server.php?serviceid=<?php echo $status->ServiceID; ?>"><?php echo $status->ServiceID; ?></a></td>
		</tr>
		<tr>
			<td>Request Type:</td>
			<td><?php echo $status->RequestType; ?></td>
		</tr>
		<tr>
			<td>Status:</td>
			<td><?php echo $status->Status; ?></td>
		</tr>
	</table>
</body>
</html>

