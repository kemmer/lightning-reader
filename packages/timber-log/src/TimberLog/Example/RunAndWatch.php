<?php
namespace TimberLog\Example;

require __DIR__."/../../../vendor/autoload.php";
use TimberLog\Target\ConsoleLogger;
use TimberLog\Logger\LoggerInterface;
use TimberLog\Factory\LogFactory;
use TimberLog\Log\LogLevel;
use ReflectionClass;


/**
 * RunAndWatch
 *
 * Class intended to be executed and results observed
 * Logs are passed within array then all executed, accordingly
 * with desired output type
 */
class RunAndWatch
{
  private $logger;  // Logger mechanism
  private $logs;    // Array of Log objects to be tested

  public function __construct(LoggerInterface $sl, array $l)
  {
    $this->logger = $sl;
    $this->logs = $l;
  }

  public function test_output()
  {
    foreach($this->logs as $log) {
      $this->logger->output($log);
    }
  }
}

class SampleClass
{
  public function methodA()
  {
    echo "aaa";
  }

  public function methodB()
  {
    echo "bbb";
  }
}

function main()
{
  // An example class/method
  // $r_class = new ReflectionClass(new class {
  //     public function SampleMethod()
  //     {
  //         $aaa = 1;
  //         $bbb = 2;
  //     }
  // });
  // $r_method = $r_class->getMethod("SampleMethod");
  $r_class = new ReflectionClass(new SampleClass);
  $r_method = $r_class->getMethod("methodB");

  // Logs
  $logs = [];
  $logs [] = LogFactory::createSimple(LogLevel::ERROR_STR, "This is a test");
  $logs [] = LogFactory::createSimple(LogLevel::ERROR_STR, "This is another test");
  $logs [] = LogFactory::createSimple(LogLevel::INFO_STR, "This is whatever you wanna log");
  $logs [] = LogFactory::createSimple(LogLevel::INFO_STR, "This is... hold on... enough!");
  $logs [] = LogFactory::createReflection(LogLevel::INFO_STR, "Example of log using method details from reflection", $r_method);
  $logs [] = LogFactory::createReflection(LogLevel::WARNING_STR, "Another one, same method", $r_method);

  // Testing screen logging
  $chosen = new ConsoleLogger;
  $rnw = new RunAndWatch($chosen, $logs);
  $rnw->test_output();
}

main();
