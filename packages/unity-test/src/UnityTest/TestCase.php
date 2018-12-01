<?php

namespace UnityTest;

use ReflectionClass, ReflectionMethod;
use ErrorException, Exception;

use UnityTest\Assert\{AssertTrait, AssertException};
use UnityTest\Result\ResultFactory;

use TimberLog\Log\LogFactory;
use TimberLog\Logger\LoggerInterface;

// error_reporting(0);


abstract class TestCase
{
    use AssertTrait;

    /* Store testing results */
    private $results;

    /* This flag will tell results to provide a more expressive and detailed result output */
    private $verbose;

    /* Provides result log handling */
    private $logHandler;

    public function __construct(LoggerInterface $handler)
    {
        $this->results = [];
        $this->logHandler = $handler;

        set_error_handler([ResultFactory::class, 'convertErrorToException']);
    }

    protected function configure()
    {
        // ...
    }

    final public function performTesting()
    {
        // Configure required dependencies
        $this->configure();

        // Selecting and running the methods intended for testing
        $rClass = new ReflectionClass($this);

        foreach($rClass->getMethods() as $rMethod)
        {
            $testableMethodName = $rMethod->getName();
            // Every method intended to be executed should start with 'test_' prefix
            if(strlen($testableMethodName) > 5 && substr($testableMethodName, 0, 5) == 'test_') {

                $status = false;
                $exceptionThrown = null;

                try {
                    $this->$testableMethodName();
                    $status = true;

                } catch(AssertException $e) {
                    // An assertion has failed. Report as assertion failure.
                    $exceptionThrown = $e;

                } catch(ErrorException $e) {
                    // An error was thrown and converted for exception. Report as an error.
                    $exceptionThrown = $e;

                } catch(Exception $e) {
                    // Some other exception have been catch. Report as an error.
                    $exceptionThrown = $e;

                } finally {
                    $args = [$rMethod, $exceptionThrown];
                    if($status == true)
                        $this->results [] = ResultFactory::createSuccess(...$args);
                    else
                        $this->results [] = ResultFactory::createFailure(...$args);
                }

                /**
                 * In all exceptional cases the test has indeed failed.
                 * They differ, however, just in the form it is reported.
                 * This is intended to help user understanding where the
                 * problem lies.
                 */
            }
        }

        $this->outputResults();
    }

    private function outputResults()
    {
        // Providing the output of test results for the user
        foreach($this->results as $result) {
            if($result->failed())
                $this->logHandler->error(LogFactory::createPlain($result->output()));
            else
                $this->logHandler->info(LogFactory::createPlain($result->output()));
        }
    }
}
