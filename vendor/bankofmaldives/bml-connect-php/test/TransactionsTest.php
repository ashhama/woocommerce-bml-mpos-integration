<?php

class TransactionsTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $stub = $this->getMockBuilder('BMLConnect\Client')->disableOriginalConstructor()->getMock();
        $stub->method('post')->willReturn('foo');

        $transactions = new \BMLConnect\Transactions($stub);
        $this->assertEquals('foo', $transactions->create(['foo' => 'bar', 'amount' => 123, 'currency' => 'EUR']));

    }
}