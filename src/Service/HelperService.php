<?php

namespace App\Service;

use App\Util\DummySubject;

class HelperService
{
    private $db;
    private $logger;
    
    public function __construct($db, $router, $request, $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
        $this->router = $router;
        $this->request = $request;
    }

    public function baseUrl($request = null)
    {
        if (is_null($request)) {
            $request = $this->request;
        }
        return $request->getUri()->getBaseUrl();
    }

    public function pathFor($name, $full = false, $params = [], $query = [])
    {
        if ($full) {
            return $this->baseUrl().$this->router->pathFor($name, $params, $query);
        } else {
            return $this->router->pathFor($name, $params, $query);
        }
    }

    public function getUserFromSubject(DummySubject $subject, $with = null)
    {
        return $this->db->query('App:User', $with)
            ->where('subject_id', $subject->getId())
            ->firstOrFail();
    }

    public function getEntityFromId($model, $key, $params = null, $with = null)
    {
        $id = isset($params)? $this->getSanitizedId($key, $params): $key;
        return $this->db->query($model, $with)->findOrFail($id);
    }

    public function getSanitizedId($attr, $params)
    {
        $isDigit = ctype_digit($params[$attr] ?? 'x');
        return $isDigit ? $params[$attr] : -1;
    }

    public function getSanitizedStr($attr, $params)
    {
        // TODO hacer validacion de verdad
        return $params[$attr]?? null;
    }

    public function generateTrace($str)
    {
        return preg_replace('/[^[:alnum:]]/ui', '', $str);
    }

    public function getDuplicatedFields($model, $instance, $fields)
    {
        $dupFields = [];
        $qry = $this->db->query($model);
        if ($instance->exists) {
            $qry = $qry->where('id', '!=', $instance->id);
        }
        $queryFields = array_intersect_key($instance->toArray(), array_flip($fields));
        $qry = $qry->where(function ($q) use ($queryFields) {
            $q->where($queryFields, null, null, 'or');
        });
        $dupli = $qry->first();
        if (isset($dupli)) {
            foreach($fields as $field) {
                if ($instance->getAttribute($field) == $dupli->getAttribute($field)) {
                    $dupFields[] = $field;
                }
            };
        }
        return $dupFields;
    }
}
