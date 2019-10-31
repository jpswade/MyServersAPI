<?php

include(__DIR__ . '/../src/MyServersApi.php');

use Deaduseful\MyServersApi\MyServersApi;

$api = new MyServersApi(MyServersApi::LIVE_URL, getenv('MYSERVERS_API_USER'), getenv('MYSERVERS_API_PASS'));
$servers = MyServersApi::arrayHelper($api->getClient()->GetAllServerDetails($api->getParams())->GetAllServerDetailsResult, 'ServerInfo');
$list = [];
foreach ($servers as $server) {
    $id = $server->ServiceID;
    $primaryIp = $server->PrimaryIP;
    $reverseDns = MyServersApi::arrayHelper($server->ReverseDnsEntries, 'ReverseDnsEntry');
    foreach ($reverseDns as $entry) {
        $ip = $entry->IPAddress;
        $host = $entry->HostName;
        if ($ip == $primaryIp) {
            $id = $host;
        }
    }
    $ips = MyServersApi::arrayHelper($server->IPAddresses, 'string');
    foreach ($ips as $ip) {
        $list[$ip] = $id;
    }
}
var_dump($list);
