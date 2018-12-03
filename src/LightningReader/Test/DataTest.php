<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
// use LightningReader\Data\RequestLog;
use LightningReader\Validator\RequestLogValidator;


function main()
{
    $parserTest = new DataTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class DataTest extends TestCase
{
    private $validator;

    protected function configure()
    {
        parent::configure();
    }

    public function test_CanCreateRequestLogValidator()
    {
        $this->validator = new RequestLogValidator;
    }

    public function test_CanCreateValidationField()
    {
        $data = "[sdsd sdsdf  \"==]";
        $this->validator->data($data)->set();

        $this->assertEquals($this->validator->pending[0]['data'], $data);
    }

    public function test_CanSetRule()
    {
        $data = "[sdsd sdsdf  \"==]";
        $this->validator->data($data)->rule(RequestLogValidator::INTEGER)->set();

        $this->assertEquals($this->validator->pending[0]['rule'], $data);
    }
}

main();
