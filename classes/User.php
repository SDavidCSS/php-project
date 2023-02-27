<?php

require_once 'Record.php';
require_once 'Session.php';

class User extends Record {
    public int $id = 0;
    public string $username = '';
    public string $password = '';
    public string $email = '';

    public static function tableName(): string
    {
        return 'user';
    }

    public function attributes(): array
    {
        $attributes = ['username', 'password', 'email'];
        array_unshift($attributes, self::primaryKey());
        return $attributes;
    }

    public function verifyPassword($password): bool
    {
        return password_verify($password, $this->password);
    }

    public function login()
    {
        $session = Session::getInstance();
        $session->set([
            'loggedIn' => true,
            'username' => $this->username,
            'id' => $this->id,
        ]);

        header('Location: index.php');
    }

    public function beforeSave()
    {

    }
}
