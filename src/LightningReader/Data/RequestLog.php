<?php

namespace LightningReader\Data;

use LightningReader\Data\RequestLogAbstract;
use LightningReader\Validator\ValidatorInterface;
use LightningReader\Validator\Rule\{ServiceRule, NumericRule, DateTimeRule};


class RequestLog extends RequestLogAbstract
{
    public $service        = "";
    public $dateTime       = "";
    public $requestDetails = "";
    public $httpCode       = "";

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->configureValidatorRules();
    }

    private function configureValidatorRules()
    {
        $this->validator->newField("service");
        $this->validator->newField("dateTime");
        $this->validator->newField("httpCode");

        $this->validator->addFieldRule("service", new ServiceRule);
        $this->validator->addFieldRule("dateTime", new DateTimeRule);
        $this->validator->addFieldRule("httpCode", new NumericRule);
    }

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

    public function validate() : bool
    {
        $this->validator->setFieldData("service", $this->service);
        $this->validator->setFieldData("dateTime", $this->dateTime);
        $this->validator->setFieldData("httpCode", $this->httpCode);

        return $this->validator->validate();
    }
}
