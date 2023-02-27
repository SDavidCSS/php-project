<?php

require_once 'Record.php';
require_once 'Database.php';
require_once 'OfferProduct.php';
class Offer extends Record {
    public int $id = 0;
    public string $customer_name = '';
    public string $comment = '';
    public string $discount_percent = '';
    public string $offer_price = '';
    public string $discount_price = '';
    public string $product_amount = '';
    public string $valid_until = '';

    public array $offerProducts = [];

    public static function tableName(): string
    {
        return 'offer';
    }

    public function load(array $config)
    {
        parent::load($config);

        if(!$this->isNewRecord) $this->getOfferProducts();
    }

    public function attributes(): array
    {
        $attributes = ['customer_name', 'comment', 'discount_percent', 'offer_price', 'discount_price', 'product_amount', 'valid_until'];
        array_unshift($attributes, self::primaryKey());
        return $attributes;
    }

    public function beforeSave()
    {

    }

    public function getOfferProducts()
    {
        $sql = 'SELECT * FROM `offer_products` WHERE offer_id = :offer_id ;';
        $stmt = Database::getInstance()->prepare($sql);
        $stmt->execute([':offer_id' => $this->id]);
        $result = $stmt->fetchAll();

        foreach ($result as $op) {
            $offerProduct = new OfferProduct();
            $offerProduct->load($op);

            $this->offerProducts[] = $offerProduct;
        }
    }

    public static function getOfferInfo($id)
    {
        $db = Database::getInstance();

        $stmt = $db->prepare('SELECT * FROM `offer` WHERE id=:id');
        $stmt->execute([':id' => $id]);
        $data = [];

        $data['offer'] = $stmt->fetch();

        $stmt = $db->prepare('SELECT * FROM `offer_products` WHERE offer_id=:offer_id');
        $stmt->execute([':offer_id' => $id]);

        $data['offer_product'] = $stmt->fetchAll();

        foreach ($data['offer_product'] as $key => $d) {
            $stmt = $db->prepare('SELECT * FROM `product` WHERE id=:id');
            $stmt->execute([':id' => $d['product_id']]);

            $d['product'] = $stmt->fetch();
            $data['offer_product'][$key] = $d;
        }

        return $data;
    }
}
