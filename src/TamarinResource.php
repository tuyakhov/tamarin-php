<?php
namespace Tamarin;

use Psr\Http\Message\ResponseInterface;

abstract class TamarinResource extends Resource
{
    /**
     * @return array|ResponseInterface
     */
    public function create()
    {
        return $this->sendRequest('post', ['{resource}']);
    }

    /**
     * @param array $params
     * @return array|ResponseInterface
     */
    public function getAll(array $params = [])
    {
        return $this->sendRequest('get', ['{resource}'], ['query' => $params]);
    }

    /**
     * @param $id
     * @param array $params
     * @return array|ResponseInterface
     */
    public function getOne($id, array $params = [])
    {
        return $this->sendRequest('get', ['{resource}/{id}', ['id' => $id]], ['query' => $params]);
    }

    /**
     * @param $id
     * @return array|ResponseInterface
     */
    public function update($id = null)
    {
        $id = (isset($id) ? $id : $this->id);
        return $this->sendRequest('patch', ['{resource}/{id}', ['id' => $id]]);
    }

    /**
     * @param $id
     * @return array|ResponseInterface
     */
    public function delete($id = null)
    {
        $id = (isset($id) ? $id : $this->id);
        return $this->sendRequest('delete', ['{resource}/{id}', ['id' => $id]]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        $reflectionClass = new \ReflectionClass($this);
        return strtolower($reflectionClass->getShortName()) . 's';
    }

    protected function sendRequest($method, $route, array $options = [])
    {
        if (is_array($route)) {
            $url = $route[0];
            $url = str_replace("{resource}", $this->getName(), $url);
            if (isset($route[1])) {
                foreach ((array) $route[1] as $param => $value) {
                    $url = str_replace("{{$param}}", $value, $url);
                }
            }
        } else {
            $url = (string) $route;
        }
        if (!isset($options['resource'])) {
            $options['resource'] = $this;
        }
        return $this->client->{$method}($url, $options);
    }
}