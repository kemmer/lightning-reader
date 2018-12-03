<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Parser\Tokenizer;
use LightningReader\Parser\Comparator;
use LightningReader\Parser\Template;


function main()
{
    $parserTest = new ParserTest(new ConsoleLogger);
    $parserTest->performTesting();
}

class ParserTest extends TestCase
{
    private $tokenizer;
    private $textLog_Line1;

    protected function configure()
    {
        parent::configure();

        $this->tokenizer = new Tokenizer;
        $this->textLog_Line1 = "USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] \"POST /users HTTP/1.1\" 201";
    }

    public function test_CanMakeTokenizer()
    {
        $this->tokenizer = new Tokenizer;
    }

    public function test_Detect_Char()
    {
        $target = "f";
        $result = Comparator::compareSingle($target, "f");
        $this->assertTrue($result);
    }

    public function test_Detect_Char_Special()
    {
        $target = "`";
        $result = Comparator::compareSingle($target, "`");
        $this->assertTrue($result);
    }

    public function test_Detect_Space()
    {
        $target = " ";
        $result = Comparator::compareSingle($target, " ");
        $this->assertTrue($result);
    }

    public function test_Detect_PHP_EOL()
    {
        $target = PHP_EOL;
        $result = Comparator::compareSingle($target, PHP_EOL);
        $this->assertTrue($result);
    }

    public function test_Detect_Multiple()
    {
        $target = "k";
        $compare = [
            PHP_EOL,
            "a",
            "k",
            "v"
        ];
        $result = Comparator::compare($target, $compare);
        $this->assertTrue($result);
    }

    public function test_DetectFail_Multiple()
    {
        $target = "ç";
        $compare = [
            PHP_EOL,
            "a",
            "k",
            "v"
        ];
        $result = Comparator::compare($target, $compare);
        $this->assertFalse($result);
    }

    public function test_SwallowUntil_EOL()
    {
        $stream = fopen("logs_short.log", "r");
        $result = $this->tokenizer->swallow($stream);

        $this->assertEquals($result, $this->textLog_Line1);
    }

    public function test_SwallowUntil_Char()
    {
        $stream = fopen("logs_short.log", "r");
        $target = "]";
        $result = $this->tokenizer->swallow($stream, $target);

        $this->assertEquals($result, "USER-SERVICE - - [17/Aug/2018:09:21:53 +0000");
    }

    public function test_SwallowUntil_Char_NoEOLStop()
    {
        $stream = fopen("logs_short.log", "r");
        $target = "^";
        $result = $this->tokenizer->swallow($stream, $target, false);

        $this->assertEquals($result, "USER-SERVICE - - [17/Aug/2018:09:21:53 +0000] \"POST /users HTTP/1.1\" 201
USER-SERVICE - - [17/Aug/");
    }

    public function test_BundleSegment_ByDelimiters_End()
    {
        $stream = fopen("logs_short.log", "r");
        $template = new Template(null, " ");

        $result = $this->tokenizer->bundle($stream, $template);
        $this->assertEquals($result, "USER-SERVICE");
    }

    public function test_BundleSegment_ByDelimiters()
    {
        $stream = fopen("logs_short.log", "r");
        $template = new Template("[", "]");

        $result = $this->tokenizer->bundle($stream, $template);
        $this->assertEquals($result, "17/Aug/2018:09:21:53 +0000");
    }

    public function test_BundleSegment_ByDelimiters_Many()
    {
        $stream = fopen("logs_short.log", "r");

        $templateArray = [];
        $templateArray [] = new Template(null, " ");
        $templateArray [] = new Template("[", "]");
        $templateArray [] = new Template("\"", "\"");
        $templateArray [] = new Template(" ", PHP_EOL);
        $templateArray [] = new Template(null, " ");
        $templateArray [] = new Template("[", "]");

        $expected = [
            "USER-SERVICE",
            "17/Aug/2018:09:21:53 +0000",
            "POST /users HTTP/1.1",
            "201",
            "USER-SERVICE",
            "17/Aug/^2018:09:21:54 +0000",
        ];
        $ex = 0;

        foreach($templateArray as $template) {
            $result = $this->tokenizer->bundle($stream, $template);
            $this->assertEquals($result, $expected[$ex]);
            $ex++;
        }
    }
}

main();
