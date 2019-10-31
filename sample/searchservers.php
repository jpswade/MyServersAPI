<?php	require("myserversapi.inc.php");	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Search Servers</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Search Servers</h1>

<?php

	$status = array("All");			// One or more of {All, Active, AwaitingBuild, AwaitingDelivery, AwaitingInstallation, Built, Cancelled}
	$serviceType = array("All");	// One of more of {All, Colo, Dedi, ManagedRack, Rack, Transit, VPS}
	$deviceType = array("All");		// One or more of {All, Access_Point, Firewall, Full_Rack, Load_Balancer, Managed_Rack, Managed_Switch, Modem, Router, Server, Transit, Unmanaged_Switch, Virtual_Server}
	$filter = "";					// e.g. Your-Ref or an IS-number
	$showIPs = false;				// true or false - false is faster as it will return less information
	$showComponents = false;		// true or false - false is faster as it will return less information
	$showReverseDNS = false;		// true or false - false is faster as it will return less information

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->status->ServerStatusSearch = $status;
	$params->serviceType->ServerServiceTypeSearch = $serviceType;
	$params->deviceType->ServerDeviceTypeSearch = $deviceType;
	$params->filter = $filter;
	$params->showIPs = $showIPs;
	$params->showComponents = $showComponents;
	$params->showReverseDNS = $showReverseDNS;

	try {
		$servers = ArrayHelper($MyServersAPI->SearchServers($params)->SearchServersResult, "ServerInfo");
		// For debugging use
		//$servers = ArrayHelper($MyServersAPIWithTrace->SearchServers($params)->SearchServersResult, "ServerInfo");
		//echo "REQUEST:\n" . $MyServersAPIWithTrace->__getLastRequest() . "\n";
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}
?>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Server ID</th>
				<th>Primary IP</th>
				<th>Location</th>
				<th>Your Reference</th>
				<th>Status</th>
				<th>Service Type</th>
				<th>Device Type</th>
				<th>Normal Cost</th>
				<th>Extra Cost</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($servers as $server) {
?>
			<tr>
				<td><a href='server.php?serviceid=<?php echo $server->ServiceID ?>'><?php echo $server->ServiceID; ?></a></td>
				<td><?php echo $server->PrimaryIP; ?></td>
				<td><?php echo $server->Location; ?></td>
				<td><?php echo $server->YourReference; ?></td>
				<td><?php echo $server->Status; ?></td>
				<td><?php echo $server->ServiceType; ?></td>
				<td><?php echo $server->DeviceType; ?></td>
				<td><?php echo $server->NormalCost; ?></td>
				<td><?php echo $server->ExtraCost; ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</body>
</html>