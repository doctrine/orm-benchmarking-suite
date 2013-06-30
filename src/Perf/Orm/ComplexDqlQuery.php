<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class ComplexDqlQuery extends PerformanceTest
{
    protected function warmUp(SystemUnderTest $systemUnderTest)
    {
        $systemUnderTest->em->createQuery(
            'SELECT count(a.id) AS num FROM Perf\Orm\Author a WHERE a.id > ?1 OR (a.firstName = ?2 OR a.lastName = ?3)'
        )->getDQL();
    }

    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $authors = $systemUnderTest->em->createQuery(
            'SELECT count(a.id) AS num FROM Perf\Orm\Author a WHERE a.id > ?1 OR (a.firstName = ?2 OR a.lastName = ?3)'
        )->setParameter(1, $systemUnderTest->authors[array_rand($systemUnderTest->authors)]->id)
         ->setParameter(2, 'John Doe')
         ->setParameter(3, 'John Doe')
         ->getSingleScalarResult();
    }
}
