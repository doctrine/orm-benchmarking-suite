<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class FetchJoinDqlQuery extends PerformanceTest
{
    protected function warmUp(SystemUnderTest $systemUnderTest)
    {
        $systemUnderTest->em->createQuery(
            'SELECT b, a FROM Perf\Orm\Book b JOIN b.author a WHERE b.title = ?1'
        )->getDQL();
    }

    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $book = $systemUnderTest->em->createQuery(
            'SELECT b, a FROM Perf\Orm\Book b JOIN b.author a WHERE b.title = ?1'
        )->setParameter(1, 'Hello' . $currentExecution)
         ->getSingleResult();
    }
}
