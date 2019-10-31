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

			$params->testType = $_POST["testType"];
			$params->ip = $_POST["txtIp"];
			$params->arg1 = $_POST["txtArg1"];

			try {
				$MyServersAPI->AddTest($params);
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

	$status = ArrayHelper($MyServersAPI->GetServerStatus($params)->GetServerStatusResult, "CurrentMonitorStatus");
	$tests = ArrayHelper($MyServersAPI->GetTestTypes($params)->GetTestTypesResult, "string");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Tests: <?php echo $serverid ?></h1>

	<table border="1" cellpadding="2" cellspacing="0">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th>Service</th>
				<th>Args</th>
				<th>IP Address</th>
				<th>Last Updated</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach ($status as $test) {	?>
			<tr>
				<td><?php echo $test->StatusCode; ?></td>
				<td><?php echo $test->TestName; ?></td>
				<td><?php echo $test->TestArg1; ?></td>
				<td><?php echo $test->MonitoredIp; ?></td>
				<td><?php echo $test->LastUpdated; ?></td>
				<td><a href="deletetest.php?serviceid=<?php echo $serverid; ?>&testid=<?php echo $test->TestId; ?>">Del</a></td>
			</tr>
<?php	}	?>
		</tbody>
	</table>

	<h2>Add Test</h2>

	<form name="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
		<input name="serviceid" type="hidden" value="<?php echo $serverid; ?>" />
		<table border="0" cellpadding="2">
			<tr>
				<td>Test Type:</td>
				<td><select name="testType">
<?php	foreach ($tests as $test) {	?>
	<option><?php echo $test ?></option>
<?php 	} ?>
				</select></td>
			</tr>
			<tr>
				<td>Ip:</td>
				<td><input name="txtIp" type="text" /></td>
			</tr>
			<tr>
				<td>Arg:</td>
				<td><input name="txtArg1" type="text" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input name="cmdSubmit" type="submit" value="Add Test" />
			</tr>
		</table>
	</form>
</body>
</html>