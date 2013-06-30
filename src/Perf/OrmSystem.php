<?php

namespace Perf;

use Perf\Suite\SystemUnderTest;

class OrmSystem extends SystemUnderTest
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    public $em;
    public $books = array();
    public $authors = array();
    private $rootPath;

    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Setup system, not included in benchmark.
     */
    public function setUp()
    {
        require_once $this->rootPath . "/lib/Doctrine/ORM/Tools/Setup.php";

        \Doctrine\ORM\Tools\Setup::registerAutoloadGit($this->rootPath);

        $cache = new \Doctrine\Common\Cache\ArrayCache();

        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__."/Orm"));

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache); // not sql query cache, but dql query parsing cache.

        $config->setProxyDir(__DIR__ . "/proxies");
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(false);

        $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);

        $this->em = \Doctrine\ORM\EntityManager::create($dbParams, $config);

        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->em);

        try {
            $schemaTool->dropSchema($classes);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        $schemaTool->createSchema($classes);

        $this->em->getProxyFactory()->generateProxyClasses(array(
            $this->em->getClassMetadata('Perf\Orm\Author')
            ), __DIR__ . '/proxies');
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
        $this->em->clear();
    }

    /**
     * Start system, included in benchmark.
     */
    public function start()
    {
        $this->em->beginTransaction();
    }

    /**
     * End system, included in benchmark.
     */
    public function end()
    {
        $this->em->flush();
        $this->em->commit();
    }
}
