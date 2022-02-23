<?php

namespace Deaduseful\MyServersApi;

use SoapClient;
use SoapFault;

class MySoapClient extends SoapClient
{
    private $maximum_retries = 5;

    /**
     * @param string $name
     * @param mixed $args
     * @return false|mixed
     * @throws SoapFault
     */
    public function __call($name, array $args)
    {
        $result = false;
        $retryCount = 0;
        while (!$result && $retryCount < $this->maximum_retries) {
            try {
                $result = parent::$name($args);
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
