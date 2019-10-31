<?php	require("myserversapi.inc.php");

	// Handle Add form being posted back
	if (isset($_POST["addentry"])) {

		$addparams->authInfo->Username = $APIUsername;
		$addparams->authInfo->Password = $APIPassword;
		$addparams->domainName = $_POST["domainName"];
		$addparams->hostingType = $_POST["hostingType"];
		$addparams->primaryNS = $_POST["primaryNS"];

		$domainid = $MyServersAPI->AddForwardDnsDomain($addparams)->AddForwardDnsDomainResult;

		// Redirect to this page
		header("Location: domain.php?domainid=" . $domainid);
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Forward DNS</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Forward DNS</h1>

<?php
	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;

	try {
		$domains = ArrayHelper($MyServersAPI->GetForwardDnsDomains($params)->GetForwardDnsDomainsResult, "HostedDomainInfo");
	} catch (SoapFault $fault) {
		die(var_dump($fault));
	}
?>

	<table border="1" cellspacing="0">
		<thead>
			<tr>
				<th>Domain Name</th>
				<th>Hosting Type</th>
				<th>Expiry Date</th>
				<th>Primary IP</th>
				<th>Authority Status</th>
				<th>Transfer Status</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
<?php
	foreach ($domains as $domain) {
?>
			<tr>
				<td><a href='domain.php?domainid=<?php echo $domain->DomainId ?>'><?php echo $domain->DomainName; ?></a></td>
				<td><?php echo $domain->HostingType; ?></td>
				<td><?php echo $domain->ExpiryDate; ?></td>
				<td><?php echo $domain->PrimaryNS; ?></td>
				<td><?php echo $domain->AuthorityStatus; ?></td>
				<td><?php echo $domain->SecondaryTransferStatus; ?></td>
				<td>[<a href="deletedomain.php?domainid=<?php echo $domain->DomainId; ?>">Del</a>]</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>

	<h2>Add Domain</h2>

		<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
			<table border="0" cellpadding="2">
				<tr>
					<td>Domain Name:</td>
					<td><input name="domainName" type="text" cols="30" />
				</tr>
				<tr>
					<td>Hosting Type:</td>
					<td><select name="hostingType">
						<option>Primary</option>
						<option>Secondary</option>
					</select></td>
				</tr>
				<tr>
					<td>Primary Nameserver IP:</td>
					<td><input type="text" name="primaryNS" cols="30" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="addentry" value="Add Domain" />
				</tr>
			</table>

	</form>
</body>
</html>