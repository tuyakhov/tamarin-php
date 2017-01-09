<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 09/01/2017
 * Time: 18:27
 */

namespace Tamarin;


use GuzzleHttp\Psr7\StreamDecoratorTrait;
use Psr\Http\Message\StreamInterface;

class ResourceStream implements StreamInterface
{
    use StreamDecoratorTrait;

    protected $resource;

    public function __construct(ResourceInterface $resource, StreamInterface $stream)
    {
        $this->resource = $resource;
        $this->stream = $stream;
    }

    public function getResource()
    {
        return $this->resource;
    }
}