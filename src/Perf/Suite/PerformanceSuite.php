<?php

namespace Perf\Suite;

class PerformanceSuite
{
    private $tests = array();

    public function addTest(PerformanceTest $test, $times)
    {
        $this->tests[] = array('test' => $test, 'times' => $times);
    }

	public function run(SystemUnderTest $systemUnderTest)
	{
        $results = array();

        $systemUnderTest->setUp();

        foreach ($this->tests as $test) {
            $duration = $test['test']->run($systemUnderTest, $test['times']);

            $results[$test['test']->getName()] = $systemUnderTest->createResult($duration);
        }

        $systemUnderTest->tearDown();

        return $results;
	}
}
