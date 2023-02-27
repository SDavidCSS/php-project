<?php

class Database extends PDO
{
    private static PDO $instance;

    public function __construct(array $config)
    {
        $dsn = sprintf("mysql:host=%s;dbname=%s", $config['host'], $config['dbname']);
        try {
            parent::__construct($dsn, $config['user'], $config['password']);
        } catch (\PDOException $e) {
            echo "MySql Connection Error: " . $e->getMessage();
        }

        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance():PDO
    {
        $config = [
            'host' => 'localhost',
            'dbname' => 'tcom',
            'user' => 'root',
            'password' => ''
        ];

        if(!isset(self::$instance)) {
            self::$instance = new self($config);
        }

        return self::$instance;
    }
}