<?php

declare(strict_types=1);

namespace App\Tests\Tools\Loader;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DoctrineFixtureLoader
{
    private const FIXTURE_FOLDER = '%s/tests/Fixtures/Doctrine';

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly EntityManagerInterface $entityManager,
        private readonly ORMExecutor $ORMExecutor,
    ) {}

    /**
     * @param string[] $fixtureNames
     */
    public function loadFixtures(array $fixtureNames, bool $append = false): void
    {
        $fixtureFolder = \sprintf(self::FIXTURE_FOLDER, $this->container->getParameter('kernel.project_dir'));

        $loader = new ContainerAwareLoader($this->container);

        foreach ($fixtureNames as $fixtureName) {
            if (\class_exists($fixtureName)) {
                $loader->addFixture(new $fixtureName());

                continue;
            }

            $fixtureName = \preg_replace_callback("/[^\/]*/", static fn ($m) => \ucfirst($m[0]), $fixtureName);
            $fixturePath = \sprintf('%s/%sFixture.php', $fixtureFolder, $fixtureName);

            if (!\file_exists($fixturePath)) {
                throw new \InvalidArgumentException(\sprintf('Could not find fixture at path %s', $fixturePath));
            }

            $loader->loadFromFile($fixturePath);
        }

        if (!$append) {
            $this->truncateDatabase();
        }

        $this->ORMExecutor->execute($loader->getFixtures(), true);
    }

    public function getReference(string $reference): object
    {
        return $this->ORMExecutor->getReferenceRepository()->getReference($reference);
    }

    private function truncateDatabase(): void
    {
        $connection = $this->entityManager->getConnection();

        try {
            $tables = $connection->createSchemaManager()->listTables();
            $sequences = $connection->createSchemaManager()->listSequences();
            $connection->beginTransaction();

            $tableNames = \array_map(static fn ($table) => $table->getName(), $tables);
            $connection->executeQuery(
                \sprintf('TRUNCATE ONLY %s CASCADE', \implode(', ', $tableNames))
            );

            foreach ($sequences as $sequence) {
                $connection->executeQuery(
                    \sprintf('ALTER SEQUENCE %s RESTART WITH 1', $sequence->getName())
                );
            }

            $connection->commit();
        } catch (Exception) {
        }
    }
}
