<?php
require_once(dirname(__FILE__) . '/YamlTestCase.php');
require_once(dirname(__FILE__) . '/../Account.php');

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

    private function getFixtureRow($table, $index)
    {
        $fixtureTable = $this->getConnection()->createDataSet()->getTable($table);
        return $fixtureTable->getRow($index);
    }

    public function testFind()
    {
        $row = $this->getFixtureRow('account', 0);
        $id = $row['id'];
        $name = $row['name'];

        $account = Account::find($id);
        $this->assertEquals($id, $account->getId());
        $this->assertEquals($name, $account->getName());
    }

    public function testSave()
    {
        $name = 'ms. test';
        $account = new Account($name);
        $this->assertEquals($name, $account->getName());

        $this->assertEquals(1, $this->getConnection()->getRowCount('account'));

        $account->save();
        $this->assertEquals(2, $this->getConnection()->getRowCount('account'));
    }

    public function testUpdate()
    {
        $row = $this->getFixtureRow('account', 0);
        $id = $row['id'];
        $name = $row['name'];

        $account = Account::find($id);
        $this->assertEquals($id, $account->getId());
        $this->assertEquals($name, $account->getName());

        $newName = 'The new';
        $account->setName($newName);
        $account->save();

        $account = Account::find($id);
        $this->assertEquals($id, $account->getId());
        $this->assertEquals($newName, $account->getName());
    }

    public function testDestroy()
    {
        $row = $this->getFixtureRow('account', 0);
        $id = $row['id'];
        $account = Account::find($id);
        $this->assertEquals(1, $this->getConnection()->getRowCount('account'));
        $account->destroy();
        $this->assertEquals(0, $this->getConnection()->getRowCount('account'));
    }

    public function testAddBookmark()
    {
        $row = $this->getFixtureRow('account', 0);
        $id = $row['id'];
        $account = Account::find($id);

        $this->assertEquals(1, $this->getConnection()->getRowCount('bookmark'));
        $url = 'http://example.com';
        $account->addBookmark($url);
        $this->assertEquals(2, $this->getConnection()->getRowCount('bookmark'));
    }

    public function testFindBookmarks()
    {
        $row = $this->getFixtureRow('account', 0);
        $id = $row['id'];
        $account = Account::find($id);
        $bookmarks = $account->findBookmarks();

        $bookmarkRow = $this->getFixtureRow('bookmark', 0);
        $this->assertEquals(1, count($bookmarks));
        $this->assertEquals($bookmarkRow['id'], $bookmarks[0]->getId());
        $this->assertEquals($bookmarkRow['url'], $bookmarks[0]->getUrl());
    }
}
