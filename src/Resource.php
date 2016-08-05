<?php
namespace Tamarin;

use GuzzleHttp\Client;

abstract class Resource implements ResourceInterface
{
    protected $attributes = [];

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    function __get($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}