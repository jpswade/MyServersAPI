<?php
	$APIUsername = "Cxxxxxx@api.rapidswitch.com";
	$APIPassword = "xxxxxxxxxxxxxxxxxxxxxxxxxxx";

	// Simulator Url - comment out when testing is finished
	$MyServersAPI = new SoapClient('https://api.rapidswitch.com/MyServersAPI/Simulator.svc?wsdl');

	// Live Url - uncomment when testing is finished
	//$MyServersAPI = new SoapClient('https://api.rapidswitch.com/MyServersAPI/MyServersApi.svc?wsdl');

  // Live Url with tracing enabled so we can see the requests - uncomment when testing is finished
  //$MyServersAPIWithTrace = new SoapClient('https://api.rapidswitch.com/MyServersAPI/MyServersApi.svc?wsdl', array('trace' => 1));
  
	//
	// Helper function for SOAP functions which return arrays.  The return from these functions depends
	// on the number of elements in the array - 0 elements returns an empty class, 1 element returns the
	// element itself, and more than 1 elements returns an array.  This helper function always returns
	// an array
	//
	function ArrayHelper($var, $typename) {
		if (!isset($var->$typename))
			return array();

		$obj = $var->$typename;

		if (is_array($obj))
			return $obj;
		else if (is_object($obj) || is_scalar($obj))
			return array($obj);
		else
			return array();
	}
?>