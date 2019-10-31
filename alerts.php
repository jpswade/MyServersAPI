<?php
	require("myserversapi.inc.php");

	//
	// Process submitted form
	//
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$serverid = $_POST["serviceid"];

		if (isset($_POST["cmdSubmit"])) {
			$params->authInfo->Username = $APIUsername;
			$params->authInfo->Password = $APIPassword;
			$params->serviceId = $_POST["serviceid"];
			$params->alertType = $_POST["alertType"];
			$params->dest = $_POST["txtDest"];
			$params->initDelay = $_POST["txtInitDelay"];
			$params->repeatDelay = $_POST["txtRepeatDelay"];
			$params->alertOnWarn = isset($_POST["chkAlertOnWarn"]);
			$params->alertOnFail = isset($_POST["chkAlertOnFail"]);

			try {
				$MyServersAPI->AddAlert($params);
			} catch (SoapFault $fault) {
				$error = $fault;
				var_dump($error);
				die();
			}
		}

		// Redirect back here
		header("Location: " . $_SERVER['PHP_SELF'] . "?serviceid=" . $_POST["serviceid"]);
		exit();
	}

	if (!isset($_GET["serviceid"]))
		die("Missing parameter: serviceid");
	$serverid = $_GET["serviceid"];

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->serviceId = $serverid;

	$alerts = ArrayHelper($MyServersAPI->GetAlerts($params)->GetAlertsResult, "AlertInfo");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Alerts: <?php echo $serverid ?></h1>

	<table border="1" cellpadding="2" cellspacing="0">
		<thead>
			<tr>
				<th>Alert Type</th>
				<th>Destination</th>
				<th>Init Delay</th>
				<th>Repeat Delay</th>
				<th>Alert On</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach ($alerts as $alert) {	?>
			<tr>
				<td><?php echo $alert->AlertType; ?></td>
				<td><?php echo $alert->Destination; ?></td>
				<td><?php echo $alert->InitialDelay; ?></td>
				<td><?php echo $alert->RepeatDelay; ?></td>
				<td><?php echo ($alert->AlertOnWarning ? "Warn " : "") . ($alert->AlertOnFailure ? "Fail " : ""); ?></td>
				<td><a href="deletealert.php?serviceid=<?php echo $serverid; ?>&alertid=<?php echo $alert->AlertId; ?>">Del</a></td>
			</tr>
<?php	}	?>
		</tbody>
	</table>

	<h2>Add Alert</h2>

	<form name="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input name="serviceid" type="hidden" value="<?php echo $serverid; ?>" />
		<table border="0" cellpadding="2">
			<tr>
				<td>Alert Type:</td>
				<td><select name="alertType">
					<option>Email</option>
					<option>SMS</option>
				</select></td>
			</tr>
			<tr>
				<td>Destination:</td>
				<td><input name="txtDest" type="text" /></td>
			</tr>
			<tr>
				<td>Initial Delay</td>
				<td><input name="txtInitDelay" type="text" value="15" size="2" />
			</tr>
			<tr>
				<td>Repeat Delay</td>
				<td><input name="txtRepeatDelay" type="text" value="120" size="2" />
			</tr>
			<tr>
				<td>Alert On Warning</td>
				<td><input name="chkAlertOnWarn" type="checkbox" />
			</tr>
			<tr>
				<td>Alert On Failure</td>
				<td><input name="chkAlertOnFail" type="checkbox" checked="1" />
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="cmdSubmit" type="submit" value="Add Alert" />
			</tr>
		</table>
	</form>
</body>
</html>