<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class SimpleEntityDqlQuery extends PerformanceTest
{
    protected function warmUp(SystemUnderTest $systemUnderTest)
    {
        $systemUnderTest->em->createQuery(
            'SELECT b FROM Perf\Orm\Book b WHERE b.price > ?1'
        )->getDql();
    }

    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $book = $systemUnderTest->em->createQuery(
            'SELECT b FROM Perf\Orm\Book b WHERE b.price > ?1'
        )->setParameter(1, $currentExecution)
         ->setMaxResults(1)
         ->getSingleResult();
    }
}
