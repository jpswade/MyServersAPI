<?php	require("myserversapi.inc.php");
		if (!isset($_GET["domainid"]))
			die("Need to supply Domain Id to delete entry from");
		if (!isset($_GET["recordid"]))
			die("Need to supply record id of the entry to delete");

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;

		// To delete an entry, call SetReverseDnsEntry with a blank hostname
		$params->domainId = $_GET["domainid"];
		$params->recordId = $_GET["recordid"];

		try {
			$MyServersAPI->DeleteForwardDnsEntry($params);
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect back to the list
		header("Location: domain.php?domainid=" . $_GET["domainid"]);
?>