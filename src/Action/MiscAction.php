<?php

namespace App\Action;

class MiscAction
{
    protected $db;
    protected $representation;
    protected $helper;

    public function __construct($db, $representation, $helper)
    {
        $this->db = $db;
        $this->representation = $representation;
        $this->helper = $helper;
    }

    public function getCategories($request, $response, $params)
    {
        $regs = $this->db->query('App:Category')->get();
        return $response->withJSON($regs->toArray());
    }

    public function getRegions($request, $response, $params)
    {
        $regs = $this->db->query('App:Region')->get();
        return $response->withJSON($regs->toArray());
    }

    public function getDepartments($request, $response, $params)
    {
        $regId = $this->helper->getSanitizedId('reg', $params);
        $deps = $this->db->query('App:Department')->where('region_id', $regId)->get();
        return $response->withJSON($deps->toArray());
    }

    public function getLocalities($request, $response, $params)
    {
        $depId = $this->helper->getSanitizedId('dep', $params);
        $locs = $this->db->query('App:Locality')->where('department_id', $depId)->get();
        return $response->withJSON($locs->toArray());
    }

    public function getLocality($request, $response, $params)
    {
        $locId = $this->helper->getSanitizedId('loc', $params);
        $locality = $this->db->query('App:Locality', ['department.region'])->findOrFail($locId);
        return $response->withJSON($locality->toArray());
    }
}
