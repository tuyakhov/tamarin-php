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
        $stack->push(Middleware::mediaType());
        $client = new Tamarin([
            'handler' => $stack
        ]);
        $resource = new MockResource($client);
        $resource->getAll();
        $this->assertEquals('Nick', $resource->name);
        $this->assertEquals('1', $resource->id);
    }

    public function testCreate()
    {
        $handler = new MockHandler([
            new \GuzzleHttp\Psr7\Response(200, [], '{
                "data": {
                    "attributes": {
                        "dummyAttribute": "dummyValue",
                        "id": "dummyId"
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
        $resource->create();

        $this->assertEquals('dummyValue', $resource->dummyAttribute);
        $this->assertEquals('dummyId', $resource->id);
    }
}
