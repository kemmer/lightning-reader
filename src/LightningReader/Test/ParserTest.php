<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Parser\Tokenizer;
use LightningReader\Parser\Comparator;


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
        $delimiters = [
            "end" => " "
        ];

        $result = $this->tokenizer->bundle($stream, $delimiters);
        $this->assertEquals($result, "USER-SERVICE");
    }

    public function test_BundleSegment_ByDelimiters()
    {
        $stream = fopen("logs_short.log", "r");
        $delimiters = [
            "start" => "[",
            "end" => "]"
        ];

        $result = $this->tokenizer->bundle($stream, $delimiters);
        $this->assertEquals($result, "17/Aug/2018:09:21:53 +0000");
    }

    public function test_BundleSegment_ByDelimiters_Many()
    {
        $stream = fopen("logs_short.log", "r");

        $delimitersArray = [
            [
                "end" => " ",
                "result" => "USER-SERVICE"  // 'result' is just for testing
            ],
            [
                "start" => "[",
                "end" => "]",
                "result" => "17/Aug/2018:09:21:53 +0000"
            ],
            [
                "start" => "\"",
                "end" => "\"",
                "result" => "POST /users HTTP/1.1"
            ],
            [
                "start" => " ",
                "end" => PHP_EOL,
                "result" => "201"
            ],
            [
                "end" => " ",
                "result" => "USER-SERVICE"
            ],
            [
                "start" => "[",
                "end" => "]",
                "result" => "17/Aug/^2018:09:21:54 +0000"
            ],
        ];

        foreach($delimitersArray as $delimiters) {
            $result = $this->tokenizer->bundle($stream, $delimiters);
            $this->assertEquals($result, $delimiters["result"]);
        }
    }
}

main();
