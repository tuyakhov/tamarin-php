<?php
namespace Tamarin\Tests;

class MockHandler
{
    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    function __invoke(\Psr\Http\Message\RequestInterface $request, array $options)
    {
        if (is_callable($this->result)) {
            $this->result = call_user_func($this->result, $request, $options);
        }
        return $this->result instanceof \Exception
            ? new \GuzzleHttp\Promise\RejectedPromise($this->result)
            : \GuzzleHttp\Promise\promise_for($this->result);
    }

}