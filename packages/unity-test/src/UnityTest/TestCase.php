<?php

namespace UnityTest;

use ReflectionClass, ReflectionMethod;
use ErrorException, Exception;

use UnityTest\Assert\{AssertTrait, AssertException};
use UnityTest\Result\ResultFactory;
use UnityTest\Result\ResultLog;

use TimberLog\Logger\LoggerInterface;

/**
 * TestCase
 *
 * Base class for testing our stuff
 * Every new class agregating lots of tests must extend
 * this class and follow these rules:
 *
 * - Choose and create a LoggerInterface and inject in when creating
 * - Override configure() for creating objects for injection, adjust parameters, etc
 * - Override configure() and set $verbose flag on it in case you want it
 * - Implements new methods prepended with 'test_' that are intended to be tested, with
 * protected as minimum level of visibility
 * - Calls performTesting() on the instance to start testing
 */
abstract class TestCase
{
    use AssertTrait;

    private $results;     /* Store testing results */
    private $logHandler;  /* Provides result log handling */
    protected $verbose;   /* This flag will tell results to provide a more expressive and detailed result output */

    public function __construct(LoggerInterface $logHandler)
    {
        $this->logHandler = $logHandler;
        $this->results = [];
        $this->verbose = false;

        /**
         * Many errors thrown by the system will be converted into an exception.
         * allowing its use as a test result (error), which is different from
         * failing an assertion.
         *
         * This won't work for fatal errors (like undefined classes or methods, or
         * syntax errors). But, for errors like executing "strpos()" (no parameters)
         * this will work as expected.
         *
         * We're passing a static callable of an static method in the parameter
         */
        set_error_handler([ResultFactory::class, 'convertErrorToException']);
    }

    /**
     * Used to configure the context before initiating the tests
     */
    protected function configure()
    {
        // Default configs for running the tests
        // Always call parent::configure(...) when overriding
    }

    /**
     * Will execute the flagged methods and observe for results
     * After that, will output to the user
     */
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

    /**
     * Output results currently hosted in $this->results
     */
    private function outputResults()
    {
        if(empty($this->results))
            return;

        // Custom configurations for output
        $this->logHandler->enableTimestamp(false);
        $this->logHandler->enableLevel($this->verbose);
        $this->logHandler->enableNewLine($this->verbose);

        // This holds all results that failed, to be exibited after
        // regular output, when NOT in verbose mode
        $errors = [];

        // Providing the output of test results for the user
        foreach($this->results as $result) {
            $this->logHandler->output(new ResultLog($result, $this->verbose));

            if(! $this->verbose && ! $result->wasSuccess()) {
                $errors [] = $result;
            }
        }

        $this->logHandler->finish();

        // When the output is not verbose we still need to show unsuccessful test results to the user
        if(! $this->verbose) {
            $this->logHandler->enableLevel(true);
            $this->logHandler->enableNewLine(true);

            foreach($errors as $result) {
                $this->logHandler->output(new ResultLog($result, true));
            }

            $this->logHandler->finish();
        }

    }
}
