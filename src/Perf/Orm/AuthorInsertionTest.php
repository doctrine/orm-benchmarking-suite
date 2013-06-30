<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class AuthorInsertionTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $systemUnderTest->requiresFlush = true;

        $author = new Author();
        $author->firstName = 'John' . $currentExecution;
        $author->lastName = 'Doe' . $currentExecution;

        $systemUnderTest->em->persist($author);

        $systemUnderTest->authors[] = $author;
    }
}
