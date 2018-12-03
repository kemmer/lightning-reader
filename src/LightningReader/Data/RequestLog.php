<?php

namespace LightningReader\Data;

use LightningReader\Data\RequestLogAbstract;


class RequestLog extends RequestLogAbstract
{
    public $service = "";
    public $dateTime = "";
    public $requestDetails = "";
    public $httpCode = "";

    public function setService(string $data)
    {
        $this->service = $data;
    }

    public function setDateTime(string $data)
    {
        $this->dateTime = $data;
    }

    public function setRequestDetails(string $data)
    {
        $this->requestDetails = $data;
    }

    public function setHttpCode(string $data)
    {
        $this->httpCode = $data;
    }

    public function complete() : bool
    {
        if(!empty($this->service)
            && !empty($this->dateTime)
            && !empty($this->requestDetails)
            && !empty($this->httpCode)) {

            return true;
        }

        return false;
    }
}
