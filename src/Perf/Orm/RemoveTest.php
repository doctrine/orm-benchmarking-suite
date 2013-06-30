<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class RemoveTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $author = $systemUnderTest->authors[$currentExecution];

        $author = $systemUnderTest->em->getReference('Perf\Orm\Author', $author->id);
        $systemUnderTest->em->remove($author);
    }
}
