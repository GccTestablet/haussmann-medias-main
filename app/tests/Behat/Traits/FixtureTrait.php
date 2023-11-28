<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use App\Entity\User;
use App\Tools\Parser\ObjectParser;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

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
     *
     * @param class-string $entityName
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
     * @Then /^I reset the password for "([^"]*)" with "([^"]*)"$/
     */
    public function iResetPasswordForUser(string $email, string $password): void
    {
        /** @var ?User $user */
        $user = $this->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user) {
            throw new UserNotFoundException(\sprintf('User %s not found', $email));
        }

        $this->visitPath(\sprintf('/reset-password/reset/%s', $user->getPasswordResetToken()));

        $this->fillField('reset_password_form[new_password][first]', $password);
        $this->fillField('reset_password_form[new_password][second]', $password);
        $this->pressButton('Enregistrer');

        $this->assertPageAddress('/login');
        $this->assertPageContainsText('Votre mot de passe a été modifié');

        $this->fillField('login_form[email]', $email);
        $this->fillField('login_form[password]', $password);
        $this->pressButton('Connexion');
        $this->iWaitToSee('Dashboard');
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
