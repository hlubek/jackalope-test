<?php

/* bootstrapping the repository implementation */

/*
 * configuration
 */
$workspace  = 'default'; // phpcr workspace to use
$user       = 'root';   // RDBMS user
$pass       = '';   // RDBMS password

/** for jackalope - doctrine dbal, do this: */
function bootstrapDoctrineDbal()
{
    global $repository, $user, $pass, $dbConn;

    /* additional doctrine dbal configuration
     * For further details, please see Doctrine configuration page.
     * http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connection-details
     */
    $driver   = 'pdo_mysql';
    $host     = 'localhost';
    $database = 'jackalope_test';

    // Bootstrap Doctrine
    $dbConn = \Doctrine\DBAL\DriverManager::getConnection(array(
        'driver'    => $driver,
        'host'      => $host,
        'user'      => $user,
        'password'  => $pass,
        'dbname'    => $database,
    ));
    $factory = new \Jackalope\RepositoryFactoryDoctrineDBAL();
    $repository = $factory->getRepository(array('jackalope.doctrine_dbal_connection' => $dbConn));
}

bootstrapDoctrineDbal();

$credentials = new \PHPCR\SimpleCredentials($user, $pass);

/* only create a session if this is not about the server control command */
if (isset($argv[1])
    && $argv[1] != 'jackalope:init:dbal'
    && $argv[1] != 'list'
    && $argv[1] != 'help'
) {
    $session = $repository->login($credentials, $workspace);

    $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
        'session' => new \PHPCR\Util\Console\Helper\PhpcrHelper($session)
    ));
} else if (isset($argv[1]) && $argv[1] == 'jackalope:init:dbal') {
    // special case: the init command needs the db connection, but a session is impossible if the db is not yet initialized
    $helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
        'connection' => new \Jackalope\Tools\Console\Helper\DoctrineDbalHelper($dbConn)
    ));
}
