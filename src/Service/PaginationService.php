<?php

namespace App\Service;

class PaginationService
{
    protected $validation;

    public function __construct($validation)
    {
        $this->validation = $validation;
    }

    public function getParams($request, array $extras = [])
    {
        $params = array_intersect_key($request->getQueryParams(), $extras);
        // $params['page'] = (int) $request->getQueryParam('page', 1);
        // $params['size'] = (int) $request->getQueryParam('size', 20);
        $extras['page'] = [
            'type' => 'integer',
            'minimum' => 1,
            'maximum' => 999,
            'default' => 1,
        ];
        $extras['size'] = [
            'type' => 'integer',
            'minimum' => 1,
            'maximum' => 100,
            'default' => 20,
        ];
        $schema = [
            'type' => 'object',
            'properties' => $extras,
        ];
        $v = $this->validation->fromSchema($schema);
        $params = $this->validation->prepareData($schema, $params);
        $v->assert($params);
        return $params;
    }

    public function renderResponse($response, $results)
    {
        $data = $results->metadata();
        $data['data'] = $results->toArray();
        return $response->withJSON($data);
    }
}
