<?php

require_once 'Record.php';

class Product extends Record {
    public int $id = 0;
    public string $name = '';
    public string $image = '';
    public string $price = '';

    public static function tableName(): string
    {
        return 'product';
    }

    public function attributes(): array
    {
        $attributes = ['name', 'image', 'price'];
        array_unshift($attributes, self::primaryKey());
        return $attributes;
    }

    public function beforeSave()
    {

    }
}
