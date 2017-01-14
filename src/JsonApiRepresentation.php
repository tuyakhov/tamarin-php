<?php
namespace Tamarin;

class JsonApiRepresentation implements RepresentationInterface
{
    /**
     * @param string $payload
     * @return array
     */
    public function parse($payload)
    {
        $object = \GuzzleHttp\json_decode($payload);
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

    public function create($data)
    {
        $payload = [];
        if (isset($data['id'])) {
            $payload['data']['id'] = $data['id'];
            unset($data['id']);
        }
        if (isset($data['type'])) {
            $payload['data']['type'] = $data['type'];
            unset($data['type']);
        }
        $payload['data']['attributes'] = $data;
        return \GuzzleHttp\json_encode($payload);
    }
}