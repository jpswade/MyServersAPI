 <?php	require("myserversapi.inc.php");

 	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->serviceId = $_POST["serviceid"];
		if (isset($_POST["cmdEnableRecoveryMode"])) {
			$params->recoverymode = true;
		} else {
			$params->recoverymode = false;
		}
		$MyServersAPI->SetRecoveryMode($params);

 		header("Location: " . $_SERVER["PHP_SELF"] . "?requestid=" . $_POST["requestid"]);
 		exit();
 	}

 	if (!isset($_GET["requestid"]))
 		die("Missing parameter: requestid");

 	$params->authInfo->Username = $APIUsername;
 	$params->authInfo->Password = $APIPassword;
 	$params->requestid = $_GET["requestid"];

 	try {
 		$status = $MyServersAPI->GetSessionDetails($params)->GetSessionDetailsResult;
 	} catch (SoapFault $fault) {
 		die(var_dump($fault));
 	}
 ?>

 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 <html xmlns="http://www.w3.org/1999/xhtml">
 <head>
 	<title>RapidSwitch API Sample: Kvm Session Details</title>
 	<link rel="stylesheet" type="text/css" href="styles.css" />
 </head>
 <body>
 	<h1>Kvm Session Details</h1>

	<form name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
		<input type="hidden" name="serviceid" value="<?php echo $status->ServiceID; ?>" />
		<input type="hidden" name="requestid" value="<?php echo $status->RequestID; ?>" />
		<table border="0" cellpadding="2">
			<tr>
				<td>Service:</td>
				<td><a href="server.php?serviceid=<?php echo $status->ServiceID; ?>"><?php echo $status->ServiceID; ?></a></td>
			</tr>
			<tr>
				<td>Session Type:</td>
				<td><?php echo $status->RequestType; ?></td>
			</tr>
			<tr>
				<td>Active:</td>
				<td><?php echo $status->Active; ?></td>
			</tr>
			<tr>
				<td>Start Date:</td>
				<td><?php echo $status->StartDate; ?></td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td><?php echo $status->EndDate; ?></td>
			</tr>
			<tr>
				<td>Url:</td>
				<td><a href="<?php echo $status->Url; ?>"><?php echo $status->Url; ?></a></td>
			</tr>
			<tr>
				<td>Username:</td>
				<td><?php echo $status->Username; ?></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><?php echo $status->Password; ?></td>
			</tr>
	<?php	if ($status->RequestType == "Recovery") { 		?>
			<tr>
				<td>Recovery Mode:</td>
				<td>
	<?php		if ($status->RecoveryMode) {	?>
					Enabled <input type="submit" name="cmdDisableRecoveryMode" value="Disable" />
	<?php		} else { 		?>
					Disabled <input type="submit" name="cmdEnableRecoveryMode" value="Enable" />
	<?php		} 		?>
			</tr>
	<?php	} 		?>
		</table>
	</form>
 </body>
 </html>

