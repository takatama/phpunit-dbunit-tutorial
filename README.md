# Tutorial for phpunit/dbunit

based on http://phpunit.de/manual/3.7/en/database.html

## Setup

composer.json
'''
{
    "require-dev": {
    "phpunit/phpunit": "3.7.*",
	    "phpunit/dbunit": "1.2.*"
    }
}
'''

## Supposed database schema

tutorial.sql
'''
CREATE DATABASE IF NOT EXISTS tutorial DEFAULT CHARACTER SET utf8;
CREATE USER 'dbunit'@'localhost';
GRANT ALL ON tutorial.* to 'dbunit'@'localhost';

CREATE TABLE IF NOT EXISTS tutorial.account (
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(100),
	PRIMARY KEY (id)
	);

CREATE TABLE IF NOT EXISTS tutorial.bookmark (
	id INT NOT NULL AUTO_INCREMENT,
	url VARCHAR(100),
	account_id INT,
	created TIMESTAMP DEFAULT NOW(),
	PRIMARY KEY (id)
	);
'''

## Configuration of phpunit

tests/phpunit.xml

'''
<?xml version="1.0" encoding="UTF-8" ?>
<phpunit>
<php>
<var name="DB_DSN" value="mysql:dbname=tutorial;host=localhost" />
<var name="DB_USER" value="dbunit" />
<var name="DB_PASSWORD" value="" />
<var name="DB_DBNAME" value="tutorial" />
<var name="YAML_FILE" value="tutorial.yaml" />
</php>
</phpunit>
'''

## Fixture

tests/fixtures/tutorial.yaml

'''

account:
  -
      id: 1
          name: "helloworld"
	   
	   bookmark:
	     -
	         id: 1
		     account_id: 1
		         url: "http://biglobe.ne.jp/"
			     created: "2014-05-22 18:14:00"
'''

## Test cases

tests/AbstractDatabaseTestCase.php
'''
<?php
abstract class AbstractDatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    public final function getConnection()
        {
	        $pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWORD']);
		        return $this->createDefaultDBConnection($pdo, $GLOBALS['DB_DBNAME']);
			    }
			    }
'''

tests/YamlTestCase.php
'''
<?php
require_once('AbstractDatabaseTestCase.php');
 
 class YamlTestCase extends AbstractDatabaseTestCase
 {
     protected function getDataSet()
         {
	        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
		           dirname(__FILE__) . '/fixtures/' . $GLOBALS['YAML_FILE']
			          );
				      }
				      }
'''

tests/AccountTest.php
'''
<?php
class AccountTest extends YamlTestCase
{
    public function testQueryTable()
        {
	        $queryTable = $this->getConnection()->createQueryTable('account', 'SELECT * FROM account');
		        $expectedTable = $this->getConnection()->createDataSet()->getTable('account');
			        $this->assertTablesEqual($expectedTable, $queryTable);
				    }
				     
				         public function testRowCount()
					     {
					             $this->assertEquals(1, $this->getConnection()->getRowCount('account'));
						             $this->assertEquals(1, $this->getConnection()->getRowCount('bookmark'));
							         }
								 }
'''

## Test if yaml fixture is set correctly
$ vendor/bin/phpunit --configuration tests/phpunit tests
or
$ vendor/bin/phpunit -c tests/phpunit tests
or
$ vendor/bin/phpunit -c tests/phpunit tests/AccountTest.php

## Create test case and implementation
tests/AccountTest.php
Account.php
Bookmark.php

