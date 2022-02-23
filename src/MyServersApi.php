<?php

namespace Deaduseful\MyServersApi;

use SoapClient;
use stdClass;

class MyServersApi
{
    const LIVE_URL = 'https://api.rapidswitch.com/MyServersApi/MyServersApi.svc?wsdl';
    const TEST_URL = 'https://api.rapidswitch.com/MyServersApi/Simulator.svc?wsdl';

    /** @var SoapClient */
    private $client;

    /** @var stdClass */
    private $params;

    /**
     * MyServersApi constructor.
     *
     * @param string $url Simulator or Live Url
     * @param string $username
     * @param string $password
     * @param MySoapClient|null $client
     * @throws \SoapFault
     */
    public function __construct(string $url, string $username, string $password, SoapClient $client = null)
    {
        $client = $client ?: new SoapClient($url);
        $this->setClient($client);
        $params = new StdClass;
        $authInfo = $this->buildAuthInfo($username, $password);
        $params->authInfo = $authInfo;
        $this->setParams($params);
    }

    public function buildAuthInfo($username, $password): stdClass
    {
        $authInfo = new StdClass;
        $authInfo->Username = $username;
        $authInfo->Password = $password;
        return $authInfo;
    }

    /**
     * Helper function for SOAP functions which return arrays.  The return from these functions depends
     * on the number of elements in the array - 0 elements returns an empty class, 1 element returns the
     * element itself, and more than 1 elements returns an array.  This helper function always returns
     * an array.
     *
     * @param mixed $var
     * @param string $typename
     * @return array
     */
    public static function arrayHelper($var, string $typename): array
    {
        if (isset($var->$typename) === false) {
            return [];
        }
        $obj = $var->$typename;
        if (is_array($obj)) {
            return $obj;
        } elseif (is_object($obj)) {
            return [$obj];
        } else {
            return [];
        }
    }

    /**
     * @return stdClass
     */
    public function getParams(): stdClass
    {
        return $this->params;
    }

    /**
     * @param stdClass $params
     * @return MyServersApi
     */
    public function setParams(stdClass $params): MyServersApi
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return SoapClient
     */
    public function getClient(): SoapClient
    {
        return $this->client;
    }

    /**
     * @param SoapClient $client
     * @return MyServersApi
     */
    public function setClient(SoapClient $client): MyServersApi
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return MyServersApi
     */
    public function addParam(string $key, $value): MyServersApi
    {
        $this->params->{$key} = $value;
        return $this;
    }
}