<?php
namespace Tamarin;

class JsonApiRepresentation implements RepresentationInterface
{
    public function parse(\Psr\Http\Message\ResponseInterface $response)
    {
        $object = \GuzzleHttp\json_decode($response->getBody());
        $attributes = [];
        if (!property_exists($object, 'data') && !property_exists($object, 'meta') && !property_exists($object,
                'errors')
        ) {
            throw new \RuntimeException('Document MUST contain at least one of the following properties: data, errors, meta');
        }
        if (property_exists($object, 'data') && property_exists($object, 'errors')) {
            throw new \RuntimeException('The properties `data` and `errors` MUST NOT coexist in Document.');
        }
        if (property_exists($object, 'data')) {
            if (is_object($object->data)) {
                foreach (get_object_vars($object->data) as $key => $value) {
                    if ($key == 'attributes') {
                        foreach (get_object_vars($value) as $attrKey => $attrValue) {
                            $attributes[$attrKey] = $attrValue;
                        }
                        continue;
                    }
                    $attributes[$key] = $value;
                }
            }
        }
        if (property_exists($object, 'meta')) {
            $attributes['meta'] = $object->meta;
        }
        if (property_exists($object, 'errors')) {

        }
        if (property_exists($object, 'included')) {
            if (!property_exists($object, 'data')) {
                throw new \RuntimeException('If Document does not contain a `data` property, the `included` property MUST NOT be present either.');
            }
            // TODO join with relationships
        }
        if (property_exists($object, 'jsonapi')) {

        }
        if (property_exists($object, 'links')) {
            $attributes['links'] = $object->links;
        }
        return $attributes;
    }

    public function create(ResourceInterface $resource)
    {
        $payload = [];
        $attributes = $resource->getAttributes();
        if (isset($attributes['id'])) {
            $payload['data']['id'] = $attributes['id'];
            unset($attributes['id']);
        }
        if (isset($attributes['type'])) {
            $payload['data']['type'] = $attributes['type'];
            unset($attributes['type']);
        }
        $payload['data']['attributes'] = $attributes;
        return \GuzzleHttp\json_encode($payload);
    }
}