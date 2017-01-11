<?php
namespace Tamarin;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Middleware
{
    public static function contentNegotiation(RepresentationInterface $representation)
    {
        return function (callable $handler) use ($representation) {
            return function (RequestInterface $request, array $options) use ($handler, $representation) {
                if (!empty($options['resource'])) {
                    $resource = $options['resource'];
                    $body = $representation->create($resource);
                    $request = $request->withBody(\GuzzleHttp\Psr7\stream_for($body));
                }
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($representation, $resource) {
                        $content = $representation->parse($response);
                        $resource->setAttributes($content);
                        $resourceStream = new ResourceStream($resource, $response->getBody());
                        return $response->withBody($resourceStream);
                    }
                );
            };
        };
    }
}