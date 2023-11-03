<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Behat\Gherkin\Node\TableNode;

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
}
