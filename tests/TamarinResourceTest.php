<?php
namespace Tamarin\Tests;

use GuzzleHttp\Handler\MockHandler;
use Tamarin\JsonApiRepresentation;
use Tamarin\Middleware;
use Tamarin\ResourceStream;
use Tamarin\Tamarin;

class TamarinResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $handler = new MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], '{
                "data": {
                    "id": "1",
                    "type": "templates",
                    "attributes": {
                        "name": "Nick"
                    }
                }
            }')
        ]);
        $stack = \GuzzleHttp\HandlerStack::create($handler);
        $stack->push(Middleware::contentNegotiation(new JsonApiRepresentation()));
        $client = new Tamarin([
            'handler' => $stack
        ]);
        $resource = new MockResource($client);
        $response = $resource->getAll();

        $this->assertInstanceOf(ResourceStream::class, $response->getBody());
        $responseResource = $response->getBody()->getResource();
        $this->assertInstanceOf(MockResource::class, $responseResource);
        $this->assertEquals('Nick', $responseResource->name);
    }

    public function testCreate()
    {
        $handler = new MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], '{
                "data": {
                    "attributes": {
                        "dummyAttribute": "dummyValue"
                    }
                }
            }')
        ]);
        $stack = \GuzzleHttp\HandlerStack::create($handler);
        $stack->push(Middleware::contentNegotiation(new JsonApiRepresentation()));
        $client = new Tamarin([
            'handler' => $stack
        ]);
        $resource = new MockResource($client);
        $resource->dummyAttribute = 'dummyValue';
        $response = $resource->create();

        $this->assertInstanceOf(ResourceStream::class, $response->getBody());
        $responseResource = $response->getBody()->getResource();
        $this->assertInstanceOf(MockResource::class, $responseResource);
        $this->assertEquals('dummyValue', $responseResource->dummyAttribute);
    }
}
