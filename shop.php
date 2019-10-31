<?php	require("myserversapi.inc.php");

	//
	// Handle Add form being posted back
	//

	if (isset($_POST["create"])) {
		$params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
		$params->email = $_POST["email"];
		$params->remoteIP = $_POST["remoteIP"];
		$params->userAgent = $_POST["userAgent"];
		$params->referer = $_POST["referer"];

		try {
			$newbasket = $MyServersAPI->CreateBasketByID($params)->CreateBasketByIDResult;

		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect to the basket page
		header("Location: basket.php?basketguid=" . $newbasket->BasketGuid);
	}

	if (isset($_POST["create2"])) {
		$params = new stdClass();
        $params->authInfo->Username = $APIUsername;
		$params->authInfo->Password = $APIPassword;
        $basket = new stdClass();
        $basket->Email = $_POST["email"];
        $basket->RemoteIP = $_POST["remoteIP"];
        $basket->UserAgent = $_POST["userAgent"];
        $basket->Referer = $_POST["referer"];

        $params->basket = $basket;

		try {
			$newbasket = $MyServersAPI->CreateBasket($params)->CreateBasketResult;

		} catch (SoapFault $fault) {
			$error = $fault;
			var_dump($error);
			die();
		}

		// Redirect to the basket page
		header("Location: basket.php?basketguid=" . $newbasket->BasketGuid);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Shop</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Shop</h1>

<?php

?>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<fieldset>
			<legend>Create A Basket<legend>
			<ol>
				<li>
					<input type="text" name="email" value="phpsample@rapidswitch.com" />
				</li>
				<li>
					<input type="text" name="remoteIP" value="127.0.0.1" />
				</li>
				<li>
					<input type="text" name="userAgent" value="Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.125 Safari/537.36" />
				</li>
				<li>
					<input type="text" name="referer" value="PHPSample" />
				</li>
			</ol>
		</fieldset>
		<p>There are 2 methods which can be used to create a basket</p>
		<input type="submit" name="create" value="Create - CreateBasketByID()" />
		<span>This is the simplest method</span>
		<br />
		<br />
		<input type="submit" name="create2" value="Create - CreateBasket()" />
		<span>This is the trickier method, as it requires an object to be passed</span>
	</form>
</body>
</html>