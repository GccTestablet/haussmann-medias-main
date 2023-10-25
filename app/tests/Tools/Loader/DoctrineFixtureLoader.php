<?php

declare(strict_types=1);

namespace App\Tests\Tools\Loader;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineFixtureLoader
{
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
        $loader = new ContainerAwareLoader($this->container);

        foreach ($fixtureNames as $fixtureName) {
            if (!\class_exists($fixtureName)) {
                throw new NotFoundHttpException(\sprintf('Fixture "%s" not found', $fixtureName));
            }

            $loader->addFixture(new $fixtureName());
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
