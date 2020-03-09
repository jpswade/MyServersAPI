<?php

include(__DIR__ . '/../src/MyServersApi.php');

use Deaduseful\MyServersApi\MyServersApi;

$api = new MyServersApi(MyServersApi::LIVE_URL, getenv('MYSERVERS_API_USER'), getenv('MYSERVERS_API_PASS'));
$result = MyServersApi::arrayHelper($api->getClient()->GetClientInfo($api->getParams(), $api->getParams())->GetClientInfoResult, 'ClientInfo');
var_dump($result);
