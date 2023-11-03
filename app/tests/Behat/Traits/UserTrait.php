<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

trait UserTrait
{
    /**
     * @Given /^I am logged on$/
     */
    public function iAmLoggedOn(): void
    {
        $this->visitPath('/login');
        $this->fillField('login_form[email]', 'super-admin@hm.mail');
        $this->fillField('login_form[password]', 'Qwerty123');
        $this->pressButton('Connexion');
        $this->assertPageAddress('/');
    }

    /**
     * @When /^I switch to company "([^"]*)"$/
     */
    public function iSwitchToCompany(string $companyName): void
    {
        $this
            ->getSession()
            ->getPage()
            ->find('css', '.nav-main .dropdown-menu')
            ->findLink($companyName)
            ->click()
        ;
    }
}
