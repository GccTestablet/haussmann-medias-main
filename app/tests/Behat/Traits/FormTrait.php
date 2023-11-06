<?php

declare(strict_types=1);

namespace App\Tests\Behat\Traits;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;

trait FormTrait
{
    /**
     * @Then /^the "([^"]*)" select field should contain:$/
     */
    public function assertSelectShouldContainOptions(string $selector, TableNode $tableNode): void
    {
        $this->assertSelectOptions($selector, $tableNode, true);
    }

    /**
     * @Then /^the "([^"]*)" select field should not contain:$/
     */
    public function assertSelectShouldNotContainOptions(string $selector, TableNode $tableNode): void
    {
        $this->assertSelectOptions($selector, $tableNode, false);
    }

    /**
     * @Then /^the option "([^"]*)" from select "([^"]*)" should be selected$/
     */
    public function assertSelectOptionShouldBeSelected(string $option, string $selectSelector): void
    {
        $this->assertSelectedOption($selectSelector, $option, true);
    }

    /**
     * @Then /^the option "([^"]*)" from select "([^"]*)" should not be selected$/
     */
    public function assertSelectOptionShouldNotBeSelected(string $option, string $selectSelector): void
    {
        $this->assertSelectedOption($selectSelector, $option, false);
    }

    private function assertSelectOptions(string $selector, TableNode $tableNode, bool $contains): void
    {
        $field = $this->getSession()->getPage()->findField($selector);
        if (null === $field) {
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value|placeholder', $selector);
        }

        $options = $field->findAll('css', 'option');
        $optionTexts = \array_map(
            static fn (NodeElement $option) => $option->getText(),
            $options
        );

        foreach ($tableNode->getRows() as $row) {
            [$text] = $row;

            if (
                ($contains && \in_array($text, $optionTexts, true))
                || (!$contains && !\in_array($text, $optionTexts, true))
            ) {
                continue;
            }

            throw new \Exception(\sprintf(
                'The select field "%s" %s contain the option "%s".',
                $selector,
                $contains ? 'should' : 'should not',
                $text
            ));
        }
    }

    private function assertSelectedOption(string $select, string $optionText, bool $selected): void
    {
        $field = $this->getSession()->getPage()->findField($select);
        if (null === $field) {
            throw new ElementNotFoundException($this->getSession(), 'form field', 'id|name|label|value|placeholder', $select);
        }

        $options = $field->findAll('css', 'option');
        $selectedOptions = \array_filter(
            $options,
            static fn (NodeElement $option) => $option->isSelected(),
        );

        $optionTexts = \array_map(
            static fn (NodeElement $option) => $option->getText(),
            $selectedOptions
        );

        if (
            ($selected && \in_array($optionText, $optionTexts, true))
            || (!$selected && !\in_array($optionText, $optionTexts, true))
        ) {
            return;
        }

        throw new \Exception(\sprintf(
            'The option "%s" from select field "%s" %s be selected.',
            $optionText,
            $select,
            $selected ? 'should' : 'should not',
        ));
    }
}
