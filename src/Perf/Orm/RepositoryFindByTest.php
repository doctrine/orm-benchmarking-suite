<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class RepositoryFindByTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $bookRepository = $systemUnderTest->em->getRepository('Perf\Orm\Book');
        $books = $bookRepository->findBy(array('author' => $currentExecution));
    }
}
