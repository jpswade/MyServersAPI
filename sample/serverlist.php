<?php	require("myserversapi.inc.php");	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Server List</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Server List</h1>

<?php
	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;

	try {
		$servers = ArrayHelper($MyServersAPI->GetAllServerDetails($params)->GetAllServerDetailsResult, "ServerInfo");
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}
?>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Server ID</th>
				<th>Type</th>
				<th>Primary IP</th>
				<th>Location</th>
				<th>Your Reference</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($servers as $server) {
?>
			<tr>
				<td><a href='server.php?serviceid=<?php echo $server->ServiceID ?>'><?php echo $server->ServiceID; ?></a></td>
				<td><?php echo $server->ServiceType; ?></td>
				<td><?php echo $server->PrimaryIP; ?></td>
				<td><?php echo $server->Location; ?></td>
				<td><?php echo $server->YourReference; ?></td>
				<td><?php echo $server->Status; ?></td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</body>
</html>