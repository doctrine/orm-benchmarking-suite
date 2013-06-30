<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class CollectionFetchJoinTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $dql = 'SELECT o, oi FROM Perf\Orm\Order o JOIN o.items oi WHERE o.id = ?1';
        $order = $systemUnderTest->em->createQuery($dql)->setParameter(1, $currentExecution+1)->getSingleResult();

        $totalPrice = 0;
        foreach ($order->items as $item) {
            $totalPrice = $item->price * $item->count;
        }
    }
}
