<?php	require("myserversapi.inc.php");
		if (!isset($_GET["ip"]))
			die("Need to supply IP address to delete entry for");
		$ip = $_GET["ip"];

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;

		// To delete an entry, call SetReverseDnsEntry with a blank hostname
		$params->ip = $ip;
		$params->hostName = "";

		try {
			$MyServersAPI->SetReverseDnsEntry($params);
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect back to the list
		header("Location: reversedns.php");
?>