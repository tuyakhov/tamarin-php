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
        return $this->client->post("/{$this->getName()}", ['resource' => $this]);
    }

    /**
     * @param array $params
     * @return array|ResponseInterface
     */
    public function getAll(array $params = [])
    {
        return $this->client->get("/{$this->getName()}", ['query' => $params]);
    }

    /**
     * @param $id
     * @param array $params
     * @return array|ResponseInterface
     */
    public function getOne($id, array $params = [])
    {
        return $this->client->get(str_replace('{id}', $id, "/{$this->getName()}/{id}"), ['query' => $params]);
    }

    /**
     * @param $id
     * @return array|ResponseInterface
     */
    public function update($id = null)
    {
        $id = (isset($id) ? $id : $this->id);
        return $this->client->patch(str_replace('{id}', $id, "/{$this->getName()}/{id}"), ['resource' => $this]);
    }

    /**
     * @param $id
     * @return array|ResponseInterface
     */
    public function delete($id = null)
    {
        $id = (isset($id) ? $id : $this->id);
        return $this->client->delete(str_replace('{id}', $id, "/{$this->getName()}/{id}"));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strtolower(get_class($this)) . 's';
    }
}