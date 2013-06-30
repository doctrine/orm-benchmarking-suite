<?php

require_once dirname(__FILE__) . '/sfTimer.php';

abstract class AbstractTestSuite
{
	protected $books = array();
	protected $authors = array();

	abstract function initialize();
	abstract function clearCache();
	abstract function beginTransaction();
	abstract function commit();
	abstract function runAuthorInsertion($i);
	abstract function runBookInsertion($i);
	abstract function runPKSearch($i);
	abstract function runComplexQuery($i);
	abstract function runHydrate($i);
	abstract function runJoinSearch($i);
    abstract function runUpdate($i);

	public function run($name = null)
	{
		$t1 =  $this->runTest('runAuthorInsertion', 1700);
		$t1 += $this->runTest('runBookInsertion', 1700);
		$t2 = $this->runTest('runPKSearch', 1900);
		$t3 = $this->runTest('runComplexQuery', 190);
		$t4 = $this->runTest('runHydrate', 750);
		$t5 = $this->runTest('runJoinSearch', 700);
        $t6 = $this->runTest('runUpdate', 700);
		echo sprintf("%100s | %6d | %6d | %6d | %6d | %6d | %6d |", $name ?: get_class($this), $t1, $t2, $t3, $t4, $t5, $t6);
	}

	public function runTest($methodName, $nbTest)
	{
		$this->clearCache();
		$this->beginTransaction();
		$timer = new sfTimer();
		for($i=0; $i<$nbTest; $i++) {
			$this->$methodName($i);
		}
		$t = $timer->getElapsedTime();
		$this->commit();
		return $t * 1000;
	}
}
