<?php
namespace Tamarin;

use \Psr\Http\Message\ResponseInterface;

interface RepresentationInterface
{
    /**
     * @param ResponseInterface $response
     * @return Resource
     */
    public function parse(ResponseInterface $response);

    /**
     * @param ResourceInterface $resource
     * @return mixed
     */
    public function create(ResourceInterface $resource);
}