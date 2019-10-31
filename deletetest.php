<?php	require("myserversapi.inc.php");
		if (!isset($_GET["testid"]))
			die("Need to supply test ID to delete entry for");
		if (!isset($_GET["serviceid"]))
			die("Need to supply service id to delete entry for");

		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->serviceId = $_GET["serviceid"];
		$params->testId = $_GET["testid"];

		try {
			$MyServersAPI->RemoveTest($params);
		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect back to the list
		header("Location: tests.php?serviceid=" . $_GET["serviceid"]);
?>