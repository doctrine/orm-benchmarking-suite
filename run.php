<?php

require_once "vendor/autoload.php";

if (!isset($argv[1])) {
    echo("Missing argument 1: Library root directory\n");
    exit(1);
}

$suite = new \Perf\Suite\PerformanceSuite();

// Book Example
$suite->addTest(new \Perf\Orm\AuthorInsertionTest(), 1700);
$suite->addTest(new \Perf\Orm\BookInsertionTest(), 1700);
$suite->addTest(new \Perf\Orm\EntityManagerFind(), 1700);
$suite->addTest(new \Perf\Orm\RepositoryFindByTest(), 1700);
$suite->addTest(new \Perf\Orm\SimpleEntityDqlQuery(), 500);
$suite->addTest(new \Perf\Orm\FetchJoinDqlQuery(), 500);
$suite->addTest(new \Perf\Orm\ComplexDqlQuery(), 500);
$suite->addTest(new \Perf\Orm\UpdateTest(), 1000);
$suite->addTest(new \Perf\Orm\RemoveTest(), 1000);// last test in book suite!

// Order Example
$suite->addTest(new \Perf\Orm\OrderInsertTest(), 500);
$suite->addTest(new \Perf\Orm\CollectionLazyLoadTest(), 500);
$suite->addTest(new \Perf\Orm\CollectionFetchJoinTest(), 500);

$data = $suite->run(new \Perf\OrmSystem($argv[1]));

echo json_encode($data);
