<?php
namespace Tamarin;

class Template extends TamarinResource
{
    public function render($id = null)
    {
        $id = (isset($id) ? $id : $this->id);
        $this->client->post(str_replace('{id}', $id, "/{$this->getName()}/{id}/render"), ['resource' => $this]);
    }
}