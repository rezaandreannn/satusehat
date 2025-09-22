<?php

namespace Rezaandreannn\SatuSehat\Services;

use Illuminate\Support\Arr;
use Rezaandreannn\SatuSehat\FHIR\LocationFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class LocationService
{
    protected $satuSehat;
    protected $fhir;
    protected $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new LocationFHIR();
        $this->endpoint = '/fhir-r4/v1/Location';
    }

    /**
     * Search location by ID
     * @param $id
     */
    public function searchByID(string $id)
    {
        $endpoint = $this->endpoint  . '/' . $id;
        return $this->satuSehat->get($endpoint);
    }

    /**
     * search by organization ID
     * @param $orgId
     */
    public function searchByOrganizationID(string $orgId)
    {
        $param = [
            'organization' => $orgId
        ];

        return $this->satuSehat->get($this->endpoint, $param);
    }

    /**
     * create  location
     * @param $data
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
    }

    /**
     * update  location
     * @param $data
     */
    public function update(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->put($this->endpoint, $payload);
    }
}
