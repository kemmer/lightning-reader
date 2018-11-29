<?php

namespace UnityTest;

use ReflectionClass, ReflectionMethod;
use ErrorException, Exception;
use UnityTest\Assert\{AssertTrait, AssertException};
use UnityTest\Result\ResultFactory;

// error_reporting(0);


abstract class TestCase
{
    use AssertTrait;

    /* Store testing results */
    private array $results;

    /* This flag will tell results to provide a more expressive and detailed result output */
    private bool $verbose;

    /* Provides result handling */
    /**
     * TODO: Implement a result handler to remove direct ResultFactory dependency
     * and provide advanced configuration and better abstraction
     */
    private $resultsHandler;

    public function __construct()
    {
        $this->results = [];
        // $this->resultsHandler = $handler;

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
                    $status = true;
                    $this->$testableMethodName();

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

        // Providing the output of test results for the user
        foreach($this->results as $result) {
            // TODO: Use TimberLog, for both providing
            printf($result->output());
        }
    }
}
