<?php
namespace Tamarin;

use Psr\Http\Message\ResponseInterface;

class Middleware
{
    public static function contentNegotiation(RepresentationInterface $representation)
    {
        return function (callable $handler) use ($representation) {
            return function ($request, array $options) use ($handler, $representation) {
                if (!empty($options['resource'])) {
                    $options['body'] = $representation->create($options['resource']);
                }
                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($representation) {
                        $content = $representation->parse($response);
                        return $content;
                    }
                );
            };
        };
    }
}