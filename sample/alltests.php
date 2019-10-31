<?php
	require("myserversapi.inc.php");
	
	try {
		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->testIds = ArrayHelper($MyServersAPI->GetAllTestIDs($params)->GetAllTestIDsResult, "int");

		$testresults = ArrayHelper($MyServersAPI->GetMultipleTestResults($params)->GetMultipleTestResultsResult, "TestResult");
	} 
	catch (SoapFault $fault) {
		die(var_dump($fault));
	}

	try {
		$params2->authInfo->Username = $APIUsername;
		$params2->authInfo->Password = $APIPassword;
		$params2->serviceIds = ArrayHelper($MyServersAPI->GetAllServerIDs($params2)->GetAllServerIDsResult, "string");

		$currentmonitorstatus = ArrayHelper($MyServersAPI->GetMultipleServerStatus($params2)->GetMultipleServerStatusResult, "CurrentMonitorStatus");
	} 
	catch (SoapFault $fault) {
		die(var_dump($fault));
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
	<style type="text/css">
		table {
			background-color: #aaa;
		}
		tbody {
			background-color: #fff;
			height: 300px;
			overflow: auto;
		}
		td {
			padding: 3px 10px;
		}
		thead, tbody {
			display: block;
		}
	</style>
</head>
<body>

	<h1>All Test Results</h1>
		
	<table border="1" cellpadding="2" cellspacing="0">
		<thead>
			<tr>
				<th>Date</th>
				<th>ServiceId</th>
				<th>StatusCode</th>
				<th>StatusDetail</th>
				<th>TestID</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach ($testresults as $testresult) {	?>
			<tr>
				<td><?php echo $testresult->Date; ?></td>
				<td><a href="server.php?serviceid=<?php echo $testresult->ServiceId; ?>"><?php echo $testresult->ServiceId; ?></a></td>
				<td><?php echo $testresult->StatusCode; ?></td>
				<td><?php echo $testresult->StatusDetail; ?></td>
				<td><a href="monitorhistory.php?serviceid=<?php echo $testresult->ServiceId; ?>&testid=<?php echo $testresult->TestId; ?>"><?php echo $testresult->TestId; ?></a></td>
			</tr>
<?php	}	?>
		</tbody>
	</table>

	<h1>All Server Status</h1>

		<table border="1" cellpadding="2" cellspacing="0">
		<thead>
			<tr>
				<!--<th>ExtensionData</th>-->
				<th>LastStatusChange</th>
				<th>LastUpdated</th>
				<th>MonitoredIp</th>
				<th>ServiceId</th>
				<th>StatusCode</th>
				<th>TestArg1</th>
				<th>TestId</th>
				<th>TestName</th>
				<th>TestType</th>
			</tr>
			<tr>
				<th colspan="9">StatusDetail</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach ($currentmonitorstatus as $status) {	?>
			<tr>
				<!--<td><?php echo $status->ExtensionData; ?></td>-->
				<td><?php echo $status->LastStatusChange; ?></td>
				<td><?php echo $status->LastUpdated; ?></td>
				<td><?php echo $status->MonitoredIp; ?></td>
				<td><a href="server.php?serviceid=<?php echo $status->ServiceId; ?>"><?php echo $status->ServiceId; ?></a></td>
				<td><?php echo $status->StatusCode; ?></td>
				<td><?php echo $status->TestArg1; ?></td>
				<td><a href="monitorhistory.php?serviceid=<?php echo $status->ServiceId; ?>&testid=<?php echo $status->TestId; ?>"><?php echo $status->TestId; ?></a></td>
				<td><?php echo $status->TestName; ?></td>
				<td><?php echo $status->TestType; ?></td>
			</tr>
			<tr>
				<td colspan="9"><?php echo $status->StatusDetail; ?></td>
			</tr>
<?php	}	?>
		</tbody>
	</table>
	
</body>
</html>