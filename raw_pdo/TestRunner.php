<?php

require dirname(__FILE__) . '/PDOTestSuite.php';

$test = new PDOTestSuite();
$test->initialize();
$test->run();
