<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use App\Tools\Parser\ObjectParser;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;

trait FixtureTrait
{
    /**
     * @Given /^I load fixtures:$/
     */
    public function iLoadFixtures(TableNode $fixtureNames): void
    {
        $this->load($fixtureNames, false);
    }

    private function load(TableNode $fixtureNames, bool $append): void
    {
        $names = [];
        foreach ($fixtureNames->getRows() as $row) {
            $names[] = $row[0];
        }

        $this->loadOrmOnDemandFixtures($names, $append);
    }

    /**
     * @Given /^I update entity "([^"]*)" with ID "([^"]*)":$/
     */
    public function iUpdateEntityWithID(string $entityName, string $id, TableNode $data): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getService('doctrine.orm.entity_manager');
        $entity = $entityManager->find($entityName, $id);

        /** @var ObjectParser $objectParser */
        $objectParser = $this->getService(ObjectParser::class);

        $rows = [];
        foreach ($data->getColumnsHash() as $row) {
            $rows[$row['field']] = $row['value'];

            $this->transformData($rows, $row['field'], $row['type']);
        }

        $objectParser->mergeFromArray($rows, $entity);

        $entityManager->flush();
    }

    /**
     * @param array<string, mixed> $rows
     */
    private function transformData(array &$rows, string $field, string $type): void
    {
        match ($type) {
            'reference' => $this->denormalizeReferenceFields($rows, [$field]),
            default => null,
        };
    }
}
