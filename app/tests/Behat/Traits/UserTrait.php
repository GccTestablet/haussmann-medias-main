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
        $this->iAmLoggedOnAs('super-admin@hm.mail');
    }

    /**
     * @Given /^I am logged on as "([^"]*)"$/
     */
    public function iAmLoggedOnAs(string $email): void
    {
        $this->visitPath('/login');
        $this->iShowHtmlContent();
        $this->fillField('login_form[email]', $email);
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
