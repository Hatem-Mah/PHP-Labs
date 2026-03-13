<?php
require_once 'autoload.php';

$auth = Auth::getInstance();
$auth->logout();

header('Location: login.php');
exit;
