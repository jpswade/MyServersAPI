<?php	require("myserversapi.inc.php");

	// Handle Add form being posted back
	if (isset($_POST["updatedomain"])) {

		$updateparams->authInfo->Username = $APIUsername;
		$updateparams->authInfo->Password = $APIPassword;
		$updateparams->domainId = $_POST["domainId"];
		$updateparams->hostingType = $_POST["hostingtype"];
		$updateparams->primaryNS = $_POST["primaryns"];
		$updateparams->allowedTransferList = $_POST["transfersto"];

		try {
			$MyServersAPI->UpdateDomainHostingSettings($updateparams);

		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect to the domain page
		header("Location: domain.php?domainid=" . $_POST["domainId"]);
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Edit Domain</title>
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

	<h1>Edit Domain: <?php echo $domain->DomainName ?></h1>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<input type="hidden" name="domainId" value='<?php echo $domain->DomainId ?>' />
		<table border="0" cellpadding="2">

			<tr>
				<td>Hosting Type:</td>
				<td><select name="hostingtype">
					<option <?php if ($domain->HostingType == "Primary") echo "selected"; ?>>Primary</option>
					<option <?php if ($domain->HostingType == "Secondary") echo "selected"; ?>>Secondary</option>
				</select></td>
			</tr>

			<tr>
				<td>Allow Transfers To:</td>
				<td><input type="text" name="transfersto" cols="30" value='<?php echo $domain->AllowedTransferList ?>' /></td>
			</tr>

			<tr>
				<td>Primary NS:</td>
				<td><input type="text" name="primaryns" cols="30" value='<?php echo $domain->PrimaryNS ?>' /></td>
			</tr>

			<tr>
				<td>&nbsp;</td>
				<td><input type="submit" name="updatedomain" value="Update Domain" />
			</tr>
		</table>
	</form>

</body>
</html>