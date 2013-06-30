<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class CollectionLazyLoadTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $order = $systemUnderTest->em->find('Perf\Orm\Order', $currentExecution + 1);

        $totalPrice = 0;
        foreach ($order->items as $item) {
            $totalPrice = $item->price * $item->count;
        }
    }
}
