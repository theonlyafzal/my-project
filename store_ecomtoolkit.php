<?php
require_once __DIR__ . '/wp-load.php';
header('Content-Type: text/html; charset=UTF-8');
readfile(__DIR__ . '/wp-content/themes/rev-digital-products/ecomtoolkit/index.html');
