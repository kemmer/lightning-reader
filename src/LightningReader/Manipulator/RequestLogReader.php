<?php

namespace LightningReader\Manipulator;

use LightningReader\Manipulator\Exception\{SanitizeException, IncompleteLineException, ValidationException};

use LightningReader\Database\DatabaseInterface;
use LightningReader\Database\Operation\MultipleInsert;
use LightningReader\Database\Information\{RequestTable, RequestErrorTable};

use LightningReader\Validator\ValidatorInterface;
use LightningReader\Parser\{Tokenizer, Template};
use LightningReader\Data\RequestLog;

use Exception;


/**
 * RequestLogReader
 *
 * The entry point for the log insertion operation
 */
class RequestLogReader
{
    private $stream;            /* The input stream to be observed */
    private $connection;        /* Database connection to where we will insert the lines */
    private $validator;         /* For validating the input */
    private $tokenizer;         /* Bundle the input accordingly into desired tokens */

    private $templates;         /* Templates rules for parsing the input */
    private $requestLogs;       /* RequestLog collected from input */
    private $multipleInsert;    /* Helper responsible to insert multiple lines at once */

    public function __construct(
        $stream, DatabaseInterface $connection, ValidatorInterface $validator, Tokenizer $tokenizer)
    {
        $this->stream     = $stream;
        $this->connection = $connection;
        $this->validator  = $validator;
        $this->tokenizer  = $tokenizer;

        $this->multipleInsert = new MultipleInsert($this->connection);
    }

    /**
     * Set templates for specific fields. Those templates will be used
     * for separating the fields
     */
    private function configureTemplates()
    {
        $this->templates = [];
        $this->templates["service"] = new Template(null, " ");
        $this->templates["moment"] = new Template("[", "]");
        $this->templates["details"] = new Template("\"", "\"");
        $this->templates["http_code"] = new Template(" ", PHP_EOL);
    }

    /**
     * Inserts the RequestLog lines in the database. Designed to insert
     * multiple lines at once, adding the $file_info_id field to each
     * of the lines
     */
    private function insertLines()
    {
        if(empty($this->requestLogs))
            return;

        $fieldData_Array = [];
        foreach($this->requestLogs as $requestLog) {
            $data = $requestLog->toArray();
            array_unshift($data, '1');  // Addind the file_info_id
            $fieldData_Array [] = $data;
        }

        // Inserting multiple lines
        $this->multipleInsert->insert("request", RequestTable::fields(), $fieldData_Array);

        // Wipe our current logs to go for new ones
        $this->requestLogs = [];
    }

    /**
     * Starts reading the file.
     */
    public function start()
    {
        $this->configureTemplates();

        while(! feof($this->stream))
        {
            // Start reading an entry from the file
            $requestLog = new RequestLog($this->validator);

            try {
                // Templates are parsed in the order they are created
                foreach($this->templates as $field => $template) {
                    $requestLog->{$field} = $this->tokenizer->bundle($this->templates[$field]);
                }

                /**
                 * Checks whether the field is valid. It must attend the following
                 * three requirements.
                 *
                 * Otherwise, a corresponding exception will be thrown
                 */
                if(! $requestLog->complete())
                    throw new IncompleteLineException;

                if(! $requestLog->validate())
                    throw new ValidationException;

                if(! $requestLog->sanitize())
                    throw new SanitizeException;

                // Everything went ok, set the thing to be inserted
                $this->requestLogs [] = $requestLog;

            } catch(IncompleteLineException | ValidationException | SanitizeException $e) {
                RequestErrorTable::newError($this->connection, 1, 222, get_class($e), "");

            } catch(Exception $e) {
                RequestErrorTable::newError($this->connection, 1, 222, "Unknown", "");
            }

            // If we hit 20 entries, insert at once in database
            if(count($this->requestLogs) == 20)
                $this->insertLines();
        }

        // If we end, insert the valid lines that we have left
        $this->insertLines();
    }
}
