<?php
namespace TimberLog\Test;

require __DIR__."/../../../vendor/autoload.php";
use TimberLog\Target\ScreenLogger;
use TimberLog\Logger\{LoggerInterface, Log};


/**
 * RunAndWatch
 *
 * Class intended to be executed and results observed
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

  public function test_error()
  {
    foreach($this->logs as $log) {
      $this->logger->error($log);
    }
  }

  public function test_warning()
  {
    foreach($this->logs as $log) {
      $this->logger->warning($log);
    }
  }

  public function test_info()
  {
    foreach($this->logs as $log) {
      $this->logger->info($log);
    }
  }
}

function main()
{
  // An example class/method
  $r_class = new \ReflectionClass(new class {
      public function SampleMethod()
      {
          $aaa = 1;
          $bbb = 2;
      }
  });
  $r_method = $r_class->getMethod("SampleMethod");

  // Logs
  $logs = [];
  $logs [] =  new Log($r_method, "This is a test");
  $logs [] = new Log($r_method, "This is another test");
  $logs [] = new Log($r_method, "This is whatever you wanna log");
  $logs [] = new Log($r_method, "This is... hold on... enough!");

  // Testing screen logging
  $chosen = new ScreenLogger;
  $rnw = new RunAndWatch($chosen, $logs);
  $rnw->test_error();
  $rnw->test_warning();
  $rnw->test_info();
}

main();
