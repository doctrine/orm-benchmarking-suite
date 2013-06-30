<?php

namespace Perf\Orm;

use Perf\Suite\PerformanceTest;
use Perf\Suite\SystemUnderTest;

class OrderInsertTest extends PerformanceTest
{
    protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution)
    {
        $order = new Order();
        for ($i = 0; $i < 5; $i++) {
            $order->addItem("Product $currentExecution-$i", $i * 10, $i);
        }

        $systemUnderTest->em->persist($order);
    }
}
