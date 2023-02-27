<?php

require_once './classes/Database.php';
require_once './classes/Offer.php';

if(isset($_POST['Offer'])) {
    $id = $_POST['Offer']['id'];

    $offer = Offer::findOne($id);
    $offer->delete();

    header('Location: index.php');
}