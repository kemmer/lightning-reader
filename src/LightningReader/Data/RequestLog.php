<?php

namespace LightningReader\Data;

use LightningReader\Data\RequestLogAbstract;
use LightningReader\Validator\ValidatorInterface;
use LightningReader\Validator\Rule\{ServiceRule, NumericRule, DateTimeRule};
use LightningReader\Data\Sanitize\DateTimeSanitize;


class RequestLog extends RequestLogAbstract
{
    public $service        = "";
    public $dateTime       = "";
    public $requestDetails = "";
    public $httpCode       = "";

    private $validator;

    private $completed;
    private $validated;
    private $sanitized;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->configureValidatorRules();

        $this->completed = false;
        $this->validated = false;
        $this->sanitized = false;
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

            $this->completed = true;
            return $this->completed;
        }

        return false;
    }

    public function validate() : bool
    {
        $this->validator->setFieldData("service", $this->service);
        $this->validator->setFieldData("dateTime", $this->dateTime);
        $this->validator->setFieldData("httpCode", $this->httpCode);

        $this->validated = $this->validator->validate();
        return $this->validated;
    }

    public function sanitize() : bool
    {
        /* Sanitize $dateTime */
        $sanitizer = new DateTimeSanitize;
        $this->dateTime = $sanitizer->transform($this->dateTime);

        /**
         * The other fields are already ok since
         * here they have been already validated
         */
        $this->sanitized = true;
        return $this->sanitized;
    }

    public function toArray() : array
    {
        return [
            'service'        => $this->service,
            'dateTime'       => $this->dateTime,
            'requestDetails' => $this->requestDetails,
            'httpCode'       => $this->httpCode,
        ];
    }
}
