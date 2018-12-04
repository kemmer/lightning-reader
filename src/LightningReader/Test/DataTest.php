<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Data\RequestLog;
use LightningReader\Validator\Validator;


function main()
{
    $parserTest = new DataTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class DataTest extends TestCase
{
    private $requestLog;
    private $validator;

    protected function configure()
    {
        parent::configure();

        $this->validator = new Validator;
        $this->requestLog = new RequestLog($this->validator);
    }

    public function test_CanCreateRequestLog()
    {
        $requestLog = new RequestLog($this->validator);
    }

    public function test_SetRequestLogFields()
    {
        $this->requestLog->setService("USER-SERVICE");
        $this->assertEquals($this->requestLog->service, "USER-SERVICE");
    }

    public function test_SetRequestLogFields_Service()
    {
        $data = "USER-SERVICE";
        $this->requestLog->setService($data);
        $this->assertEquals($this->requestLog->service, $data);
    }

    public function test_SetRequestLogFields_DateTime()
    {
        $data = "17/Aug/2018:09:21:53 +0000";
        $this->requestLog->setDateTime($data);
        $this->assertEquals($this->requestLog->dateTime, $data);
    }

    public function test_SetRequestLogFields_Request()
    {
        $data = "POST /users HTTP/1.1";
        $this->requestLog->setRequestDetails($data);
        $this->assertEquals($this->requestLog->requestDetails, $data);
    }

    public function test_SetRequestLogFields_HttpCode()
    {
        $data = "201";
        $this->requestLog->setHttpCode($data);
        $this->assertEquals($this->requestLog->httpCode, $data);
    }

    public function test_RequestLogComplete()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USER-SERVICE");
        $requestLog->setDateTime("17/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $result = $requestLog->complete();
        $this->assertTrue($result);
    }

    public function test_RequestLogComplete_Fail()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USER-SERVICE");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");

        $result = $requestLog->complete();
        $this->assertFalse($result);
    }

    public function test_RequestLogValidate()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USER-SERVICE");
        $requestLog->setDateTime("17/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $this->assertTrue($requestLog->validate());
    }

    public function test_RequestLogValidate_Fail_DateTime()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USERSERVICE");
        $requestLog->setDateTime("1c/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $this->assertFalse($requestLog->validate());
    }

    public function test_RequestLogValidate_Fail_Service()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USERSERVICE");
        $requestLog->setDateTime("11/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $this->assertFalse($requestLog->validate());
    }

    public function test_RequestLogToArray()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USERSERVICE");
        $requestLog->setDateTime("11/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $this->assertTrue(is_array($requestLog->toArray()));
    }

    public function test_RequestLogSanitize()
    {
        $requestLog = new RequestLog($this->validator);
        $requestLog->setService("USERSERVICE");
        $requestLog->setDateTime("11/Aug/2018:09:21:53 +0000");
        $requestLog->setRequestDetails("POST /users HTTP/1.1");
        $requestLog->setHttpCode("201");

        $requestLog->sanitize();
        $this->assertEquals($requestLog->toArray()["dateTime"], "2018-08-11 09:21:53");
    }
}

main();
