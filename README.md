# Lightning Reader

This is a simple server-side API intended to consume a log file and store in a database. The system will also be able to query some information about the records stored through HTTP requests.

# How to test it
- Install the SQL structure specified in the database/create.sql. I am using MySQL/MariaDB.
- Create a .env file with your MySQL configurations (following .env.example)
- Head to src/LightningReader/Example/Example.php
- Change the $filename var in the script accordingly
- Execute it in the command-line "php Example.php"
- To check the results, go to the projects root, run the PHP build-in webserver and calls /matrix both (accepts GET and POST). The example filters are the ones specified in the project's email
- Also, if you wish to inspect the database, run (use a `WHERE` clause):
  - `SELECT * FROM request_errors;` to see the errors catch in the log file
  - `SELECT * FROM request;` to see everything valid from the log file
  - `SELECT * FROM file_info;` if you wish to see the files you read so far

# A few brief notes
- Errors are being reported in the database as soon as they appear, so much errors can decrease your performance
- In the DB, logs are identified by `file_info_id` (which is unique for each filename)
- I assumed empty lines anywhere in the log file are invalid, so that is being reported
- I assumed the time is in UTC for all log lines as the example did not show something different
- The logs are being inserted by 20 at once in the database. Grouping queries may significantly improve performance, so this number may be fine-tuned if requested.
- The read is not using buffers, bringing more data to the memory where it is faster so that can be improved, but it will consume more memory depending on its size. I do not know if this implementation is performant enought for the specification, so it may be improved if requested.

# Project structure

## Introduction
This project was built entirely from scratch. I only used PHP 7.1 and Composer. The ideia is to read an input log file of an specific format into a database and let it available for systems to query information about it.

It is not meant to be production-ready or used by people that are unaware of the project's specification.

## Folder Structure
### `database/`
Holds all data related to database creation with SQL

### `packages/`
Every packaged bundled with composer external from project's namespace will be here. Folders here inside are being add to the project repository manually in the `composer.json`

### `src/`
Main project folder

## Core namespaces
