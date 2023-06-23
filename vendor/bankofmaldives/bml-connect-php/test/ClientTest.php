<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testClientEndpoint()
    {
        $client = new BMLConnect\Client('foo', 'bar');

        $this->assertEquals(
            $client::BML_PRODUCTION_ENDPOINT,
            PHPUnit_Framework_Assert::readAttribute($client, "baseUrl")
        );

        $client = new BMLConnect\Client('foo', 'bar', 'sandbox');

        $this->assertEquals(
            $client::BML_SANDBOX_ENDPOINT,
            PHPUnit_Framework_Assert::readAttribute($client, "baseUrl")
        );

    }


    public function testPost()
    {
        $mock = new MockHandler([new Response(200, ['X-Foo' => 'Bar'], "{\"foo\":\"bar\"}")]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mock);
        $stack->push($history);
        $http_client = new Client(['handler' => $stack]);
        $client = new BMLConnect\Client('foo' , 'bar');
        $client->setClient($http_client);

        $client->transactions->create(['foo' => 'bar', 'amount' => 123, 'currency' => 'EUR']);

        foreach ($container as $transaction) {
            $this->assertEquals('POST', $transaction['request']->getMethod());
        }
    }
}
