<?php

namespace Deaduseful\MyServersApi;

use SoapClient;
use SoapFault;

class MySoapClient extends SoapClient
{
    private $maximum_retries = 5;

    public function __call($function_name, $arguments)
    {
        $result = false;
        $retryCount = 0;
        while (!$result && $retryCount < $this->maximum_retries) {
            try {
                $result = parent::$function_name($arguments);
            } catch (SoapFault $fault) {
                if ($fault->faultstring != 'Could not connect to host') {
                    throw $fault;
                }
            }
            sleep(1);
            $retryCount++;
        }
        if ($retryCount >= $this->maximum_retries) {
            throw new SoapFault(508, sprintf('Could not connect to host after %d attempts', $this->maximum_retries));
        }
        return $result;
    }
}
