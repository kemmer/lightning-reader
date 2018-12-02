<?php

namespace LightningReader\Test;

require_once __DIR__."/../../../vendor/autoload.php";

use UnityTest\TestCase;
use TimberLog\Target\ConsoleLogger;
use LightningReader\Parser\Tokenizer;


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
        $this->text1  = "aa\\aa^aa bbbb ffkdk\ndd{]s";
        $this->text11 = "aa\\aa^aa bbbb ffkdk";
        $this->text111 = "aa\\aa^aa";
        $this->text2  = "sld   l".PHP_EOL."fgz\n kdf    dffggh";
        $this->text22 = "sld   l";
    }

    public function test_CanMakeTokenizer()
    {
        $this->tokenizer = new Tokenizer;
    }

    public function test_CanDetect_NewLine()
    {
        $target = "\n";
        $result = $this->tokenizer->compareNewLine($target);
        $this->assertTrue($result);
    }

    public function test_CanDetect_PHP_EOL()
    {
        $target = PHP_EOL;
        $result = $this->tokenizer->compareEOL($target);
        $this->assertTrue($result);
    }

    public function test_CanDetect_Char()
    {
        $target = "f";
        $result = $this->tokenizer->compare($target, "f");
        $this->assertTrue($result);
    }

    public function test_CanDetect_Char_Special()
    {
        $target = "`";
        $result = $this->tokenizer->compare($target, "`");
        $this->assertTrue($result);
    }

    // public function test_CanDetect_Multiple()
    // {
    // }

    public function test_ReadUntil_Newline()
    {
        $result = $this->tokenizer->readBlock($this->text1);
        $this->assertEquals($result, $this->text11);
    }

    public function test_ReadUntil_EOL()
    {
        $result = $this->tokenizer->readBlock($this->text2);
        $this->assertEquals($result, $this->text22);
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
