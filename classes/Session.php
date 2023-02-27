<?php

class Session
{
    public static $instance;
    public function __construct()
    {

    }

    public static function getInstance()
    {
        if(!isset($_SESSION)) session_start();
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function set(array $data) {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    public function get($key) {
        return $_SESSION[$key] ?? false;
    }

    public function destroy()
    {
        session_destroy();
    }

}