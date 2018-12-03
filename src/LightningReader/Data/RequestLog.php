<?php

namespace LightningReader\Data;

use LightningReader\Data\RequestLogAbstract;
use LightningReader\Validator\ValidatorInterface;


class RequestLog extends RequestLogAbstract
{
    private $service;
    private $dateTime;
    private $requestDetails;
    private $httpCode;

    private $validator;
    private $database;

    public function __construct(ValidatorInterface $validator, $)
    {

    }
}
