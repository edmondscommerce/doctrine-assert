<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests;

use Doctrine\ORM\Tools\DisconnectedClassMetadataFactory;
use Doctrine\ORM\Tools\EntityGenerator;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;


abstract class AbstractDoctrineTest extends TestCase
{
    public const ENTITY_PATH = '/src/Entity';
    public const CONFIG_PATH = '/config';
    public const DB_PATH     = '/db.sqlite';

    public const ENTITY_GEN_GENERATE_ANNOTATIONS = true;
    public const ENTITY_GEN_GENERATE_METHODS     = true;
    public const ENTITY_GEN_REGENERATE_ENTITIES  = true;
    public const ENTITY_GEN_UPDATE_ENTITIES      = false;
    public const ENTITY_GEN_NUM_SPACES           = 4;
    public const ENTITY_GEN_BACKUP_EXISTING      = false;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var vfsStreamDirectory
     */
    private $rootDir;

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function setUp()
    {
        $this->setupVfs();
        $this->setupEntityManager();
        $this->generateEntities();
        $this->updateSchema();
    }

    abstract protected function getFixturePath(): string;

    public function getRootDir()
    {
        return $this->rootDir;
    }

    private function setupVfs(): void
    {
        $this->rootDir = vfsStream::setup();

        vfsStream::copyFromFileSystem($this->getFixturePath());
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    private function setupEntityManager(): void
    {
        $isDevMode = true;

        $config = Setup::createYAMLMetadataConfiguration(
            [$this->rootDir->url() . self::CONFIG_PATH],
            $isDevMode
        );

        $connection = [
            'driver' => 'pdo_sqlite',
            'path'   => $this->rootDir->url() . self::DB_PATH
        ];

        $this->entityManager = EntityManager::create($connection, $config);
    }

    private function generateEntities(): void
    {
        $classMetadataFactory = new DisconnectedClassMetadataFactory();
        $classMetadataFactory->setEntityManager($this->getEntityManager());
        $allMetadata = $classMetadataFactory->getAllMetadata();

        if (empty($allMetadata)) {
            throw new \InvalidArgumentException(
                'You need to configure a set of entity fixtures for this test'
            );
        }

        $destinationPath = $this->rootDir->url() . self::ENTITY_PATH;

        if (! file_exists($destinationPath)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Entities destination directory %s does not exist.',
                    $destinationPath
                )
            );
        }

        if (! is_writable($destinationPath)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Entities destination directory %s does not have write permissions.',
                    $destinationPath
                )
            );
        }

        $entityGenerator = new EntityGenerator();

        $entityGenerator->setGenerateAnnotations(self::ENTITY_GEN_GENERATE_ANNOTATIONS);
        $entityGenerator->setGenerateStubMethods(self::ENTITY_GEN_GENERATE_METHODS);
        $entityGenerator->setRegenerateEntityIfExists(self::ENTITY_GEN_REGENERATE_ENTITIES);
        $entityGenerator->setUpdateEntityIfExists(self::ENTITY_GEN_UPDATE_ENTITIES);
        $entityGenerator->setNumSpaces(self::ENTITY_GEN_NUM_SPACES);
        $entityGenerator->setBackupExisting(self::ENTITY_GEN_BACKUP_EXISTING);

        $entityGenerator->generate($allMetadata, $destinationPath);
    }

    private function updateSchema(): void
    {

    }
}