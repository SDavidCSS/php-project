<?php

require_once 'Record.php';
require_once 'Product.php';

class OfferProduct extends Record {
    public int $id = 0;
    public string $product_id = '';
    public string $offer_id = '';
    public string $amount = '';
    public string $total_price = '';

    public $product = null;

    public static function tableName(): string
    {
        return 'offer_products';
    }

    public function attributes(): array
    {
        $attributes = ['product_id', 'offer_id', 'amount', 'total_price'];
        array_unshift($attributes, self::primaryKey());
        return $attributes;
    }

    public function load(array $config)
    {
        parent::load($config);

        if(!$this->isNewRecord) {
            $this->product = Product::findOne($this->product_id);
        }
    }

    public function beforeSave()
    {

    }
}
