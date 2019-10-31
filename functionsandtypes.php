<?php	require("myserversapi.inc.php");	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RapidSwitch API Sample: Functions and Types</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
	<h1>Functions and Types</h1>

	<h2>Functions</h2>

<pre><?php	var_dump($MyServersAPI->__getFunctions()); ?></pre>

	<h2>Types</h2>

<pre><?php	var_dump($MyServersAPI->__getTypes()); ?></pre>