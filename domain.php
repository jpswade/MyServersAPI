<?php	require("myserversapi.inc.php");

	// Handle Add form being posted back
	if (isset($_POST["addentry"])) {

		if (!isset($_POST["recordtype"]) || strlen($_POST["recordtype"]) == 0)
			die("record type must be entered");

		$recordType = $_POST["recordtype"];
		$addparams->authInfo->Username = $APIUsername;
		$addparams->authInfo->Password = $APIPassword;
		$addparams->domainId = $_POST["domainId"];

		try {
			switch ($recordType) {
				case "A":
					if (!isset($_POST["data1"]))
						die("Record name must be entered (may be blank)");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("IP Address must be entered");

					$addparams->recordName = $_POST["data1"];
					$addparams->ip = $_POST["data2"];

					$MyServersAPI->AddDnsARecord($addparams);

					break;

				case "AAAA";
					if (!isset($_POST["data1"]))
						die("Record name must be entered (may be blank)");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("IP Address must be entered");

					$addparams->recordName = $_POST["data1"];
					$addparams->ip = $_POST["data2"];

					$MyServersAPI->AddDnsAAAARecord($addparams);

					break;

				case "CNAME":
					if (!isset($_POST["data1"]) )
						die("Record name must be entered (may be blank)");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("Record destination must be entered");

					$addparams->recordName = $_POST["data1"];
					$addparams->destination = $_POST["data2"];

					$MyServersAPI->AddDnsCNAMERecord($addparams);

					break;
				case "MX":
					if (!isset($_POST["data1"]) )
						die("Subdomain must be entered (may be blank)");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("Priority must be entered");
					if (!isset($_POST["data3"]) || strlen($_POST["data3"]) == 0)
						die("Mail server must be entered");

					$addparams->subDomain = $_POST["data1"];
					$addparams->priority = $_POST["data2"];
					$addparams->mailServer = $_POST["data3"];

					$MyServersAPI->AddDnsMXRecord($addparams);

					break;

				case "NS":
					if (!isset($_POST["data1"]) || strlen($_POST["data1"]) == 0)
						die("Hostname must be entered");

					$addparams->hostName = $_POST["data1"];

					$MyServersAPI->AddDnsNSRecord($addparams);
					break;

				case "SRV":
					if (!isset($_POST["data1"]) || strlen($_POST["data1"]) == 0)
						die("Service Name must be entered");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("Priority must be entered");
					if (!isset($_POST["data3"]) || strlen($_POST["data3"]) == 0)
						die("Weight must be entered");
					if (!isset($_POST["data4"]) || strlen($_POST["data4"]) == 0)
						die("Port server must be entered");
					if (!isset($_POST["data5"]) || strlen($_POST["data5"]) == 0)
						die("Hostname must be entered");

					$addparams->serviceName = $_POST["data1"];
					$addparams->priority = $_POST["data2"];
					$addparams->weight = $_POST["data3"];
					$addparams->port = $_POST["data4"];
					$addparams->hostName = $_POST["data5"];

					$MyServersAPI->AddDnsSRVRecord($addparams);

					break;

				case "TXT":
					if (!isset($_POST["data1"]))
						die("Record name must be entered (may be blank)");
					if (!isset($_POST["data2"]) || strlen($_POST["data2"]) == 0)
						die("Text Data must be entered");

					$addparams->recordName = $_POST["data1"];
					$addparams->data = $_POST["data2"];

					$MyServersAPI->AddDnsTXTRecord($addparams);

					break;
			}
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect to this page
		header("Location: " . $_SERVER["PHP_SELF"] . "?domainid=" . $_POST["domainId"]);
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Domain Records</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<?php
	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->domainId = $_GET["domainid"];

	try {
		$domain = $MyServersAPI->GetForwardDnsDomain($params)->GetForwardDnsDomainResult;
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}
?>

	<h1>Domain: <?php echo $domain->DomainName ?></h1>

	[<a href="editdomain.php?domainid=<?php echo $domain->DomainId ?>">Edit Domain</a>]

<?php

	if ($domain->HostingType == "Primary") {

		try {
			$domainrecords = ArrayHelper($MyServersAPI->GetForwardDnsEntries($params)->GetForwardDnsEntriesResult, "HostedDomainRecord");
		} catch (SoapFault $fault) {
			die(var_dump($fault));
		}

?>

	<h2>Domain Records</h2>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Record Type</th>
				<th>Name</th>
				<th>Data</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($domainrecords as $record) {
		$data = ArrayHelper($record->RecordData, "string");
?>
			<tr>
				<td><?php echo $record->RecordType; ?></td>
				<td><?php echo $data[0]; ?></td>
				<td><?php
				for ($i=1; $i<count($data); $i++) {
					echo $data[$i];
					echo " ";
				} ?></td>
				<td>[<a href="deletednsentry.php?domainid=<?php echo $domain->DomainId; ?>&recordid=<?php echo $record->RecordId ?>">Del</a>]</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>

	<h2>Add Domain Record</h2>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<input type="hidden" name="domainId" value='<?php echo $domain->DomainId ?>' />
		<table border="0" cellpadding="2">
			<tr>
				<td>Record Type:</td>
				<td><select name="recordtype">
					<option>A</option>
					<option>AAAA</option>
					<option>CNAME</option>
					<option>MX</option>
					<option>NS</option>
					<option>SRV</option>
					<option>TXT</option>
				</select></td>
			</tr>
			<tr>
				<td>Data1:</td>
				<td><input type="text" name="data1" cols="30" /></td>
			</tr>
			<tr>
				<td>Data2:</td>
				<td><input type="text" name="data2" cols="30" /></td>
			</tr>
			<tr>
				<td>Data3:</td>
				<td><input type="text" name="data3" cols="30" /></td>
			</tr>
			<tr>
				<td>Data4:</td>
				<td><input type="text" name="data4" cols="30" /></td>
			</tr>
			<tr>
				<td>Data5:</td>
				<td><input type="text" name="data5" cols="30" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="addentry" value="Add Entry" />
			</tr>
		</table>

	</form>
<?php	} else if ($domain->HostingType == "Secondary") { ?>

	Secondary Domain

<?php	} else if ($domain->HostingType == "None") { ?>

	Domain with no hosting

<?php	} ?>

</body>
</html>