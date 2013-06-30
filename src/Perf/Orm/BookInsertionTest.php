<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class BookInsertionTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $author = $systemUnderTest->authors[array_rand($systemUnderTest->authors)];
        $author = $systemUnderTest->em->getReference('Perf\Orm\Author', $author->id);

        $book = new Book();
        $book->title = 'Hello' . $currentExecution;
        $book->author = $author;
        $book->isbn = '1234';
        $book->price = $currentExecution;

        $systemUnderTest->em->persist($book);

        $systemUnderTest->books[] = $book;
    }
}
