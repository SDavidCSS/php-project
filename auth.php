<?php

require_once './classes/Session.php';

$session = Session::getInstance();

if(!$session->get('loggedIn')) {
    header('Location: login.php');
}