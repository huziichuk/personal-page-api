<?php

global $pdo;
require_once 'utils/config.php';
require_once 'utils/connect.php';

$pdo->prepare("DELETE FROM tokens WHERE expires_in < :now")->execute(['now' => time()]);