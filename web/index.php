<?php
require_once __DIR__ . '/../vendor/autoload.php';

//error_reporting(E_ALL & ~E_NOTICE);

use Lenses\Lenses;

$lenses = new Lenses();
$lenses->run();