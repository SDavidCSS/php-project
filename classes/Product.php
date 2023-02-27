<?php

require_once 'Record.php';

class Product extends Record {
    public int $id = 0;
    public string $sku = '';
    public string $ean = '';
    public string $name = '';
    public string $shortDesc = '';
    public string $manufacturer = '';
    public string $price = '';
    public string $stock = '';

    public static function tableName(): string
    {
        return 'product';
    }

    public function attributes(): array
    {
        $attributes = ['sku', 'ean', 'name', 'shortDesc', 'manufacturer', 'price', 'stock'];
        array_unshift($attributes, self::primaryKey());
        return $attributes;
    }

    public function beforeSave()
    {

    }
}
