<?php	require("myserversapi.inc.php");
		if (!isset($_GET["domainid"]))
			die("Need to supply Domain Id to delete");

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;

		// To delete an entry, call SetReverseDnsEntry with a blank hostname
		$params->domainId = $_GET["domainid"];

		try {
			$MyServersAPI->DeleteForwardDnsDomain($params);
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect back to the list
		header("Location: forwarddns.php");
?>