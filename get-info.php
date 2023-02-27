<?php

require_once './classes/Offer.php';

if(isset($_GET['id'])) {
    $data = Offer::getOfferInfo($_GET['id']);

    echo json_encode(['success' => 'true', 'data' => $data]);
} else {
    echo json_encode(['success' => 'false']);
}
