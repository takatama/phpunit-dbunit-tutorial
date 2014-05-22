<?php
require_once(dirname(__FILE__) . '/Bookmark.php');

class Account
{
    private static $pdo = null;
    private $id;
    private $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    private static function pdo()
    {
        if (self::$pdo === null) {
            $dsn = 'mysql:dbname=tutorial;host=localhost';
            $user = 'dbunit';
            $pass = '';
            self::$pdo = new PDO($dsn, $user, $pass);
            self::$pdo->query('SET NAMES utf8');
        }
        return self::$pdo;
    }

    public static function find($id)
    {
        $sql = 'SELECT id, name FROM account WHERE id = :id';
        $stmt = self::pdo()->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $account = new Account($row['name']);
        $account->id = $row['id'];
        return $account;
    }

    public function save()
    {
        $sql = 'INSERT INTO account (name) VALUES (:name)';
        if ($this->id !== null) {
            $sql = 'UPDATE account SET name = :name WHERE id = :id';
        }
        $stmt = $this->pdo()->prepare($sql);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        if ($this->id !== null) {
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        }
        $stmt->execute();
    }

   public function destroy()
   {
        $sql = 'DELETE from account WHERE id = :id';
        $stmt = self::pdo()->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
   }

   public function addBookmark($url)
   {
        if ($this->id === null) {
            return;
        }
        $sql = 'INSERT INTO bookmark (url, account_id) VALUES (:url, :account_id)';
        $stmt = self::pdo()->prepare($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':account_id', $this->id, PDO::PARAM_STR);
        $stmt->execute();
   }

   private function createBookmarks($rows)
   {
        $bookmarks = array();
        foreach($rows as $row) {
            $bookmark = new Bookmark($row['id'], $row['url'], $this, $row['created']); 
            $bookmarks[] = $bookmark;
        }
        return $bookmarks;
   }

   public function findBookmarks()
   {
        $sql = 'SELECT id, url, created FROM bookmark WHERE account_id = :id';
        $stmt = self::pdo()->prepare($sql);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->createBookmarks($stmt->fetchAll(PDO::FETCH_ASSOC));
   }
}

