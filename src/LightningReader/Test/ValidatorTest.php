<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Validator\Validator;
use LightningReader\Validator\Field;
use LightningReader\Validator\Rule\Helpers;
use LightningReader\Validator\Rule\{NumericRule, ServiceRule, DateTimeRule};


function main()
{
    $parserTest = new ValidatorTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class ValidatorTest extends TestCase
{
    private $validator;
    private $numericRule, $serviceRule, $dateTimeRule;

    protected function configure()
    {
        parent::configure();

        $this->validator = new Validator;
        $this->numericRule = new NumericRule;
        $this->serviceRule = new ServiceRule;
        $this->dateTimeRule = new DateTimeRule;
    }

    public function test_CanCreateField()
    {
        $field = new Field;
    }

    public function test_CanCreateValidator()
    {
        $validator = new Validator;
    }

    public function test_CheckByMask()
    {
        $data = "ss/ddd:333";
        $mask = "aa/aaa:nnn";
        $this->assertTrue(Helpers::checkByMask($data, $mask));
    }

    public function test_CheckByMask_Fail_Length()
    {
        $data = "ss/d^d:33";
        $mask = "aa/aaa:nnn";
        $this->assertFalse(Helpers::checkByMask($data, $mask));
    }

    public function test_CheckByMask_Fail()
    {
        $data = "ss/d8d:333";
        $mask = "aa/aaa:nnn";
        $this->assertFalse(Helpers::checkByMask($data, $mask));
    }

    public function test_RuleCanTestNumericOnly_Fail()
    {
        $this->assertFalse($this->numericRule->test("2939s43"));
    }

    public function test_RuleCanTestNumericOnly()
    {
        $this->assertTrue($this->numericRule->test("2939043"));
    }

    public function test_RuleCanTestServiceIdentification_False()
    {
        $this->assertFalse($this->serviceRule->test("USERsa~SER223VICE"));
    }

    public function test_RuleCanTestServiceIdentification()
    {
        $this->assertTrue($this->serviceRule->test("USER-SER223VICE"));
    }

    public function test_RuleCanTestDateTime_Fail_Month()
    {
        $this->assertFalse($this->dateTimeRule->test("17/Augu/2018:09:21:53 +0000"));
    }

    public function test_RuleCanTestDateTime_Fail_Day()
    {
        $this->assertFalse($this->dateTimeRule->test("32/Augu/2018:09:21:53 +0000"));
    }

    public function test_RuleCanTestDateTime_Fail_TooSmall()
    {
        $this->assertFalse($this->dateTimeRule->test("1/Aug/2018:09:21 +0000"));
    }

    public function test_RuleCanTestDateTime_Fail_WrongTimezone()
    {
        $this->assertFalse($this->dateTimeRule->test("17/Aug/2018:09:21:53 +000"));
    }

    public function test_RuleCanTestDateTime_Fail_WrongMonth()
    {
        $this->assertFalse($this->dateTimeRule->test("17/Dez/2018:09:21:53 +000"));
    }

    public function test_RuleCanTestDateTime()
    {
        $this->assertTrue($this->dateTimeRule->test("17/Aug/2018:09:21:53 +0000"));
    }

    /* ---------------------------------- */

    public function test_FieldCanSetData()
    {
        $field = new Field;
        $field->setData("HUEBR");
    }

    public function test_FieldCanAddRule()
    {
        $field = new Field;
        $field->addRule($this->dateTimeRule);
    }

    public function test_FieldCanCheck_Fail()
    {
        $field = new Field;
        $field->setData("HUEBR");
        $field->addRule($this->numericRule);

        $this->assertFalse($field->check());
    }

    public function test_FieldCanCheck()
    {
        $field = new Field;
        $field->setData("122333");
        $field->addRule($this->numericRule);

        $this->assertTrue($field->check());
    }

    /* ---------------------------------- */

    public function test_ValidatorNewField()
    {
        $this->validator->newField("service");
    }

    public function test_ValidatorAddFieldRule()
    {
        $this->validator->newField("service");
        $this->validator->addFieldRule("service", new NumericRule);
    }

    public function test_ValidatorSetFieldData()
    {
        $this->validator->newField("service");
        $this->validator->addFieldRule("service", new ServiceRule);
        $this->validator->setFieldData("service", "USER-SERVICE");
    }

    public function test_ValidatorValidate_Fail()
    {
        $this->validator->newField("service");
        $this->validator->addFieldRule("service", new ServiceRule);
        $this->validator->setFieldData("service", "USERSERVICE");

        $this->assertFalse($this->validator->validate());
    }

    public function test_ValidatorValidate()
    {
        $this->validator->newField("service");
        $this->validator->addFieldRule("service", new ServiceRule);
        $this->validator->setFieldData("service", "USER-SERVICE");

        $this->assertTrue($this->validator->validate());
    }
}

main();
