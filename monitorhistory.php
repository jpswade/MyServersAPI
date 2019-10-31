<?php	require("myserversapi.inc.php");

	if (!isset($_GET["serviceid"]))
		die("Missing parameter: serviceid");
	$serverid = $_GET["serviceid"];
	if (!isset($_GET["testid"]))
		die("Missing parameter: testid");
	$testid = $_GET["testid"];

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->serviceId = $serverid;
	$params->testId = $testid;

	$history = ArrayHelper($MyServersAPI->GetTestHistory($params)->GetTestHistoryResult, "TestResult");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Reverse DNS</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Test History</h1>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Date</th>
				<th>Status</th>
				<th>Detail</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($history as $entry) {
?>
			<tr>
				<td><?php echo $entry->Date; ?></a></td>
				<td><?php echo $entry->StatusCode; ?></td>
				<td><?php echo $entry->StatusDetail; ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>

</body>
</html>