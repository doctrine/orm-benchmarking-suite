<?php

namespace Perf;

class Doctrine2TestSuite extends AbstractTestSuite
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em = null;

    public function initialize()
    {
        \Doctrine\ORM\Tools\Setup::registerAutoloadGit("/home/benny/code/php/wsnetbeans/doctrine2");

        $cache = new Doctrine\Common\Cache\ArrayCache();

        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__."/models"));

        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache); // not sql query cache, but dql query parsing cache.

        $config->setProxyDir(__DIR__ . "/proxies");
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses(false);

        $dbParams = array('driver' => 'pdo_sqlite', 'memory' => true);

        $this->em = \Doctrine\ORM\EntityManager::create($dbParams, $config);

        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        $schemaTool = new Doctrine\ORM\Tools\SchemaTool($this->em);

        try {
            $schemaTool->dropSchema($classes);
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        $schemaTool->createSchema($classes);

        // warmup dql parser caches
        $authors = $this->em->createQuery(
            'SELECT count(a.id) AS num FROM Author a WHERE a.id > ?1 OR (a.firstName = ?2 OR a.lastName = ?3)'
        )->setParameter(1, 1)
         ->setParameter(2, 'John Doe')
         ->setParameter(3, 'John Doe')
         ->setMaxResults(5)
         ->getSingleScalarResult();
        $books = $this->em->createQuery(
            'SELECT b FROM Book b WHERE b.price > ?1'
        )->setParameter(1, 100)
         ->setMaxResults(5)
         ->getResult();
        $book = $this->em->createQuery(
            'SELECT b, a FROM Book b JOIN b.author a WHERE b.title = ?1'
        )->setParameter(1, 'Hello1')
         ->setMaxResults(5)
         ->getResult();

        $this->em->getProxyFactory()->generateProxyClasses(array($this->em->getClassMetadata('Author')), __DIR__ . '/proxies');
    }

    public function beginTransaction() {
        $this->em->beginTransaction();
        // not needed, em flushes in commit
    }

    private $clears = 0;

    public function clearCache() {
        if ($this->clears > 1) {
            $this->em->clear(); // clear the first level cache, not second level! as in other examples
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

    public function runPKSearch($i)
    {
        $author = $this->authors[array_rand($this->authors)];

        $author = $this->em->find('Author', $author->id);
    }

    public function runComplexQuery($i)
    {
        $authors = $this->em->createQuery(
            'SELECT count(a.id) AS num FROM Author a WHERE a.id > ?1 OR (a.firstName = ?2 OR a.lastName = ?3)'
        )->setParameter(1, $this->authors[array_rand($this->authors)]->id)
         ->setParameter(2, 'John Doe')
         ->setParameter(3, 'John Doe')
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
            'SELECT b, a FROM Book b JOIN b.author a WHERE b.title = ?1'
        )->setParameter(1, 'Hello' . $i)
         ->getSingleResult();
    }

    public function runUpdate($i)
    {
        $author = $this->authors[array_rand($this->authors)];

        $author = $this->em->find('Author', $author->id);
        $author->firstName = $author->firstName . $i;
        $author->lastName = $author->lastName . $i;
    }
}
