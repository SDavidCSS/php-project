<?php

require_once './classes/Session.php';

if(isset($_POST['logout'])) {
    $session = Session::getInstance();
    $session->destroy();
    header('Location: login.php');
    exit;
}

header('Location: index.php');
