<?php
namespace Tamarin;

use \GuzzleHttp\Client;

class Tamarin extends Client
{
    protected $representation = null;

    public function __construct(array $config)
    {
        if (empty($config['representation']) || !$config['representation'] instanceof RepresentationInterface) {
            $this->representation = new JsonApiRepresentation();
        }
        if (!isset($config['handler'])) {
            $handler = \GuzzleHttp\HandlerStack::create();
            $handler->push(Middleware::contentNegotiation($this->representation));
            $handler->push(Middleware::mediaType());
            $config['handler'] = $handler;
        }
        parent::__construct($config);
    }

}