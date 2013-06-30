<?php

namespace Perf;

use Doctrine\DBAL\Logging\SQLLogger;

class DbalQueryCountLogger implements SQLLogger
{
    /**
     * @var int
     */
    public $count;

    public function startQuery($sql, array $params = array(), array $types = array())
    {
    }

    public function stopQuery()
    {
        $this->count++;
    }

    public function logSQL($sql, array $params = null, $executionMS = null)
    {
        $this->count++;
    }
}
