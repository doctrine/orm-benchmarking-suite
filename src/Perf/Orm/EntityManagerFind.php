<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class EntityManagerFind extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $authorId = $systemUnderTest->authors[$currentExecution]->id;
        $author = $systemUnderTest->em->find('Perf\Orm\Author', $authorId);
    }
}
