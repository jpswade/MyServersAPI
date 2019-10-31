<?php
	require("myserversapi.inc.php");

	//
	// Process submitted form
	//
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$serverid = $_POST["serviceid"];

		// Common params
		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->serviceId = $_POST["serviceid"];

		// Which action was requested?
		if (isset($_POST["cmdSuspendServer"])) {
			$params->reason = "Suspended via Api";
			$MyServersAPI->SuspendServer($params);

		} else if (isset($_POST["cmdUnsuspendServer"])) {
			$MyServersAPI->UnSuspendServer($params);

		} else if (isset($_POST["cmdPowerCycle"])) {
			$result = $MyServersAPI->PowerCycleServer($params)->PowerCycleServerResult;

		} else if (isset($_POST["cmdRequestKvm"])) {
			$result = $MyServersAPI->RequestKvm($params)->RequestKvmResult;

		} else if (isset($_POST["cmdRequestRecovery"])) {
			$result = $MyServersAPI->RequestRecoverySession($params)->RequestRecoverySessionResult;

		}


		if (isset($result) && ($result->Status == "PENDING")) {
			header("Location: requeststatus.php?requestid=" . $result->RequestId);
			exit();
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

	try {
		$serverDetails = $MyServersAPI->GetServerDetails($params)->GetServerDetailsResult;
		$serverStatus = ArrayHelper($MyServersAPI->GetServerStatus($params)->GetServerStatusResult, "CurrentMonitorStatus");
	} catch (SoapFault $fault) {
		if ($fault->faultcode == "s:INVALIDSERVICEID")
			die("Invalid ServiceID");
		else
			die(var_dump($fault));
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Server Details: <?php echo $serverDetails->ServiceID ?></h1>

	<div class="page">

		<div class="right">

			<fieldset>
				<legend>Monitoring
					&nbsp;&nbsp;
					[<a href="tests.php?serviceid=<?php echo $serverid ?>">Tests</a>]
					[<a href="alerts.php?serviceid=<?php echo $serverid ?>">Alerts</a>]
				</legend>

				<table border="0" cellpadding="1">
<?php	foreach ($serverStatus as $entry) { ?>
					<tr>
						<td><?php echo $entry->StatusCode ?></td>
						<td><a href="monitorhistory.php?serviceid=<?php echo $serverid; ?>&testid=<?php echo $entry->TestId; ?>"><?php echo $entry->TestName ?></a></td>
					</tr>
<?php	} ?>
				</table>
			</fieldset>

			<fieldset>
				<legend>Bandwidth</legend>
				<a href="bandwidth.php?serviceid=<?php echo $serverDetails->ServiceID; ?>"><img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&gd=overview" /></a>
			</fieldset>

		</div>

		<div class="left">

			<fieldset>
				<legend>Server Description</legend>

				<table border="0" cellpadding="2">
					<tr>
						<td>Your Reference:</td>
						<td><?php echo $serverDetails->YourReference; ?></td>
					</tr>
					<tr>
						<td>Location:</td>
						<td><?php echo $serverDetails->Location; ?></td>
					</tr>
					<tr>
						<td>Primary IP:</td>
						<td><?php echo $serverDetails->PrimaryIP; ?>&nbsp;&nbsp;[<a href="ipaddresses.php?serviceid=<?php echo $serverid; ?>">Show All</a>]</td>
					</tr>
				</table>
			</fieldset>

			<fieldset>
				<legend>Service Description</legend>
				<?php echo str_replace("\n", "<br />", $serverDetails->ServiceDescription); ?>
			</fieldset>

		</div>

		<form name="frm" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<input type="hidden" name="serviceid" value="<?php echo $serverid ?>" />
<?php 		if (!$serverDetails->Suspended) {	?>
			<input type="submit" name="cmdSuspendServer" value="Suspend Server" />
<?php		} else { ?>
			<input type="submit" name="cmdUnsuspendServer" value="Unsuspend Server" />
<?php		} ?>
			<input type="submit" name="cmdPowerCycle" value="Power Cycle" />
			<input type="submit" name="cmdRequestKvm" value="Kvm Session" />
			<input type="submit" name="cmdRequestRecovery" value="Recovery Session" />
		</form>

	</div>

</body>
</html>