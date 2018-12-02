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

    protected function configure()
    {
        parent::configure();

        $this->tokenizer = new Tokenizer;
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

    public function test_BundleUntil_EOL()
    {
        $text = "sld   l".PHP_EOL."fgz\n kdf    dffggh";
        $result = $this->tokenizer->bundle($text);
        $this->assertEquals($result, "sld   l");
    }

    public function test_BundleUntil_Char()
    {
        $target = "]";
        $text = "aa\\a]a^aa bbbb ffkdk";
        $result = $this->tokenizer->bundle($text, $target);
        $this->assertEquals($result, "aa\\a");
    }

    public function test_BundleUntil_Char_NoEOLStop()
    {
        $target = "]";
        $text = "aa\\".PHP_EOL."aa^a]a bb[bb ffkdk\ndd{s";
        $result = $this->tokenizer->bundle($text, $target, false);
        $this->assertEquals($result, "aa\\".PHP_EOL."aa^a");
    }

    // public function test_ReadFirstToken_BySeparator()
    // {
    //     $separator = " ";
    //     $result = $this->tokenizer->readBlock($this->text11, $separator);
    //     $this->assertEquals($result, $this->text111);
    // }

    // public function test_ReadOneByOne()
    // {
    //     $result = $this->tokenizer->read($this->text1);
    //     $this->assertEquals($this->text1, $result);
    // }

    // public function test_StopAt_Space()
    // {
    //     $result = $this->tokenizer->readUntil($this->text1);
    //     $this->assertEquals($result, $this->text11);
    // }

    // public function test_StopAt_NewLine()
    // {
    //     $result = $this->tokenizer->readUntil($this->text2);
    //     $this->assertEquals($result, $this->text22);
    // }

    // public function test_TellWhenFileEnds_File()
    // {
    // }
}

main();
