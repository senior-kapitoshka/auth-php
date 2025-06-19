<?php
require_once '../config/config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}