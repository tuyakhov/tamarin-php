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
                $resource = isset($options['resource']) ? $options['resource'] : null;
                if (!empty($resource)) {
                    if ($resource instanceof ResourceInterface) {
                        $body = $representation->create($resource->getAttributes());
                        $request = $request
                            ->withBody(\GuzzleHttp\Psr7\stream_for($body));
                    }
                }
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($representation, $resource) {
                        if (!empty($resource)) {
                            if ($resource instanceof ResourceInterface) {
                                $content = $representation->parse((string) $response->getBody());
                                $resource->setAttributes($content);
                            }
                        }
                        return $response;
                    }
                );
            };
        };
    }

    public static function mediaType()
    {
        return function (callable $handler) {
            return function (
                RequestInterface $request,
                array $options
            ) use ($handler) {
                $request = $request->withHeader('Content-Type', 'application/vnd.api+json');
                return $handler($request, $options);
            };
        };
    }
}