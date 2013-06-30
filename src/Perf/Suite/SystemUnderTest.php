<?php

namespace Perf\Suite;

abstract class SystemUnderTest
{
    /**
     * Setup system, not included in benchmark.
     */
    public function setUp()
    {
    }

    /**
     * Teardown system, not included in benchmark.
     */
    public function tearDown()
    {
    }

    /**
     * Reset caches and warmup them appropriatly.
     */
    public function warmUp()
    {
    }

    /**
     * Start system, included in benchmark.
     */
    public function start()
    {
    }

    /**
     * End system, included in benchmark.
     */
    public function end()
    {
    }
}
