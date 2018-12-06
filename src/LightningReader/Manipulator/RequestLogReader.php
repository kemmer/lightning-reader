<?php

namespace LightningReader\Manipulator;

use LightningReader\Manipulator\Exception\{SanitizeException, IncompleteLineException, ValidationException};
use LightningReader\Manipulator\FileInfoInterface;

use LightningReader\Database\DatabaseInterface;
use LightningReader\Database\Operation\MultipleInsert;
use LightningReader\Database\Information\{RequestTable, RequestErrorTable, FileInfoTable};

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
    private $file;              /* Information about the file */
    private $fileInfoID;        /* The ID of the file in file_info table */
    private $connection;        /* Database connection to where we will insert the lines */
    private $validator;         /* For validating the input */
    private $tokenizer;         /* Bundle the input accordingly into desired tokens */

    private $templates;         /* Templates rules for parsing the input */
    private $requestLogs;       /* RequestLog collected from input */
    private $multipleInsert;    /* Helper responsible to insert multiple lines at once */

    public function __construct(
        FileInfoInterface $file, DatabaseInterface $connection, ValidatorInterface $validator, Tokenizer $tokenizer)
    {
        $this->file       = $file;
        $this->connection = $connection;
        $this->validator  = $validator;
        $this->tokenizer  = $tokenizer;

        $this->multipleInsert = new MultipleInsert($this->connection);

        $this->templates = [];
        $this->fileInfoID = null;
    }

    /**
     * Looks for a filename match in database and returns
     * the created or new ID
     */
    private function configureFile()
    {
        // Obtains the file_info_id for the opened file
        $this->fileInfoID = FileInfoTable::openOrRecover($this->connection, $this->file->filePath());
    }

    /**
     * Set templates for specific fields. Those templates will be used
     * for separating the fields
     */
    private function configureTemplates()
    {
        $this->templates = [];
        $this->templates["service"]   = new Template(null, " ");
        $this->templates["moment"]    = new Template("[", "]");
        $this->templates["details"]   = new Template("\"", "\"");
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
            array_unshift($data, $this->fileInfoID);  // Adding the file_info_id
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
        $this->configureFile();

        while(! feof($this->file->stream()))
        {
            // We are now inspecting this line. Advance the tracker over it.
            // We don't care about success or failure to insert this line. Just
            // need to know that this is some line in the file.
            $this->file->lineTracker()->advance();

            // Start reading an entry from the file
            $requestLog = new RequestLog($this->validator);

            // Clears the audit buffer, allowing us to get fresh data from this line
            // (auditBuffer option must be enabled in $tokenizer for this to work)
            $this->tokenizer->clearAuditBuffer();

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
                RequestErrorTable::newError(
                    $this->connection,
                    $this->fileInfoID,
                    $this->file->lineTracker()->current(),
                    get_class($e),
                    $this->tokenizer->getAuditBuffer());
                $this->file->lineTracker()->newError();

            } catch(Exception $e) {
                RequestErrorTable::newError(
                    $this->connection,
                    $this->fileInfoID,
                    $this->file->lineTracker()->current(),
                    $e->getMessage(),
                    $this->tokenizer->getAuditBuffer());
                $this->file->lineTracker()->newError();
            }

            // If we hit 20 entries, insert at once in database
            if(count($this->requestLogs) == 20) {
                $this->insertLines();
                $this->file->lineTracker()->newSuccess(20);
            }
        }

        // If we end, insert the valid lines that we have left
        $remainingLines_Count = count($this->requestLogs);
        $this->insertLines();
        $this->file->lineTracker()->newSuccess($remainingLines_Count);
    }
}
