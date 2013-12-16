Jackalope test
==============

A simple test run of jackalope with Doctrine DBAL.

Installation
------------

Install packages via composer:

    composer install

Update database connection and credentials in `cli-config.php`.

Import the schema:

    bin/jackalope jackalope:init:dbal

A simple command to play around with the API (TestCommand) can be run with:

    bin/jackalope chlu:jackalopetest:test
