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
    private $text1, $text11, $text111;
    private $text2, $text22;

    protected function configure()
    {
        parent::configure();

        $this->tokenizer = new Tokenizer;

        $this->text1    = "aa\\".PHP_EOL."aa^a]a bb[bb ffkdk\ndd{s";
        $this->text1111 = "aa\\".PHP_EOL."aa^a";
        $this->text11   = "aa\\a]a^aa bbbb ffkdk";
        $this->text1112 = "aa\\a";
        $this->text111  = "aa\\a]a^aa";
        $this->text2    = "sld   l".PHP_EOL."fgz\n kdf    dffggh";
        $this->text22   = "sld   l";
        $this->text222  = "sld";
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
        /**
         * The bundle should read until EOL by default
         */
        $result = $this->tokenizer->bundle($this->text2);
        $this->assertEquals($result, $this->text22);
    }

    public function test_BundleUntil_Char()
    {
        $target = "]";
        $result = $this->tokenizer->bundle($this->text11, $target);
        $this->assertEquals($result, $this->text1112);
    }

    public function test_BundleUntil_Char_NoEOLStop()
    {
        $target = "]";
        $result = $this->tokenizer->bundle($this->text1, $target, false);
        $this->assertEquals($result, $this->text1111);
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
