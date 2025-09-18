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
     * Cari pasien berdasarkan NIK
     * @param $id
     */
    public function searchByID(string $id)
    {
        $endpoint = $this->endpoint  . '/' . $id;
        return $this->satuSehat->get($endpoint);
    }

    /**
     * create organization
     */
    public function create(array $data): array
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->post($this->endpoint, $payload);
    }

    public function update(array $data): array
    {
        $payload = $this->fhir->format($data);
        return $this->satuSehat->put($this->endpoint, $payload);
    }
}
