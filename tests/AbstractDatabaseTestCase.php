<?php
abstract class AbstractDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    public final function getConnection()
    {
        $pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
        return $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
    }
}
