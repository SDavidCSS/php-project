<?php

include_once './classes/Database.php';

if(isset($_POST['Offer'])) {

    $isUpdate = isset($_POST['is_update']) && $_POST['is_update'] == 1;
    $attributes = ['valid_until', 'customer_name', 'comment', 'discount_percent', 'offer_price', 'discount_price', 'product_amount'];
    $productAttributes = ['product_id', 'offer_id', 'amount', 'total_price'];

    $offer = $_POST['Offer'];
    $products = $_POST['OfferProduct'];
    $data = [];

    foreach ($attributes as $attribute) {
        $data[$attribute] = trim(strip_tags($offer[$attribute]));
    }

    $db = Database::getInstance();

    $fields = implode(', ', $attributes);
    $values = implode(', ', array_map(fn($item) => ":$item", $attributes));

    if(!$isUpdate) {
        $sql = "INSERT INTO `offer` ($fields) VALUES ($values);";
    } else {
        $updates = implode(', ', array_map(fn($item) => "$item=:$item", $attributes));
        $sql = "UPDATE `offer` SET $updates WHERE id=:id;";
    }
    $stmt = $db->prepare($sql);

    foreach ($attributes as $attribute) {
        $stmt->bindValue(":$attribute", $data[$attribute]);
    }

//    If updating, offer id will be specified in the POST
    if($isUpdate && isset($offer['id'])) {
        $stmt->bindValue(':id', $offer['id']);
    }

    if($stmt->execute()) {
        $offerID = $db->lastInsertId();
        $productFields = implode(', ', $productAttributes);

        $productValues = implode(', ', array_map(fn($item) => ":$item", $productAttributes));
        $productSql = "INSERT INTO `offer_products` ($productFields) VALUES ($productValues);";

        if(!$isUpdate) {
            foreach($products as $product) {
                $stmt = $db->prepare($productSql);

                foreach ($productAttributes as $productAttribute) {
                    $stmt->bindValue(":$productAttribute", $product[$productAttribute]);
                }

                $stmt->bindValue(':offer_id', $offerID);
                $stmt->execute();
            }
        } else {
//            Delete products from database which are not present in the POST anymore (were deleted from the form)
            $stmt = $db->prepare('SELECT id FROM `offer_products` WHERE offer_id=:offer_id');
            $stmt->execute([':offer_id' => (int)$offer['id']]);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $ids = array_column($products, 'id');
            $difference = array_diff($result, $ids);

            if(!empty($difference)) {
                foreach ($difference as $diff) {
                    $stmt = $db->prepare('DELETE FROM `offer_products` WHERE id=:id');
                    $stmt->execute([':id' => (int)$diff]);
                }
            }

            foreach($products as $product) {
                if(!empty($product['id'])) {
                    $productUpdates = implode(', ', array_map(fn($item) => "$item=:$item", $productAttributes));
                    $productUpdateSql = "UPDATE `offer_products` SET $productUpdates WHERE id = :id;";

                    $stmt = $db->prepare($productUpdateSql);
                    $stmt->bindValue(':id', $product['id']);
                } else {
                    $stmt = $db->prepare($productSql);
                }

                foreach ($productAttributes as $productAttribute) {
                    $stmt->bindValue(":$productAttribute", $product[$productAttribute]);
                }

                if(empty($product['id'])) {
                    $stmt->bindValue(':offer_id', (int)$offer['id']);
                }

                $stmt->execute();
            }
        }

        header('Location: index.php');
    };
}
