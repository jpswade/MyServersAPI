<?php

namespace Deaduseful\MyServersApi;

use SoapClient;
use SoapFault;

class MySoapClient extends SoapClient
{
    /**
     * @param string $function_name
     * @param array $arguments
     * @param int $maximum_retries
     * @return false|mixed
     * @throws SoapFault
     */
    public function __call($function_name, $arguments, $maximum_retries = 5)
    {
        $result = false;
        $retryCount = 0;
        while (!$result && $retryCount < $maximum_retries) {
            try {
                $result = parent::__call($function_name, $arguments);
            } catch (SoapFault $fault) {
                if ($fault->faultstring != 'Could not connect to host') {
                    throw $fault;
                }
            }
            sleep(1);
            $retryCount++;
        }
        if ($retryCount >= $maximum_retries) {
            throw new SoapFault(508, sprintf('Could not connect to host after %d attempts', $maximum_retries));
        }
        return $result;
    }
}
