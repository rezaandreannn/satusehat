<?php

namespace Rezaandreannn\SatuSehat\Services;

use Rezaandreannn\SatuSehat\FHIR\OrganizationFHIR;
use Rezaandreannn\SatuSehat\SatuSehatService;

class OrganizationService
{
    protected $satuSehat;
    protected $fhir;
    protected $endpoint;

    public function __construct()
    {
        $this->satuSehat = new SatuSehatService();
        $this->fhir = new OrganizationFHIR();
        $this->endpoint = '/fhir-r4/v1/Organization';
    }

    /**
     * Cari organization by id
     * @param $id
     */
    public function searchByID(string $id)
    {
        $endpoint = $this->endpoint  . '/' . $id;
        return $this->satuSehat->get($endpoint);
    }

    /**
     * create organization
     * @param $data
     */
    public function create(array $data)
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
    }

    /**
     * update organization
     * @param $data
     */
    public function update(array $data)
    {
        $payload = $this->fhir->format($data);
        $endpoint = $this->endpoint . '/' . $data['id'];
        return $this->satuSehat->put($endpoint, $payload);
    }
}
