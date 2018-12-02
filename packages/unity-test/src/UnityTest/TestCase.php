<?php

namespace UnityTest;

use ReflectionClass, ReflectionMethod;
use ErrorException, Exception;

use UnityTest\Assert\{AssertTrait, AssertException};
use UnityTest\Result\ResultFactory;
use UnityTest\Result\ResultLog;

use TimberLog\Logger\LoggerInterface;

// error_reporting(0);


abstract class TestCase
{
    use AssertTrait;

    /* Store testing results */
    private $results;

    /* This flag will tell results to provide a more expressive and detailed result output */
    protected $verbose;

    /* Provides result log handling */
    private $logHandler;

    public function __construct(LoggerInterface $logHandler)
    {
        $this->results = [];
        $this->verbose = false;
        $this->logHandler = $logHandler;

        set_error_handler([ResultFactory::class, 'convertErrorToException']);
    }

    protected function configure()
    {
        // Default configs for running the tests
        // Always call parent::configure(...) when overriding
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
        // Custom configurations for output
        $this->logHandler->enableTimestamp(false);
        $this->logHandler->enableLevel($this->verbose);
        $this->logHandler->enableNewLine($this->verbose);

        // Providing the output of test results for the user
        foreach($this->results as $result) {
            $this->logHandler->output(new ResultLog($result, $this->verbose));
        }
    }
}
