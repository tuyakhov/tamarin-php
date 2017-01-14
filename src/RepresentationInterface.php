<?php
namespace Tamarin;

interface RepresentationInterface
{
    /**
     * @param string $payload
     * @return array
     */
    public function parse($payload);

    /**
     * @param array $data
     * @return string
     */
    public function create($data);
}