<?php

require_once dirname(__FILE__) . '/Doctrine2TestSuite.php';

class Doctrine2WithCacheTestSuite extends Doctrine2TestSuite
{
    private $cache = null;
    
	function initialize()
	{
		parent::initialize();
		$this->cache = new Doctrine\Common\Cache\ArrayCache();
		$this->em->getConfiguration()->setMetadataCacheImpl($this->cache);
        $this->em->getConfiguration()->setQueryCacheImpl($this->cache);
	}
	
}