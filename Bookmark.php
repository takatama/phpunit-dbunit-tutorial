<?php
class Bookmark
{
    private $id;
    private $url;
    private $account;
    private $created;

    public function __construct($id, $url, $account, $created)
    {
        $this->id = $id;
        $this->url = $url;
        $this->account = $account;
        $this->created = $created;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getCreated()
    {
        return $this->created;
    }
}
