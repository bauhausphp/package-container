<?php

namespace Bauhaus;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    private $container = null;

    protected function setUp()
    {
        $this->container = new Container($this->containerItems());
    }

    protected function containerItems()
    {
        return [
            'pokemon' => 'Charmander',
            'pirate' => 'Barbossa',
            'music' => 'Right Now',
            'instrument' => 'Bass',
            'follow' => 'The White Rabbit',
        ];
    }

    protected function nonExistingLabels()
    {
        return [
            'aName',
            'anotherName',
            '',
        ];
    }

    /**
     * @test
     * @dataProvider labelsAndTheirExistence
     */
    public function verifyThatAnItemExistsByItsLabel($label, $exists)
    {
        $this->assertTrue($this->container->has($label) === $exists);
    }

    public function labelsAndTheirExistence()
    {
        foreach (array_keys($this->containerItems()) as $label) {
            yield [$label, true];
        }

        foreach ($this->nonExistingLabels() as $label) {
            yield [$label, false];
        }
    }

    /**
     * @test
     * @dataProvider labelsAndValuesOfItems
     */
    public function retrieveValueOfAnItemByItsLabel($label, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->container->get($label));
    }

    /**
     * @test
     * @dataProvider labelsAndValuesOfItems
     */
    public function retrieveValueOfAnItemByItsLabelUsingMagicMethod($label, $expectedValue)
    {
        $this->assertEquals($expectedValue, $this->container->$label);
    }

    public function labelsAndValuesOfItems()
    {
        foreach ($this->containerItems() as $label => $value) {
            yield [$label, $value];
        }
    }

    /**
     * @test
     */
    public function retrieveAllItemsWhenCallingTheMethodAll()
    {
        $expectedItems = $this->containerItems();

        $this->assertEquals($expectedItems, $this->container->all());
    }

    /**
     * @test
     */
    public function retrieveAllItemsWhenIteratingIt()
    {
        $outcome = [];
        foreach ($this->container as $label => $value) {
            $outcome[$label] = $value;
        }

        $this->assertEquals($this->containerItems(), $outcome);
    }

    /**
     * @test
     * @expectedException Bauhaus\Container\ItemNotFoundException
     * @expectedExceptionMessage No item labeled as 'nonExistingLabel' was found in this container
     */
    public function exceptionOccursWhenTryToRetriveAValueByNonExistingLabel()
    {
        $this->container->nonExistingLabel;
    }
}
