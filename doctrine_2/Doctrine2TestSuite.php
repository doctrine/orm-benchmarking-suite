<?php

require_once dirname(__FILE__) . '/../AbstractTestSuite.php';

class Doctrine2TestSuite extends AbstractTestSuite
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;
    private static $classes = null;

    public function initialize()
    {
      $lib = __DIR__ . '/vendor/doctrine2/lib/';
      require $lib . 'vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';

      $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', $lib . 'vendor/doctrine-common/lib');
      $classLoader->register();

      $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', $lib . 'vendor/doctrine-dbal/lib');
      $classLoader->register();

      $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', $lib);
      $classLoader->register();
      
      $classloader = new \Doctrine\Common\ClassLoader('Symfony', $lib . 'vendor/');
      $classloader->register();
      
      $cache = new \Doctrine\Common\Cache\ArrayCache;
      $config = new Doctrine\ORM\Configuration;
      $config->setMetadataCacheImpl($cache);
      $driverImpl = $config->newDefaultAnnotationDriver(__DIR__ . '/models');
      $config->setMetadataDriverImpl($driverImpl);
      $config->setQueryCacheImpl($cache);
      $config->setProxyDir(__DIR__ . '/proxies');
      $config->setProxyNamespace('Proxies');
      $config->setAutoGenerateProxyClasses(false); // no code generation in production
      
      $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);

      $this->em = Doctrine\ORM\EntityManager::create($dbParams, $config);
        
      if (!self::$classes) {
        self::$classes = $this->em->getMetadataFactory()->getAllMetadata();
      }
        
      $schemaTool = new Doctrine\ORM\Tools\SchemaTool($this->em);
      $schemaTool->createSchema(self::$classes);
      
      require_once __DIR__ . '/proxies/AuthorProxy.php';
    }

    public function beginTransaction() {
        $this->em->beginTransaction();
    }

    private $clears = 0;

    public function clearCache() {
        if ($this->clears > 1) {
            $this->em->clear(); // clear the first level cache (identity map), as in other examples
                                // so that objects are not re-used
        }
        $this->clears++;
    }
    public function commit() {
        $this->em->flush();
        $this->em->commit();
    }

    function runAuthorInsertion($i) {
        $author = new Author();
        $author->firstName = 'John' . $i;
        $author->lastName = 'Doe' . $i;

        $this->em->persist($author);
        
        $this->authors[] = $author;
    }

    function runBookInsertion($i) {
        $book = new Book();
        $book->title = 'Hello' . $i;
        $book->author = $this->authors[array_rand($this->authors)];
        $book->isbn = '1234';
        $book->price = $i;

        $this->em->persist($book);
        
        $this->books[] = $book;
    }

    public function runComplexQuery($i)
    {
        $authors = $this->em->createQuery(
            'SELECT count(a.id) AS num FROM Author a WHERE a.id > ?1 OR CONCAT(a.firstName, a.lastName) = ?2'
        )->setParameter(1, $this->authors[array_rand($this->authors)]->id)
         ->setParameter(2, 'John Doe')
         ->getSingleScalarResult();
    }

    public function runHydrate($i)
    {        
        $books = $this->em->createQuery(
            'SELECT b FROM Book b WHERE b.price > ?1'
        )->setParameter(1, $i)
         ->setMaxResults(5)
         ->getResult();
        
        $this->em->clear();
    }

    public function runJoinSearch($i)
    {
        $book = $this->em->createQuery(
            'SELECT b, a FROM Book b LEFT JOIN b.author a WHERE b.title = ?1'
        )->setParameter(1, 'Hello' . $i)
         ->setMaxResults(1)
         ->getResult();
    }

    public function runPKSearch($i)
    {
        $author = $this->authors[array_rand($this->authors)];
        
        $author = $this->em->find('Author', $author->id);
        $this->em->clear();
    }
}