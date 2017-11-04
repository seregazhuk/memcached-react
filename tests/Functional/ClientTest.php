<?php

namespace seregazhuk\React\Memcached\tests\Functional;

use Clue\React\Block;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use seregazhuk\React\Memcached\Client;
use seregazhuk\React\Memcached\Factory as ClientFactory;

class ClientTest extends WaitTestCase
{
    /**
     * @var Client
     */
    protected $client;


    protected function setUp()
    {
        parent::setUp();
        $this->client = ClientFactory::createClient($this->loop);
    }

    /** @test */
    public function it_stores_and_retrieves_values()
    {
        $setPromise = $this->client->set('key', [12345]);
        $this->waitForPromiseResolves($setPromise);

        $getPromise = $this->client->get('key');
        $this->assertEquals([12345], $this->waitForPromiseResolves($getPromise));
    }

    /** @test */
    public function it_flashes_database()
    {
        $this->waitForPromiseResolves($this->client->set('key', 12345));
        $this->waitForPromiseResolves($this->client->flushAll());

        $getPromise = $this->client->get('key');

        $this->waitForPromiseRejects($getPromise);
    }

    /** @test */
    public function it_increments_value()
    {
        $this->waitForPromiseResolves($this->client->set('key', 11));
        $this->waitForPromiseResolves($this->client->incr('key', 1));

        $newVal = $this->waitForPromiseResolves($this->client->get('key'));
        $this->assertEquals(12, $newVal);
    }

    /** @test */
    public function it_decrements_value()
    {
        $this->waitForPromiseResolves($this->client->set('key', 10));
        $this->waitForPromiseResolves($this->client->decr('key', 1));

        $newVal = $this->waitForPromiseResolves($this->client->get('key'));
        $this->assertEquals(9, $newVal);
    }
}
