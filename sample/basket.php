<?php	require("myserversapi.inc.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Basket</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<?php
	$params->authInfo->Username = $APIUsername;
	$params->authInfo->Password = $APIPassword;
	$params->basketGuid = $_GET["basketguid"];

	try {
		$basket = $MyServersAPI->GetBasket($params)->GetBasketResult;
	} 
	catch (SoapFault $fault) {
		die(var_dump($fault));
	}

?>

<h1>Basket</h1>
<fieldset>
	<legend>Basket</legend>
	<ol>
		<li>
			<label>BasketGuid</label>
			<input type="text" name="basketguid" readonly="readonly" value="<?php echo $basket->BasketGuid ?>" />
		</li>
		<li>
			<label>Date Created</label>
			<input type="text" name="datecreated" readonly="readonly" value="<?php echo $basket->DateCreated ?>" />
		</li>
		<li>
			<label>Date Expires</label>
			<input type="text" name="dateexpires" readonly="readonly" value="<?php echo $basket->DateExpires ?>" />
		</li>
		<li>
			<label>Cost -> SetupTotalCost</label>
			<input type="text" name="cost1" readonly="readonly" value="<?php echo $basket->Cost->SetupTotalCost ?>" />
		</li>
		<li>
			<label>Cost -> MonthlyTotalCost</label>
			<input type="text" name="cost2" readonly="readonly" value="<?php echo $basket->Cost->MonthlyTotalCost ?>" />
		</li>
	</ol>
</fieldset>
</body>
</html>
