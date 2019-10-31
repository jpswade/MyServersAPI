<?php
	require("myserversapi.inc.php");

	//
	// Process submitted form
	//
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$serverid = $_POST["serviceid"];

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->serviceId = $_POST["serviceid"];
		$params->number = $_POST["txtNumber"];
		$params->reason = $_POST["txtReason"];

		try {
			$result = $MyServersAPI->RequestIPs($params)->RequestIPsResult;
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}
		header("Location: iprequeststatus.php?serviceid=" . $_POST["serviceid"] . "&requestid=" . $result);
		exit();
	}

	if (!isset($_GET["serviceid"]))
		die("Missing parameter: serviceid");
	$serverid = $_GET["serviceid"];

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->serviceId = $serverid;

	$serverDetails = $MyServersAPI->GetServerDetails($params)->GetServerDetailsResult;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>IP Addresses: <?php echo $serverDetails->ServiceID ?></h1>

	<table border="1" cellspacing="0" cellpadding="1">
		<tr>
			<th>IP</th>
		</tr>
<?php	foreach (ArrayHelper($serverDetails->IPAddresses, "string") as $ip) {	?>
		<tr>
			<td><?php echo $ip; ?></td>
		</tr>
<?php	}	?>
	</table>

	<h2>Request IP Addresses</h2>

	<form name="frm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="serviceid" value="<?php echo $serverid ?>" />
		<table border="0" cellpadding="2">
			<tr>
				<td>Number to Request:</td>
				<td><input name="txtNumber" size="2" value="1" /></td>
			</tr>
			<tr valign="top">
				<td>Reason:</td>
				<td><textarea name="txtReason" rows="4" columns="50"></textarea></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="cmdSubmit" type="submit" value="Request IPs" />
			</tr>
		</table>
	</form>

</body>
</html>