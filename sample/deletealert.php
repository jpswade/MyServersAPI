<?php	require("myserversapi.inc.php");
		if (!isset($_GET["alertid"]))
			die("Need to supply alert ID to delete entry for");
		if (!isset($_GET["serviceid"]))
			die("Need to supply service id to delete entry for");

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->serviceId = $_GET["serviceid"];
		$params->alertId = $_GET["alertid"];

		try {
			$MyServersAPI->RemoveAlert($params);
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect back to the list
		header("Location: alerts.php?serviceid=" . $_GET["serviceid"]);
?>