<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class UpdateTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $author = $systemUnderTest->authors[array_rand($systemUnderTest->authors)];

        $author = $systemUnderTest->em->find('Perf\Orm\Author', $author->id);
        $author->firstName = $author->firstName . $currentExecution;
        $author->lastName = $author->lastName . $currentExecution;
    }
}
