<?php

define('AO_START', microtime(true));

require __DIR__.'/../mavoc/Main.php';

$ao = new mavoc\Main();
$ao->init();
