<?php

namespace Bauhaus\Container;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

use Bauhaus\Container\FakeReadableContainer;
use Bauhaus\Container\FakeRegistrableContainer;

require __DIR__ . '/../bootstrap.php';

class ContainerUserBaseContext implements Context, SnippetAcceptingContext
{
    const EXCEPTION_NAMESPACE = "Bauhaus\\Container\\Exception\\";

    protected $container = null;
    protected $outcome = null;

    /**
     * @Transform table:label,value
     */
    public function castItemsTableToArray(TableNode $itemsTable)
    {
        $items = [];
        foreach ($itemsTable as $row) {
            $items[$row['label']] = $row['value'];
        }

        return $items;
    }

    /**
     * @Given a :type container with the following items:
     */
    public function aContainerWithTheFolloingItems(string $type, array $items)
    {
        switch ($type) {
            case 'readable':
                $this->container = new FakeReadableContainer($items);
                break;
            case 'registrable':
                $this->container = new FakeRegistrableContainer($items);
                break;
        }
    }

    /**
     * @When I require the value of the item :label
     */
    public function iRequireTheValueOfTheItem($label)
    {
        $this->outcome = $this->container->$label;
    }

    /**
     * @Then I should receive( the) :expectedValue
     */
    public function iShouldReceive($expectedValue)
    {
        assertEquals($expectedValue, $this->outcome);
    }

    /**
     * @Then the exception :exceptionClassName is throwed with the message:
     */
    public function theExceptionIsThrowedWithTheMessage(
        $exceptionClassName,
        PyStringNode $message
    ) {
        assertInstanceOf(
            self::EXCEPTION_NAMESPACE . $exceptionClassName,
            $this->outcome
        );
        assertEquals($message->getRaw(), $this->outcome->getMessage());
    }
}