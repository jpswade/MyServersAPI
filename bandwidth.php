<?php
	require("myserversapi.inc.php");

	if (!isset($_GET["serviceid"]))
		die("Missing parameter: serviceid");
	$serverid = $_GET["serviceid"];

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->serviceId = $serverid;
	$params->external = true;

	$serverDetails = $MyServersAPI->GetServerDetails($params)->GetServerDetailsResult;
	$monthlybw = ArrayHelper($MyServersAPI->GetMonthlyBandwidthReport($params)->GetMonthlyBandwidthReportResult, "MonthlyBandwidthReportEntry");
	$bwreport = $MyServersAPI->GetServerBandwidthReport($params)->GetServerBandwidthReportResult;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample - Bandwidth Report</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Bandwidth Report: <?php echo $serverid ?></h1>

	<h2>Current Usage</h2>

	<div class="Page">

	<table border="1" cellspacing="0" cellpadding="2">
		<thead>
			<tr>
				<th>Time Period</th>
				<th>BW In</th>
				<th>BW Out</th>
				<th>BW Total</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Last 4 Hours</td>
				<td><?php echo $bwreport->BW4hIn; ?></td>
				<td><?php echo $bwreport->BW4hOut; ?></td>
				<td><?php echo ($bwreport->BW4hIn + $bwreport->BW4hOut); ?></td>
			</tr>
			<tr>
				<td>Last 24 Hours</td>
				<td><?php echo $bwreport->BW24hIn; ?></td>
				<td><?php echo $bwreport->BW24hOut; ?></td>
				<td><?php echo ($bwreport->BW24hIn + $bwreport->BW24hOut); ?></td>
			</tr>
			<tr>
				<td>So far this month</td>
				<td><?php echo $bwreport->BWSofarThisMonthIn; ?></td>
				<td><?php echo $bwreport->BWSofarThisMonthOut; ?></td>
				<td><?php echo ($bwreport->BWSofarThisMonthIn + $bwreport->BWSofarThisMonthOut); ?></td>
			</tr>
			<tr>
				<td>Predicted this month (24h)</td>
				<td><?php echo $bwreport->BWPredicted24hIn; ?></td>
				<td><?php echo $bwreport->BWPredicted24hOut; ?></td>
				<td><?php echo ($bwreport->BWPredicted24hIn + $bwreport->BWPredicted24hOut); ?></td>
			</tr>
			<tr>
				<td>Predicted this month (14d)</td>
				<td><?php echo $bwreport->BWPredicted14dIn; ?></td>
				<td><?php echo $bwreport->BWPredicted14dOut; ?></td>
				<td><?php echo ($bwreport->BWPredicted14dIn + $bwreport->BWPredicted14dOut); ?></td>
			</tr>
		</tbody>
	</table>
	Last Updated: <?php echo $bwreport->LastUpdated; ?>


	<h2>Historical Monthly Data</h2>

	<table border="1" cellspacing="0" cellpadding="2">
		<thead>
			<tr>
				<th>Month</th>
				<th>BW In</th>
				<th>BW Out</th>
				<th>BW Total</th>
				<th>95th Percentile</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach ($monthlybw as $bwentry) { ?>
			<tr>
				<td><?php echo $bwentry->Month; ?></td>
				<td><?php echo $bwentry->BWIn; ?></td>
				<td><?php echo $bwentry->BWOut; ?></td>
				<td><?php echo $bwentry->BWTotal; ?></td>
				<td><?php echo $bwentry->BW95thPercentile; ?></td>
			</tr>
<?php	}	?>
		</tbody>
	</table>

	<h2>Usage graphs</h2>

	<div style="float: left">
		<h3>Past 4 Hours (1 minute average)</h3>
		<img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&timeperiod=4hour" />
	</div>
	<div style="float: left">
		<h3>Past 24 Hours (5 minute average)</h3>
		<img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&timeperiod=24hour" />
	</div>
	<div style="float: left">
		<h3>Past Week (5 minute average)</h3>
		<img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&timeperiod=7day" />
	</div>
	<div style="float: left">
		<h3>Past Month (5 minute average)</h3>
		<img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&timeperiod=1month" />
	</div>
	<div style="float: left">
		<h3>Past Year (5 minute average)</h3>
		<img src="<?php echo $serverDetails->BandwidthUrlBase; ?>&timeperiod=1year" />
	</div>

	</div>

</body>
</html>