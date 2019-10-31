<?php	require("myserversapi.inc.php");

	if (isset($_POST["addentry"])) {

		if (!isset($_POST["ip"]) || strlen($_POST["ip"]) == 0)
			die("ip parameter must be entered");
		if (!isset($_POST["hostname"]) || strlen($_POST["hostname"]) == 0)
			die("hostname parameter must be entered");

		$addparams->authInfo->Username = $APIUsername;
		$addparams->authInfo->Password = $APIPassword;
		$addparams->ip = $_POST["ip"];
		$addparams->hostname = $_POST["hostname"];

		try {
			$MyServersAPI->SetReverseDnsEntry($addparams);
		} catch (SoapFault $fault) {
				$error = $fault;
				var_dump($error);
				die();
		}

		// Redirect to this page
		header("Location: " . $_SERVER["PHP_SELF"]);
	}

	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;

	try {
		$reversedns = ArrayHelper($MyServersAPI->GetReverseDnsEntries($params)->GetReverseDnsEntriesResult, "ReverseDnsEntry");
	} catch (SoapFault $fault) {
		$error = $fault;
		var_dump($error);
		die();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Reverse DNS</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Reverse DNS</h1>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>IP Address</th>
				<th>HostName</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($reversedns as $entry) {
?>
			<tr>
				<td><?php echo $entry->IPAddress; ?></a></td>
				<td><?php echo $entry->HostName; ?></td>
				<td>[<a href="deletereversedns.php?ip=<?php echo $entry->IPAddress; ?>">Del</a>]</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>

	<h2>Add Reverse DNS Entry</h2>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<table border="0" cellpadding="2">
			<tr>
				<td>IP Address:</td>
				<td><input type="text" name="ip" cols="10" maxlength="15" /></td>
			</tr>
			<tr>
				<td>Hostname:</td>
				<td><input type="text" name="hostname" cols="30" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="addentry" value="Add Entry" />
			</tr>
		</table>

	</form>

</body>
</html>