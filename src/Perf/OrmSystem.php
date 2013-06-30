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
    public $queryCount;
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
        require_once $this->rootPath . "/lib/Doctrine/ORM/Version.php";

        if (version_compare(\Doctrine\ORM\Version::VERSION, '2.3.99') > 0) {
            require_once $this->rootPath . "/vendor/autoload.php";

            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__."/Orm"));
        } else if (version_compare(\Doctrine\ORM\Version::VERSION, '2.2.99') > 0) {
            require_once $this->rootPath . "/lib/Doctrine/ORM/Tools/Setup.php";

            \Doctrine\ORM\Tools\Setup::registerAutoloadGit($this->rootPath);

            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__."/Orm"));
        } else {
            require_once $this->rootPath . "/lib/vendor/doctrine-common/lib/Doctrine/Common/Autoloader.php";

            $loader = new \Doctrine\Common\Autoloader("Doctrine\ORM", $this->rootPath . "/lib/");
            $loader->register();

            $loader = new \Doctrine\Common\Autoloader("Doctrine\DBAL", $this->rootPath . "/lib/vendor/doctrine-dbal/lib");
            $loader->register();

            $loader = new \Doctrine\Common\Autoloader("Doctrine\Common", $this->rootPath . "/lib/vendor/doctrine-common/lib");
            $loader->register();

            $config = new \Doctrine\ORM\Configuration();
            $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
        }

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $this->queryCount = new DbalQueryCountLogger();

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache); // not sql query cache, but dql query parsing cache.

        $config->setProxyDir(__DIR__ . "/proxies");
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(false);
        $config->setSQLLogger($this->queryCount);

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

        $this->em->getProxyFactory()->generateProxyClasses($classes, __DIR__ . '/proxies');
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
        $this->queryCount->count = 0;
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

    public function createResult($duration)
    {
        return array('duration' => $duration, 'queryCount' => $this->queryCount->count);
    }
}
