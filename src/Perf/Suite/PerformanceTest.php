<?php

namespace Perf\Suite;

abstract class PerformanceTest
{
    abstract protected function performTest(SystemUnderTest $systemUnderTest, $currentExecution);

    public function getName()
    {
        return (new \ReflectionObject($this))->getShortName();
    }

    protected function warmUp(SystemUnderTest $systemUnderTest)
    {
    }

    final public function run(SystemUnderTest $systemUnderTest, $times)
    {
        $systemUnderTest->warmUp();
        $this->warmUp($systemUnderTest);

        $timer = new Timer($this->getName());
        $systemUnderTest->start();

        for ($i = 0; $i < $times; $i++) {
            $this->performTest($systemUnderTest, $i);
        }

        $systemUnderTest->end();

        return $timer->getElapsedTime() * 1000;
    }
}
