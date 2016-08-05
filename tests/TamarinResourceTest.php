<?php
namespace Tamarin\Tests;

use Tamarin\JsonApiRepresentation;
use Tamarin\Middleware;
use Tamarin\Tamarin;

class TamarinResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $handler = new MockHandler(new \GuzzleHttp\Psr7\Response(200, [], '{
            "data": {
                "id": "1",
                "type": "templates",
                "attributes": {
                    "name": "Nick"
                }
            }
        }'));
        $stack = new \GuzzleHttp\HandlerStack($handler);
        $stack->push(Middleware::contentNegotiation(new JsonApiRepresentation()));
        $client = new Tamarin([
            'handler' => $stack
        ]);
        $resource = new MockResource($client);
        $response = $resource->getAll();

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
    }
}
