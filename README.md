# Lightning Reader

This is a simple server-side API intended to consume a log file and store in a database. The system will also be able to query some information about the records stored through HTTP requests.

# How to use it
- Install the SQL structure specified in the database/create.sql. I am using MySQL/MariaDB.
- Create a .env file with your MySQL configurations (following .env.example)
- Head to src/LightningReader/Example/Example.php
- Change the filename in the script accordingly
- Execute it in the command-line "php Example.php"
- To check the results, go to the projects root, run the PHP build-in webserver and calls /matrix both (accepts GET and POST). The example filters are the ones specified in the project's email.

