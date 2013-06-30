<?php

require dirname(__FILE__) . '/Doctrine2TestSuite.php';

$test = new Doctrine2TestSuite();
$test->initialize();
$test->run($argv[1]);
